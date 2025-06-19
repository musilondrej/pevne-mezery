<?php
/**
 * Test helper class
 *
 * @package PevneMezery\Tests
 */

namespace MusilTech\PevneMezery\Tests;

class TestHelper {
    
    /**
     * Sample test data for typographical rules
     */
    public static function get_typography_test_data(): array {
        return [
            // Jednopísmenné předložky
            'prepositions' => [
                'k domu' => 'k&nbsp;domu',
                's přáteli' => 's&nbsp;přáteli',
                'v práci' => 'v&nbsp;práci',
                'z města' => 'z&nbsp;města',
                'o tom' => 'o&nbsp;tom',
                'u nás' => 'u&nbsp;nás',
                'a také' => 'a&nbsp;také',
                'i když' => 'i&nbsp;když',
            ],
            
            // Jednotky
            'units' => [
                '10 kg' => '10&nbsp;kg',
                '25 m' => '25&nbsp;m',
                '100 km' => '100&nbsp;km',
                '30 °C' => '30&nbsp;°C',
                '150 Kč' => '150&nbsp;Kč',
                '5 %' => '5&nbsp;%',
                '2 l' => '2&nbsp;l',
                '60 min' => '60&nbsp;min',
            ],
            
            // Tituly
            'titles' => [
                'Mgr. Novák' => 'Mgr.&nbsp;Novák',
                'JUDr. Svoboda' => 'JUDr.&nbsp;Svoboda',
                'Ph.D. student' => 'Ph.D&nbsp;student',
                'prof. Dvořák' => 'prof.&nbsp;Dvořák',
                'Ing. Černý' => 'Ing.&nbsp;Černý',
            ],
            
            // Zkratky
            'abbreviations' => [
                'např. takto' => 'např.&nbsp;takto',
                'atd. pokračování' => 'atd.&nbsp;pokračování',
                'apod. další' => 'apod.&nbsp;další',
                'tj. znamená' => 'tj.&nbsp;znamená',
                'tzn. tedy' => 'tzn.&nbsp;tedy',
            ],
            
            // Matematické výrazy
            'math' => [
                '5 + 3 = 8' => '5&nbsp;+&nbsp;3&nbsp;=&nbsp;8',
                '10 - 2 = 8' => '10&nbsp;-&nbsp;2&nbsp;=&nbsp;8',
                '4 * 5 = 20' => '4&nbsp;*&nbsp;5&nbsp;=&nbsp;20',
                '15 / 3 = 5' => '15&nbsp;/&nbsp;3&nbsp;=&nbsp;5',
            ],
            
            // Pomlčky
            'dashes' => [
                '10 – 20' => '10&nbsp;–&nbsp;20',
                'Praha – Brno' => 'Praha&nbsp;–&nbsp;Brno',
                '2020 – 2025' => '2020–2025', // Číselné rozsahy bez mezer
            ],
            
            // Speciální znaky
            'special' => [
                'viz § 5' => 'viz&nbsp;§&nbsp;5',
                'text...' => 'text&hellip;',
                'text ... pokračování' => 'text&nbsp;&hellip;&nbsp;pokračování',
            ],
        ];
    }
    
    /**
     * Get HTML test cases to ensure HTML is not processed
     */
    public static function get_html_test_data(): array {
        return [
            '<p>k domu</p>' => '<p>k&nbsp;domu</p>',
            '<a href="test">s přáteli</a>' => '<a href="test">s&nbsp;přáteli</a>',
            '<strong>10 kg</strong>' => '<strong>10&nbsp;kg</strong>',
            '<span class="test">Mgr. Novák</span>' => '<span class="test">Mgr.&nbsp;Novák</span>',
            '<div><p>např. takto</p></div>' => '<div><p>např.&nbsp;takto</p></div>',
        ];
    }
    
    /**
     * Get WooCommerce test data
     */
    public static function get_woocommerce_test_data(): array {
        return [
            'product_names' => [
                'Káva arabica 250 g' => 'Káva arabica 250&nbsp;g',
                'Čaj zelený 100 g' => 'Čaj zelený 100&nbsp;g',
                'Mléko 1 l' => 'Mléko 1&nbsp;l',
            ],
            'descriptions' => [
                'Výborná káva s 15 % slevou' => 'Výborná káva s&nbsp;15&nbsp;% slevou',
                'Kvalitní čaj o hmotnosti 100 g' => 'Kvalitní čaj o&nbsp;hmotnosti 100&nbsp;g',
            ],
        ];
    }
    
    /**
     * Get ACF test data
     */
    public static function get_acf_test_data(): array {
        return [
            'text_field' => [
                'value' => 'Mgr. Novák má 25 kg',
                'expected' => 'Mgr.&nbsp;Novák má 25&nbsp;kg',
            ],
            'textarea_field' => [
                'value' => "První řádek k domu.\nDruhý řádek s 10 kg.",
                'expected' => "První řádek k&nbsp;domu.\nDruhý řádek s&nbsp;10&nbsp;kg.",
            ],
            'wysiwyg_field' => [
                'value' => '<p>např. takto můžeme psát s 15 %</p>',
                'expected' => '<p>např.&nbsp;takto můžeme psát s&nbsp;15&nbsp;%</p>',
            ],
        ];
    }
    
    /**
     * Create mock WordPress post
     */
    public static function create_test_post(array $args = []): int {
        $defaults = [
            'post_title' => 'Test Post k domu',
            'post_content' => 'Test content s 10 kg.',
            'post_status' => 'publish',
            'post_type' => 'post',
        ];
        
        $args = wp_parse_args($args, $defaults);
        
        return wp_insert_post($args);
    }
    
    /**
     * Create mock WooCommerce product
     */
    public static function create_test_product(array $args = []): int {
        if (!class_exists('WC_Product')) {
            return 0;
        }
        
        $defaults = [
            'post_title' => 'Test Product 250 g',
            'post_content' => 'Product description s 15 % slevou.',
            'post_status' => 'publish',
            'post_type' => 'product',
        ];
        
        $args = wp_parse_args($args, $defaults);
        
        return wp_insert_post($args);
    }
    
    /**
     * Clean up test data
     */
    public static function cleanup_test_data(): void {
        // Clean posts
        $posts = get_posts([
            'post_type' => ['post', 'product'],
            'post_status' => 'any',
            'numberposts' => -1,
            'meta_query' => [
                [
                    'key' => '_test_data',
                    'value' => 'pevne_mezery_test',
                    'compare' => '='
                ]
            ]
        ]);
        
        foreach ($posts as $post) {
            wp_delete_post($post->ID, true);
        }
        
        // Clean cache
        wp_cache_flush();
    }
    
    /**
     * Enable debug mode for tests
     */
    public static function enable_debug_mode(): void {
        \MusilTech\PevneMezery\ContentHandler::set_debug_mode(true);
    }
    
    /**
     * Disable debug mode
     */
    public static function disable_debug_mode(): void {
        \MusilTech\PevneMezery\ContentHandler::set_debug_mode(false);
    }
}
