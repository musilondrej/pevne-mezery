<?php

namespace BitSpecter\PevneMezery;

class ContentHandler
{
    /**
     * Main function to process the content and apply typographical rules.
     *
     * @param string $content The original content.
     * @return string The processed content.
     */
    public static function process_content(string $content): string
    {
        // Split content into HTML and text parts
        $text_parts = preg_split('/(<[^>]+>)/', $content, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

        foreach ($text_parts as &$part) {
            // Process only text parts, skip HTML
            if (!preg_match('/^<.*>$/', $part)) {
                foreach (self::get_regex_rules() as $pattern => $replacement) {
                    $part = preg_replace($pattern, $replacement, $part);
                }
            }
        }

        return implode('', $text_parts);
    }

    /**
     * Defines the patterns and replacements for the typographical rules.
     * Returns an associative array where keys are regex patterns and values are replacements.
     *
     * @return array The array of regex rules.
     */
    private static function get_regex_rules(): array
    {
        // Using combined regex for similar rules and more efficient replacements
        return [
            // Math expressions with non-breakable spaces
            '/(\d)\s+([+\-*\/=])\s+(\d)/u' => '$1&nbsp;$2&nbsp;$3',

            // Single-character prepositions and conjunctions
            '/\b(k|s|v|z|o|u|a|i)\s+/iu' => '$1&nbsp;',

            '/\s*–\s*/u' => '&nbsp;–&nbsp;', // Specific rule for en dash

            // Units of measurement
            '/(\d+)\s+(h|min|s|ms|m|m²|km|cm|mm|ha|km²|MB|GB|m\/s|km\/h|°|°C|°F|Kč|€|\$|%|dní|lidí)/u' => '$1&nbsp;$2',

            // Czech abbreviations  
            '/\b(Bc|Mgr|Ing|Ph\.D|LL\.B|MUDr|JUDr|prof|voj|čet|rtm|por|kpt|plk|gen|Dr|doc|cca|č|čís|čj|čp|fa|fě|fy|kupř|mj|např|p|pí|popř|př|přib|přibl|sl|str|sv|tj|tzn|tzv|zvl)\.\s+/u' => '$1.&nbsp;',

            // Space after ordered number
            '/(\d+\.)\s+([0-9a-záčďéěíňóřšťúýž])/u' => '$1&nbsp;$2', // After ordinal numbers
        ];
    }
}
