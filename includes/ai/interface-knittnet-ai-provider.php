<?php
/**
 * Contract every AI provider must implement.
 *
 * KnittNet AI Assistant talks to exactly one concrete provider today
 * (KnittNet_OpenRouter_Provider), but every call site depends on this
 * interface rather than the concrete class so a future provider can be
 * dropped in without touching AIManager's callers.
 */

if (!defined('ABSPATH')) {
    exit;
}

interface KnittNet_AI_Provider_Interface {

    /**
     * Send a chat completion request and return the full response.
     *
     * @param array $messages Chat messages: [['role' => 'system'|'user'|'assistant', 'content' => string], ...]
     * @param array $params   Provider params: model, temperature, max_tokens, timeout, api_key, etc.
     * @return array{content?: string, error?: string, error_code?: string} On success: ['content' => string, 'raw' => array].
     *                                                                       On failure: ['error' => string, 'error_code' => string].
     */
    public function chat(array $messages, array $params = array());

    /**
     * Send a streaming chat completion request, invoking $on_delta for every
     * text chunk as it arrives.
     *
     * @param array    $messages Same shape as chat().
     * @param array    $params   Same shape as chat().
     * @param callable $on_delta function(string $chunk): void
     * @return array{content?: string, error?: string, error_code?: string}
     */
    public function chat_stream(array $messages, array $params, callable $on_delta);

    /** Human-readable provider name, e.g. "OpenRouter". */
    public function get_name();

    /** Whether this provider supports OpenAI-style function/tool calling. */
    public function supports_tools();

    /** Whether this provider supports streaming responses. */
    public function supports_streaming();
}
