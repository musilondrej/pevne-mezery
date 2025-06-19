<?php

namespace BitSpecter\PevneMezery;

class PevneMezery
{
    public function __construct()
    {
        $filters = apply_filters('pevne_mezery', [
            'comment_author',
            'term_name',
            'link_name',
            'link_description',
            'link_notes',
            'bloginfo',
            'wp_title',
            'widget_title',
            'term_description',
            'the_title',
            'the_content',
            'the_excerpt',
            'comment_text',
            'single_post_title',
            'list_cats',
        ]);

        foreach ($filters as $filter) {
            add_filter($filter, [ContentHandler::class, 'process_content']);
        }

        // WooCommerce integration - only load if enabled
        if (class_exists('woocommerce') && apply_filters('pevne_mezery_enable_woocommerce', true)) {
            WooCommerceSupport::init();
        }

        // ACF integration - only load if enabled
        if (class_exists('acf') && apply_filters('pevne_mezery_enable_acf', true)) {
            ACFSupport::register_acf_filters();
        }
    }
}
