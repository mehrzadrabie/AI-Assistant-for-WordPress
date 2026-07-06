<?php
/**
 * High-level façade the rest of the plugin talks to instead of touching a
 * provider directly. Loads AI Configuration from the WordPress Options API,
 * builds prompts/conversation history, and delegates the actual request to
 * the configured KnittNet_AI_Provider_Interface implementation.
 */

if (!defined('ABSPATH')) {
    exit;
}

class KnittNet_AI_Manager {

    /** @var KnittNet_AI_Provider_Interface */
    private $provider;

    /** @var array Resolved AI Configuration (api key already excluded from anything logged). */
    private $config;

    public function __construct(KnittNet_AI_Provider_Interface $provider, array $config) {
        $this->provider = $provider;
        $this->config = wp_parse_args($config, array(
            'model'        => '',
            'temperature'  => 1.0,
            'max_tokens'   => 0,
            'system_prompt' => '',
            'timeout'      => 60,
            'streaming'    => true,
        ));
    }

    /**
     * Build an AIManager from the plugin's stored options (KnittNet →
     * AI Configuration). This is the normal way to obtain an instance.
     *
     * @param array $options The knittnet_options array (or a merged/testing variant).
     */
    public static function from_options(array $options) {
        $api_key = isset($options['openrouter_api_key']) ? (string) $options['openrouter_api_key'] : '';
        $provider = new KnittNet_OpenRouter_Provider($api_key);

        return new self($provider, array(
            'model'         => $options['openrouter_selected_model'] ?? '',
            'temperature'   => isset($options['ai_temperature']) ? (float) $options['ai_temperature'] : 1.0,
            'max_tokens'    => isset($options['ai_max_tokens']) ? (int) $options['ai_max_tokens'] : 0,
            'system_prompt' => $options['ai_system_prompt'] ?? '',
            'timeout'       => isset($options['ai_timeout']) ? (int) $options['ai_timeout'] : 60,
            'streaming'     => !empty($options['ai_streaming_enabled']),
        ));
    }

    public function get_provider() {
        return $this->provider;
    }

    /**
     * Generate a single, non-streaming reply.
     *
     * @param string $system_prompt     Bot-specific system instructions (already resolved by caller).
     * @param string $relevant_content  RAG context to append to the system message.
     * @param array  $conversation_history Raw history from the transcript store.
     * @param array  $overrides         Optional per-call overrides (model, tools, tool_choice, temperature...).
     * @return array{content?: string, error?: string, error_code?: string}
     */
    public function generate($system_prompt, $relevant_content, array $conversation_history, array $overrides = array()) {
        $history = KnittNet_Conversation_Manager::normalize($conversation_history);
        $messages = KnittNet_Prompt_Builder::build($system_prompt, $relevant_content, $history);
        $params = $this->resolve_params($overrides);

        $max_context_tokens = $params['max_tokens'] > 0 ? max(0, ($params['max_tokens'] * 8)) : 0;
        if ($max_context_tokens > 0) {
            $messages = KnittNet_Token_Counter::fit_to_budget($messages, $max_context_tokens);
        }

        return $this->provider->chat($messages, $params);
    }

    /**
     * Generate a streaming reply, invoking $on_delta for each text chunk.
     *
     * @param callable $on_delta function(string $chunk): void
     */
    public function generate_stream($system_prompt, $relevant_content, array $conversation_history, callable $on_delta, array $overrides = array()) {
        $history = KnittNet_Conversation_Manager::normalize($conversation_history);
        $messages = KnittNet_Prompt_Builder::build($system_prompt, $relevant_content, $history);
        $params = $this->resolve_params($overrides);

        if (empty($params['streaming']) || !$this->provider->supports_streaming()) {
            return $this->generate($system_prompt, $relevant_content, $conversation_history, $overrides);
        }

        return $this->provider->chat_stream($messages, $params, $on_delta);
    }

    private function resolve_params(array $overrides) {
        $params = array(
            'model'       => $overrides['model'] ?? $this->config['model'],
            'temperature' => $overrides['temperature'] ?? $this->config['temperature'],
            'max_tokens'  => $overrides['max_tokens'] ?? $this->config['max_tokens'],
            'timeout'     => $overrides['timeout'] ?? $this->config['timeout'],
            'streaming'   => array_key_exists('streaming', $overrides) ? $overrides['streaming'] : $this->config['streaming'],
        );
        if (!empty($overrides['tools'])) {
            $params['tools'] = $overrides['tools'];
        }
        if (!empty($overrides['tool_choice'])) {
            $params['tool_choice'] = $overrides['tool_choice'];
        }
        return $params;
    }
}
