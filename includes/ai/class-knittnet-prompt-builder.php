<?php
/**
 * Builds the final OpenAI-compatible messages array sent to the provider:
 * system prompt + RAG context, followed by the normalized conversation
 * history.
 */

if (!defined('ABSPATH')) {
    exit;
}

class KnittNet_Prompt_Builder {

    /**
     * @param string $system_prompt     Base system instructions for the bot.
     * @param string $relevant_content  Retrieved RAG context to append to the system message.
     * @param array  $conversation_history Prior turns: [['role' => ..., 'content' => ...], ...]
     * @param string $user_message      The current user message. Pass '' if already the last
     *                                  entry of $conversation_history.
     * @return array Messages array ready for KnittNet_AI_Provider_Interface::chat().
     */
    public static function build($system_prompt, $relevant_content, array $conversation_history, $user_message = '') {
        $messages = array();

        $system_content = trim($system_prompt . ' ' . $relevant_content);
        $messages[] = array(
            'role'    => 'system',
            'content' => $system_content,
        );

        foreach ($conversation_history as $entry) {
            if (!is_array($entry) || !isset($entry['role']) || !isset($entry['content'])) {
                continue;
            }
            $messages[] = array(
                'role'    => self::normalize_role($entry['role']),
                'content' => $entry['content'],
            );
        }

        if ($user_message !== '') {
            $messages[] = array(
                'role'    => 'user',
                'content' => $user_message,
            );
        }

        return $messages;
    }

    /** Map internal role names (bot/agent) onto the OpenAI-compatible role set. */
    public static function normalize_role($role) {
        if ($role === 'bot' || $role === 'agent') {
            return 'assistant';
        }
        if (!in_array($role, array('system', 'assistant', 'user', 'tool'), true)) {
            return 'user';
        }
        return $role;
    }
}
