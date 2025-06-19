<?php

namespace BitSpecter\PevneMezery;

class ACFSupport
{
    public static function register_acf_filters(): void
    {
        add_filter('acf/format_value/type=text', array(__CLASS__, 'process_acf_content'), 10, 3);
        add_filter('acf/format_value/type=textarea', array(__CLASS__, 'process_acf_content'), 10, 3);
        add_filter('acf/format_value/type=wysiwyg', array(__CLASS__, 'process_acf_content'), 10, 3);
        add_filter('acf/format_value/type=select', array(__CLASS__, 'process_acf_content'), 10, 3);
        add_filter('acf/format_value/type=checkbox', array(__CLASS__, 'process_acf_content'), 10, 3);
        add_filter('acf/format_value/type=radio', array(__CLASS__, 'process_acf_content'), 10, 3);
    }

    public static function process_acf_content($value, $post_id, $field): string
    {
        if (!is_string($value) || empty($value)) {
            return $value;
        }

        return ContentHandler::process_content($value);
    }
}
