<?php
/**
 * Turns raw provider/transport failures into a structured, safe-to-display
 * error shape and decides whether a failure is worth retrying.
 */

if (!defined('ABSPATH')) {
    exit;
}

class KnittNet_AI_Error_Handler {

    /**
     * Build the standard error array returned by AIManager on failure.
     *
     * @param string $error_code  Machine-readable code, e.g. 'missing_api_key'.
     * @param string $message     Human-readable detail (may be shown to admins only).
     * @param string $provider    Provider label, e.g. 'OpenRouter'.
     */
    public static function build($error_code, $message, $provider = 'OpenRouter') {
        return array(
            'error'      => self::for_audience($message, $provider),
            'error_code' => $error_code,
            'provider'   => strtolower($provider),
        );
    }

    /**
     * Format a message appropriately for who will see it: admins get the raw
     * provider detail plus an actionable hint, visitors get a generic
     * fallback so API internals (model names, quota/billing text) never leak
     * to the public.
     */
    public static function for_audience($raw_message, $provider = 'OpenRouter') {
        $raw = trim((string) $raw_message);

        if (function_exists('current_user_can') && current_user_can('manage_options')) {
            if (self::looks_like_model_access_error($raw)) {
                return $raw !== ''
                    ? sprintf(
                        /* translators: %s: raw provider error detail */
                        esc_html__('The selected AI model isn\'t available on your OpenRouter key. Choose another model in KnittNet → AI Configuration. (Details: %s)', 'knittnet'),
                        $raw
                    )
                    : esc_html__('The selected AI model isn\'t available on your OpenRouter key. Choose another model in KnittNet → AI Configuration.', 'knittnet');
            }
            return $raw !== ''
                ? sprintf(
                    /* translators: 1: provider label, 2: raw provider error detail */
                    esc_html__('%1$s returned an error: %2$s. Check your API key and model in KnittNet → AI Configuration.', 'knittnet'),
                    $provider,
                    $raw
                )
                : sprintf(
                    /* translators: %s: provider label */
                    esc_html__('%s returned an error. Check your API key and model in KnittNet → AI Configuration.', 'knittnet'),
                    $provider
                );
        }

        return esc_html__('Sorry, I\'m having trouble responding right now. Please try again in a moment.', 'knittnet');
    }

    private static function looks_like_model_access_error($raw) {
        $low = strtolower($raw);
        foreach (array('not available', 'does not have access', 'do not have access', 'does not exist', 'model_not_found', 'not_found_error', 'model not found', 'not found', 'permission_denied', 'permission denied') as $needle) {
            if (strpos($low, $needle) !== false) {
                return true;
            }
        }
        return false;
    }

    /**
     * Whether an HTTP status / cURL error looks transient (worth a retry)
     * rather than a permanent configuration problem.
     */
    public static function is_transient($http_code, $curl_errno = 0) {
        if ($curl_errno !== 0) {
            return true; // Connection-level failure (timeout, DNS, reset).
        }
        if ($http_code === 0) {
            return true;
        }
        if ($http_code === 429) {
            return true; // Rate limited.
        }
        if ($http_code >= 500) {
            return true; // Upstream/provider outage.
        }
        return false;
    }
}
