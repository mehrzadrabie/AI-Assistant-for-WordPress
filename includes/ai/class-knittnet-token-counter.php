<?php
/**
 * Lightweight token estimation.
 *
 * This is a heuristic (no model-specific BPE tokenizer is bundled with the
 * plugin), tuned to the ~4-characters-per-token average that OpenAI and
 * Anthropic both publish for English text. It is accurate enough for
 * budgeting max_tokens / trimming conversation history; it is not an exact
 * count and must not be relied on for billing reconciliation.
 */

if (!defined('ABSPATH')) {
    exit;
}

class KnittNet_Token_Counter {

    const CHARS_PER_TOKEN = 4;

    /** Estimate the token count of a single string. */
    public static function count($text) {
        $text = (string) $text;
        if ($text === '') {
            return 0;
        }
        return (int) ceil(mb_strlen($text) / self::CHARS_PER_TOKEN);
    }

    /** Estimate the total token count of a messages array (role + content). */
    public static function count_messages(array $messages) {
        $total = 0;
        foreach ($messages as $message) {
            if (isset($message['content'])) {
                $total += self::count($message['content']);
            }
            $total += 4; // Per-message role/formatting overhead, OpenAI-style estimate.
        }
        return $total;
    }

    /**
     * Trim the oldest non-system messages from $messages until the total
     * estimated token count fits within $max_tokens. The system message (if
     * present as the first entry) is always preserved.
     */
    public static function fit_to_budget(array $messages, $max_tokens) {
        if ($max_tokens <= 0) {
            return $messages;
        }
        while (self::count_messages($messages) > $max_tokens && count($messages) > 1) {
            $has_leading_system = isset($messages[0]['role']) && $messages[0]['role'] === 'system';
            $remove_index = $has_leading_system ? 1 : 0;
            if (!isset($messages[$remove_index])) {
                break;
            }
            array_splice($messages, $remove_index, 1);
        }
        return $messages;
    }
}
