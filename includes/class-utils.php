<?php

namespace BitSpecter\PevneMezery;

class Utils
{
    /**
     * Safely escape strings for regex.
     * 
     * @param array $strings Array of strings to escape
     * @return array Escaped strings
     */
    public static function escape_for_regex(array $strings): array
    {
        return array_map(function ($string) {
            return preg_quote($string, '/');
        }, $strings);
    }

    /**
     * Helper method to replace abbreviations in content.
     * 
     * @param string $content The content to process
     * @param array  $abbreviations Array of abbreviations
     * @return string Processed content
     */
    public static function replace_abbreviations(string $content, array $abbreviations): string
    {
        foreach ($abbreviations as $abbr) {
            $escaped_abbr = preg_quote($abbr, '/');
            $pattern = '/\b' . $escaped_abbr . '\b/u';
            $replacement = str_replace('.', '.&nbsp;', $abbr);
            $content = preg_replace($pattern, $replacement, $content);
        }
        return $content;
    }
}
