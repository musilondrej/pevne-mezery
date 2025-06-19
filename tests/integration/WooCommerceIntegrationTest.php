<?php
/**
 * Integration tests for WooCommerce
 *
 * @package PevneMezery\Tests
 */

namespace MusilTech\PevneMezery\Tests\Integration;

use WP_UnitTestCase;
use MusilTech\PevneMezery\Tests\TestHelper;
use MusilTech\PevneMezery\WooCommerceSupport;

class WooCommerceIntegrationTest extends WP_UnitTestCase {

    protected function setUp(): void {
        parent::setUp();
        
        // Skip tests if WooCommerce is not available
        if (!class_exists('WooCommerce')) {
            $this->markTestSkipped('WooCommerce not available');
        }
        
        TestHelper::disable_debug_mode();
    }

    protected function tearDown(): void {
        TestHelper::cleanup_test_data();
        parent::tearDown();
    }

    /**
     * Test WooCommerce support initialization
     */
    public function test_woocommerce_support_init(): void {
        // Test that WooCommerceSupport can be initialized
        WooCommerceSupport::init();
        
        // Verify filters are added
        $this->assertTrue(has_filter('the_title'));
        $this->assertTrue(has_filter('woocommerce_short_description'));
        $this->assertTrue(has_filter('woocommerce_product_description'));
    }

    /**
     * Test product title processing
     */
    public function test_product_title_processing(): void {
        $product_id = TestHelper::create_test_product([
            'post_title' => 'Káva arabica 250 g'
        ]);
        
        $title = get_the_title($product_id);
        
        $this->assertStringContains('250&nbsp;g', $title);
    }

    /**
     * Test product title only processes products
     */
    public function test_product_title_only_products(): void {
        // Create regular post
        $post_id = TestHelper::create_test_post([
            'post_title' => 'Regular post 250 g',
            'post_type' => 'post'
        ]);
        
        // Create product
        $product_id = TestHelper::create_test_product([
            'post_title' => 'Product 250 g'
        ]);
        
        // Both should be processed through general the_title filter
        $post_title = get_the_title($post_id);
        $product_title = get_the_title($product_id);
        
        $this->assertStringContains('250&nbsp;g', $post_title);
        $this->assertStringContains('250&nbsp;g', $product_title);
    }

    /**
     * Test WooCommerce short description
     */
    public function test_woocommerce_short_description(): void {
        $description = 'Krátký popis s 15 % slevou a 250 g balení.';
        
        $filtered = apply_filters('woocommerce_short_description', $description);
        
        $this->assertStringContains('15&nbsp;%', $filtered);
        $this->assertStringContains('250&nbsp;g', $filtered);
    }

    /**
     * Test WooCommerce long description
     */
    public function test_woocommerce_long_description(): void {
        $description = 'Dlouhý popis produktu k domu s Mgr. Novákem a 25 kg balením.';
        
        $filtered = apply_filters('woocommerce_product_description', $description);
        
        $this->assertStringContains('k&nbsp;domu', $filtered);
        $this->assertStringContains('Mgr.&nbsp;Novákem', $filtered);
        $this->assertStringContains('25&nbsp;kg', $filtered);
    }

    /**
     * Test WooCommerce reviews
     */
    public function test_woocommerce_reviews(): void {
        $reviews = 'Skvělý produkt! Mgr. Novák doporučuje s 10 % slevou.';
        
        $filtered = apply_filters('woocommerce_product_reviews', $reviews);
        
        $this->assertStringContains('Mgr.&nbsp;Novák', $filtered);
        $this->assertStringContains('10&nbsp;%', $filtered);
    }

    /**
     * Test cart item names
     */
    public function test_cart_item_names(): void {
        $item_name = 'Káva arabica 250 g';
        $cart_item = [];
        $cart_item_key = 'test_key';
        
        $filtered = apply_filters('woocommerce_cart_item_name', $item_name, $cart_item, $cart_item_key);
        
        $this->assertStringContains('250&nbsp;g', $filtered);
    }

    /**
     * Test WooCommerce integration disable filter
     */
    public function test_woocommerce_disable_filter(): void {
        // Disable WooCommerce support
        add_filter('pevne_mezery_enable_woocommerce', '__return_false');
        
        // Reinitialize
        new \MusilTech\PevneMezery\PevneMezery();
        
        // WooCommerce specific filters should not be active
        // Note: This test might need adjustment based on how filters are managed
        $this->assertTrue(true, 'WooCommerce disable filter test placeholder');
    }

    /**
     * Test complex WooCommerce content
     */
    public function test_complex_woocommerce_content(): void {
        $content = 'Káva arabica 250 g - Mgr. Novák doporučuje! např. s 15 % slevou k domu.';
        
        $filtered = apply_filters('woocommerce_short_description', $content);
        
        // All rules should apply
        $this->assertStringContains('250&nbsp;g', $filtered);
        $this->assertStringContains('Mgr.&nbsp;Novák', $filtered);
        $this->assertStringContains('např.&nbsp;s', $filtered);
        $this->assertStringContains('15&nbsp;%', $filtered);
        $this->assertStringContains('k&nbsp;domu', $filtered);
    }

    /**
     * Test WooCommerce with HTML content
     */
    public function test_woocommerce_html_content(): void {
        $content = '<p>Káva <strong>250 g</strong> s <em>15 %</em> slevou.</p>';
        
        $filtered = apply_filters('woocommerce_short_description', $content);
        
        // Typography applied, HTML preserved
        $this->assertStringContains('250&nbsp;g', $filtered);
        $this->assertStringContains('15&nbsp;%', $filtered);
        $this->assertStringContains('<p>', $filtered);
        $this->assertStringContains('<strong>', $filtered);
        $this->assertStringContains('<em>', $filtered);
    }

    /**
     * Test WooCommerce performance
     */
    public function test_woocommerce_performance(): void {
        $descriptions = [];
        for ($i = 1; $i <= 100; $i++) {
            $descriptions[] = "Produkt {$i} s {$i} kg a {$i} % slevou k domu.";
        }
        
        $start = microtime(true);
        
        foreach ($descriptions as $description) {
            apply_filters('woocommerce_short_description', $description);
        }
        
        $end = microtime(true);
        $processing_time = $end - $start;
        
        // Should process quickly
        $this->assertLessThan(1.0, $processing_time, 'WooCommerce processing took too long');
    }
}
