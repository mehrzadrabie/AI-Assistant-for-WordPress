<?php
/**
 * The only concrete AI provider KnittNet AI Assistant ships with: OpenRouter.
 *
 * OpenRouter exposes an OpenAI-compatible /chat/completions endpoint that
 * fans out to 100+ models from every major lab, so a single implementation
 * here covers every model the plugin offers. Swapping in a different
 * provider later means writing one new class against
 * KnittNet_AI_Provider_Interface — nothing else in the plugin depends on
 * OpenRouter specifically.
 *
 * @see https://openrouter.ai/docs
 */

if (!defined('ABSPATH')) {
    exit;
}

class KnittNet_OpenRouter_Provider implements KnittNet_AI_Provider_Interface {

    const API_URL = 'https://openrouter.ai/api/v1/chat/completions';

    /** @var string */
    private $api_key;

    /** @var int Max retry attempts for transient failures (connect refused, 429, 5xx). */
    private $max_attempts;

    public function __construct($api_key, $max_attempts = 3) {
        $this->api_key = (string) $api_key;
        $this->max_attempts = max(1, (int) $max_attempts);
    }

    public function get_name() {
        return 'OpenRouter';
    }

    public function supports_tools() {
        return true;
    }

    public function supports_streaming() {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function chat(array $messages, array $params = array()) {
        if ($this->api_key === '') {
            return KnittNet_AI_Error_Handler::build('missing_api_key', esc_html__('OpenRouter API key is not configured.', 'knittnet'));
        }
        if (empty($params['model'])) {
            return KnittNet_AI_Error_Handler::build('missing_model', esc_html__('No OpenRouter model selected.', 'knittnet'));
        }

        $body = $this->build_request_body($messages, $params, false);
        $timeout = isset($params['timeout']) ? (int) $params['timeout'] : 60;

        $last_error = null;
        for ($attempt = 0; $attempt < $this->max_attempts; $attempt++) {
            if ($attempt > 0) {
                usleep(min(2000, 250 * (2 ** $attempt)) * 1000);
            }

            $response = wp_remote_post(self::API_URL, array(
                'body'        => wp_json_encode($body),
                'headers'     => $this->build_headers(),
                'timeout'     => $timeout,
                'redirection' => 5,
                'sslverify'   => true,
            ));

            if (is_wp_error($response)) {
                $last_error = $response->get_error_message();
                if (!KnittNet_AI_Error_Handler::is_transient(0)) {
                    break;
                }
                continue;
            }

            $status = (int) wp_remote_retrieve_response_code($response);
            $decoded = json_decode(wp_remote_retrieve_body($response), true);

            if ($status === 200 && isset($decoded['choices'][0]['message']['content'])) {
                return array(
                    'content' => trim((string) $decoded['choices'][0]['message']['content']),
                    'raw'     => $decoded,
                );
            }

            $last_error = isset($decoded['error']['message']) ? $decoded['error']['message'] : ('HTTP ' . $status);

            if (!KnittNet_AI_Error_Handler::is_transient($status)) {
                break;
            }
        }

        return KnittNet_AI_Error_Handler::build('openrouter_api_error', (string) $last_error);
    }

    /**
     * @inheritDoc
     */
    public function chat_stream(array $messages, array $params, callable $on_delta) {
        if ($this->api_key === '') {
            return KnittNet_AI_Error_Handler::build('missing_api_key', esc_html__('OpenRouter API key is not configured.', 'knittnet'));
        }
        if (!function_exists('curl_init')) {
            // Environment can't stream; caller should fall back to chat().
            return KnittNet_AI_Error_Handler::build('streaming_unsupported', esc_html__('Streaming is not available on this server.', 'knittnet'));
        }

        $body = wp_json_encode($this->build_request_body($messages, $params, true));
        $timeout = isset($params['timeout']) ? (int) $params['timeout'] : 60;

        $full_response = '';
        $last_error = '';
        $http_code = 0;
        $curl_errno = 0;

        for ($attempt = 0; $attempt < $this->max_attempts; $attempt++) {
            if ($attempt > 0) {
                usleep(min(2000, 250 * (2 ** $attempt)) * 1000);
            }

            $full_response = '';
            $captured_status = 0;
            $pre_stream_body = '';
            $streaming_manager = new KnittNet_Streaming_Manager();

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, self::API_URL);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $this->build_headers_flat());
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);

            curl_setopt($ch, CURLOPT_HEADERFUNCTION, function ($ch, $header) use (&$captured_status) {
                if ($captured_status === 0 && preg_match('#^HTTP/\S+\s+(\d+)\b#', $header, $m)) {
                    $captured_status = (int) $m[1];
                }
                return strlen($header);
            });

