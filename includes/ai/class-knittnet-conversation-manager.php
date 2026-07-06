<?php
/**
 * Normalizes raw conversation-history input (as read from the transcripts
 * table / session transient) into the shape KnittNet_Prompt_Builder expects,
 * and keeps it within a sane size before it is sent to the provider.
 */

if (!defined('ABSPATH')) {
    exit;
}

class KnittNet_Conversation_Manager {

    /** Default cap on how many prior turns are sent upstream per request. */
    const DEFAULT_MAX_TURNS = 40;

    /**
     * @param mixed $history Raw history, expected to be a list of ['role' => ..., 'content' => ...].
     * @param int   $max_turns Keep only the most recent N turns.
     * @return array Sanitized, capped conversation history.
     */
    public static function normalize($history, $max_turns = self::DEFAULT_MAX_TURNS) {
        if (!is_array($history)) {
            return array();
        }

        $clean = array();
        foreach ($history as $entry) {
            if (!is_array($entry) || !isset($entry['role']) || !isset($entry['content'])) {
                continue;
            }
            if ($entry['content'] === '' || $entry['content'] === null) {
                continue;
            }
            $clean[] = array(
                'role'    => $entry['role'],
                'content' => $entry['content'],
            );
        }

        if ($max_turns > 0 && count($clean) > $max_turns) {
            $clean = array_slice($clean, -$max_turns);
        }

        return $clean;
    }
}
