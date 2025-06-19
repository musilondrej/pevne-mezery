<?php

namespace MusilTech\PevneMezery;

class ContentHandler
{
    /**
     * Debug mode flag. If true, replaces non-breaking spaces with a visible symbol.
     *
     * @var bool
     */
    private static $debug_mode = false;

    /**
     * Toggle debug mode.
     *
     * @param bool $enabled Enable or disable debug mode.
     * @return void
     */
    public static function set_debug_mode(bool $enabled): void
    {
        self::$debug_mode = $enabled;
    }

    /**
     * Main function to process the content and apply typographical rules.
     *
     * @param string $content The original content.
     * @return string The processed content.
     */
    public static function process_content(string $content): string
    {
        // Check if content is already cached
        $cached_content = CacheHandler::get_cached_content($content);
        
        if ($cached_content !== null) {
            return $cached_content;
        }

        // Split content into HTML and text parts
        $text_parts = preg_split('/(<[^>]+>)/', $content, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

        foreach ($text_parts as &$part) {
            // Process only text parts, skip HTML
            if (!preg_match('/^<.*>$/', $part)) {
                foreach (self::get_regex_rules() as $pattern => $replacement) {
                    $replacement = self::$debug_mode
                        ? str_replace('&nbsp;', '⭕️', $replacement)
                        : $replacement;
                    $part = preg_replace($pattern, $replacement, $part);
                }
            }
        }

        $processed_content = implode('', $text_parts);

        // Save processed content to cache
        CacheHandler::save_cached_content($content, $processed_content);

        return $processed_content;
    }

    /**
     * Defines the patterns and replacements for the typographical rules.
     * Returns an associative array where keys are regex patterns and values are replacements.
     *
     * @return array The array of regex rules.
     */
    private static function get_regex_rules(): array
    {
        return [
            // Matematické výrazy s pevnými mezerami
            '/(\d)\s+([+\-*\/=])\s+(\d)/u' => '$1&nbsp;$2&nbsp;$3',

            // Jednopísmenné předložky a spojky
            '/\b(k|s|v|z|o|u|a|i)\s+/iu' => '$1&nbsp;',

            // Pomlčka s pevnými mezerami
            '/\s*–\s*/u' => '&nbsp;–&nbsp;',
            '/(\d+)\s*–\s*(\d+)/u' => '$1–$2',

            // Jednotky a složené výrazy
            '/(\d+)\s+(l|h|min|s|ms|m|m²|km|cm|mm|ha|km²|MB|GB|kW|W|m\/s|km\/h|l\/\d+|°|°C|°F|Kč|€|\$|%|dní|lidí|kg)/u' => '$1&nbsp;$2',

            // Pořadová čísla a zkratky
            '/(\d+)\s*([%|kg])/u' => '$1&nbsp;$2',

            // Zkratky (např., apod., atd.)
            '/\b(např|atd|apod|tj|tzn|tzv|mj|cca|vs|resp|ap|fa|č|čj|čp|čís|kupř|mj|tj|tj.|tzn|tzv)\.\s+/u' => '$1.&nbsp;',

            // Tituly (např. JUDr., Ing., Mgr.)
            '/\b(JUDr|Ph\.D|LL\.B|MUDr|Mgr|Bc|Ing|CSc|Th\.D|MBA|DiS|prof|doc|RNDr|PhDr|PaedDr|ThLic|Dr|BcA|MgA|PharmDr|MVDr|JUDr|ThDr|Ph\.Mr|prof|etc)\.\s+/u' => '$1.&nbsp;',
            '/(\d+\.)\s+(\S)/u' => '$1&nbsp;$2',

            // Úhlové stupně, minuty, vteřiny
            '/(\d+)(\s*)(°|\'|\")/u' => '$1&nbsp;$3',

            // Lomítka
            '/\s+\/\s+/u' => ' / ',
            '/([a-zA-Z])\/([a-zA-Z])/u' => '$1/$2',

            // Exponenty a indexy
            '/(\d+)([²³])/u' => '$1$2',

            // Tři tečky
            '/(\S)\s*\.{3}/u' => '$1&hellip;',
            '/\.{3}\s+/u' => '&hellip;&nbsp;',
            '/\s+\.{3}\s+/u' => '&nbsp;&hellip;&nbsp;',
            '/(\S)…(\s|&nbsp;)/u' => '$1&hellip;&nbsp;',
            '/\s+…\s+/u' => '&nbsp;&hellip;&nbsp;',
            '/…(\s|&nbsp;)/u' => '&hellip;&nbsp;',

            // §
            '/\s+§\s+/u' => '&nbsp;§&nbsp;',
        ];
    }
}