            curl_setopt($ch, CURLOPT_WRITEFUNCTION, function ($ch, $data) use (&$full_response, &$captured_status, &$pre_stream_body, $streaming_manager, $on_delta) {
                if ($captured_status !== 0 && $captured_status !== 200) {
                    $pre_stream_body .= $data;
                    return strlen($data);
                }
                $streaming_manager->feed($data, function ($delta) use (&$full_response, $on_delta) {
                    $full_response .= $delta;
                    $on_delta($delta);
                });
                return strlen($data);
            });

            curl_exec($ch);
            $curl_errno = curl_errno($ch);
            $last_error = curl_error($ch);
            $http_code = $captured_status !== 0 ? $captured_status : (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if (!$curl_errno && $http_code === 200) {
                return array('content' => $full_response);
            }

            if ($pre_stream_body !== '') {
                $decoded = json_decode($pre_stream_body, true);
                if (isset($decoded['error']['message'])) {
                    $last_error = $decoded['error']['message'];
                }
            }

            if (!KnittNet_AI_Error_Handler::is_transient($http_code, $curl_errno)) {
                break;
            }
        }

        return KnittNet_AI_Error_Handler::build('openrouter_stream_error', (string) $last_error);
    }

    /**
     * Generate an image via an image-capable OpenRouter model (chat
     * completions with image output, e.g. Gemini's image-preview models).
     * Returns a data: URI on success so the caller can hand it straight to
     * wp_upload_bits() without a second round trip.
     *
     * @param string $prompt
     * @param array  $params  Expects 'model' (an image-output-capable OpenRouter model id).
     * @return array{image_base64?: string, mime_type?: string, error?: string, error_code?: string}
     */
    public function generate_image($prompt, array $params = array()) {
        if ($this->api_key === '') {
            return KnittNet_AI_Error_Handler::build('missing_api_key', esc_html__('OpenRouter API key is not configured.', 'knittnet'));
        }
        if (empty($params['model'])) {
            return KnittNet_AI_Error_Handler::build('missing_model', esc_html__('No image-capable OpenRouter model selected.', 'knittnet'));
        }

        $body = array(
            'model'      => $params['model'],
            'messages'   => array(array('role' => 'user', 'content' => $prompt)),
            'modalities' => array('image', 'text'),
        );

        $response = wp_remote_post(self::API_URL, array(
            'body'      => wp_json_encode($body),
            'headers'   => $this->build_headers(),
            'timeout'   => isset($params['timeout']) ? (int) $params['timeout'] : 90,
            'sslverify' => true,
        ));

        if (is_wp_error($response)) {
            return KnittNet_AI_Error_Handler::build('openrouter_connection_error', $response->get_error_message());
        }

        $status = (int) wp_remote_retrieve_response_code($response);
        $decoded = json_decode(wp_remote_retrieve_body($response), true);

        if ($status !== 200) {
            $message = isset($decoded['error']['message']) ? $decoded['error']['message'] : ('HTTP ' . $status);
            return KnittNet_AI_Error_Handler::build('openrouter_api_error', $message);
        }

        $images = $decoded['choices'][0]['message']['images'] ?? array();
        if (!empty($images[0]['image_url']['url'])) {
            $data_uri = $images[0]['image_url']['url'];
            if (preg_match('#^data:(image/[a-zA-Z0-9.+-]+);base64,(.+)$#', $data_uri, $m)) {
                return array('image_base64' => $m[2], 'mime_type' => $m[1]);
            }
            // Some models return a plain hosted URL instead of a data URI.
            return array('image_url' => $data_uri);
        }

        return KnittNet_AI_Error_Handler::build('openrouter_no_image', esc_html__('The model did not return an image.', 'knittnet'));
    }

    private function build_request_body(array $messages, array $params, $streaming) {
        $body = array(
            'model'    => $params['model'],
            'messages' => $messages,
        );
        if (isset($params['temperature'])) {
            $body['temperature'] = (float) $params['temperature'];
        }
        if (!empty($params['max_tokens'])) {
            $body['max_tokens'] = (int) $params['max_tokens'];
        }
        if (!empty($params['tools'])) {
            $body['tools'] = $params['tools'];
        }
        if (!empty($params['tool_choice'])) {
            $body['tool_choice'] = $params['tool_choice'];
        }
        if ($streaming) {
            $body['stream'] = true;
        }
        return $body;
    }

    private function build_headers() {
        return array(
            'Content-Type'  => 'application/json',
            'Authorization' => 'Bearer ' . $this->api_key,
            'HTTP-Referer'  => function_exists('home_url') ? home_url() : '',
            'X-Title'       => function_exists('get_bloginfo') ? get_bloginfo('name') : 'KnittNet AI Assistant',
        );
    }

    private function build_headers_flat() {
        $flat = array();
        foreach ($this->build_headers() as $key => $value) {
            $flat[] = $key . ': ' . $value;
        }
        return $flat;
    }
}
