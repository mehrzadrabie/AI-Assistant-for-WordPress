<?php
/**
 * Parses an OpenAI-compatible Server-Sent-Events chat-completions stream.
 *
 * OpenRouter's streaming wire format is identical to OpenAI's: newline-
 * delimited "data: {json}" frames terminated by a literal "data: [DONE]".
 * This class only understands that framing; it does not perform any HTTP
 * I/O itself, so it can be fed chunks from cURL, fsockopen, or a test fixture.
 */

if (!defined('ABSPATH')) {
    exit;
}

class KnittNet_Streaming_Manager {

    /** @var string Carry-over buffer for a line split across two chunks. */
    private $buffer = '';

    /** @var bool */
    private $done = false;

    /**
     * Feed a raw chunk of response bytes. Invokes $on_delta for every text
     * fragment found, and $on_done once when the [DONE] sentinel arrives.
     *
     * @param string   $chunk
     * @param callable $on_delta function(string $text_delta): void
     * @param callable|null $on_done function(): void
     */
    public function feed($chunk, callable $on_delta, callable $on_done = null) {
        $this->buffer .= $chunk;
        $lines = explode("\n", $this->buffer);
        $this->buffer = array_pop($lines);

        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || strpos($line, 'data: ') !== 0) {
                continue;
            }

            $json_str = trim(substr($line, 6));

            if ($json_str === '[DONE]') {
                $this->done = true;
                if ($on_done) {
                    $on_done();
                }
                continue;
            }

            $decoded = json_decode($json_str, true);
            if (is_array($decoded) && isset($decoded['choices'][0]['delta']['content'])) {
                $content = $decoded['choices'][0]['delta']['content'];
                if ($content !== '') {
                    $on_delta($content);
                }
            }
        }
    }

    public function is_done() {
        return $this->done;
    }
}
