<?php

namespace BitSpecter\PevneMezery;

class WooCommerceSupport
{
    public static function init(): void
    {
        // Add filters for product titles, descriptions, etc.
        add_filter('the_title', array(__CLASS__, 'process_product_title'), 10, 2);
        add_filter('woocommerce_short_description', array(__CLASS__, 'process_short_description'), 10, 1);
        add_filter('woocommerce_product_description', array(__CLASS__, 'process_long_description'), 10, 1);
        add_filter('woocommerce_product_reviews', array(__CLASS__, 'process_reviews'), 10, 1);

        // Process checkout and cart text
        add_filter('woocommerce_cart_item_name', array(__CLASS__, 'process_cart_item_names'), 10, 3);
        add_filter('woocommerce_checkout_order_review', array(__CLASS__, 'process_checkout_order_review'), 10, 1);
    }

    /**
     * Process product titles for non-breaking spaces.
     * 
     * @param string $title The product title.
     * @param int    $post_id The post ID.
     * @return string Processed title.
     */
    public static function process_product_title(string $title, $post_id): string
    {
        if (get_post_type($post_id) === 'product') {
            return ContentHandler::process_content($title);
        }
        return $title;
    }

    /**
     * Process WooCommerce short descriptions for non-breaking spaces.
     * 
     * @param string $description The short description of the product.
     * @return string Processed description.
     */
    public static function process_short_description(string $description): string
    {
        return ContentHandler::process_content($description);
    }

    /**
     * Process WooCommerce long descriptions for non-breaking spaces.
     * 
     * @param string $description The long description of the product.
     * @return string Processed description.
     */
    public static function process_long_description(string $description): string
    {
        return ContentHandler::process_content($description);
    }

    /**
     * Process WooCommerce product reviews for non-breaking spaces.
     * 
     * @param string $reviews The product reviews.
     * @return string Processed reviews.
     */
    public static function process_reviews(string $reviews): string
    {
        return ContentHandler::process_content($reviews);
    }
}
