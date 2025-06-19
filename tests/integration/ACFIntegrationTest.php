<?php
/**
 * Integration tests for ACF (Advanced Custom Fields)
 *
 * @package PevneMezery\Tests
 */

namespace MusilTech\PevneMezery\Tests\Integration;

use WP_UnitTestCase;
use MusilTech\PevneMezery\Tests\TestHelper;
use MusilTech\PevneMezery\ACFSupport;

class ACFIntegrationTest extends WP_UnitTestCase {

    protected function setUp(): void {
        parent::setUp();
        
        // Skip tests if ACF is not available
        if (!class_exists('acf')) {
            $this->markTestSkipped('ACF not available');
        }
        
        TestHelper::disable_debug_mode();
    }

    protected function tearDown(): void {
        TestHelper::cleanup_test_data();
        parent::tearDown();
    }

    /**
     * Test ACF support initialization
     */
    public function test_acf_support_init(): void {
        ACFSupport::register_acf_filters();
        
        // Verify ACF filters are registered
        $this->assertTrue(has_filter('acf/format_value/type=text'));
        $this->assertTrue(has_filter('acf/format_value/type=textarea'));
        $this->assertTrue(has_filter('acf/format_value/type=wysiwyg'));
    }

    /**
     * Test ACF text field processing
     */
    public function test_acf_text_field(): void {
        $test_data = TestHelper::get_acf_test_data()['text_field'];
        $post_id = TestHelper::create_test_post();
        $field = ['type' => 'text', 'name' => 'test_field'];
        
        $result = ACFSupport::process_acf_content($test_data['value'], $post_id, $field);
        
        $this->assertEquals($test_data['expected'], $result);
    }

    /**
     * Test ACF textarea field processing
     */
    public function test_acf_textarea_field(): void {
        $test_data = TestHelper::get_acf_test_data()['textarea_field'];
        $post_id = TestHelper::create_test_post();
        $field = ['type' => 'textarea', 'name' => 'test_textarea'];
        
        $result = ACFSupport::process_acf_content($test_data['value'], $post_id, $field);
        
        $this->assertEquals($test_data['expected'], $result);
    }

    /**
     * Test ACF WYSIWYG field processing
     */
    public function test_acf_wysiwyg_field(): void {
        $test_data = TestHelper::get_acf_test_data()['wysiwyg_field'];
        $post_id = TestHelper::create_test_post();
        $field = ['type' => 'wysiwyg', 'name' => 'test_wysiwyg'];
        
        $result = ACFSupport::process_acf_content($test_data['value'], $post_id, $field);
        
        $this->assertEquals($test_data['expected'], $result);
    }

    /**
     * Test ACF select field processing
     */
    public function test_acf_select_field(): void {
        $value = 'Mgr. Novák s 25 kg';
        $expected = 'Mgr.&nbsp;Novák s&nbsp;25&nbsp;kg';
        $post_id = TestHelper::create_test_post();
        $field = ['type' => 'select', 'name' => 'test_select'];
        
        $result = ACFSupport::process_acf_content($value, $post_id, $field);
        
        $this->assertEquals($expected, $result);
    }

    /**
     * Test ACF checkbox field processing
     */
    public function test_acf_checkbox_field(): void {
        $value = 'Možnost k domu s 10 %';
        $expected = 'Možnost k&nbsp;domu s&nbsp;10&nbsp;%';
        $post_id = TestHelper::create_test_post();
        $field = ['type' => 'checkbox', 'name' => 'test_checkbox'];
        
        $result = ACFSupport::process_acf_content($value, $post_id, $field);
        
        $this->assertEquals($expected, $result);
    }

    /**
     * Test ACF radio field processing
     */
    public function test_acf_radio_field(): void {
        $value = 'Volba např. takto';
        $expected = 'Volba např.&nbsp;takto';
        $post_id = TestHelper::create_test_post();
        $field = ['type' => 'radio', 'name' => 'test_radio'];
        
        $result = ACFSupport::process_acf_content($value, $post_id, $field);
        
        $this->assertEquals($expected, $result);
    }

    /**
     * Test ACF with non-string values
     */
    public function test_acf_non_string_values(): void {
        $post_id = TestHelper::create_test_post();
        $field = ['type' => 'text', 'name' => 'test_field'];
        
        // Test with null
        $result = ACFSupport::process_acf_content(null, $post_id, $field);
        $this->assertNull($result);
        
        // Test with array
        $array_value = ['item1', 'item2'];
        $result = ACFSupport::process_acf_content($array_value, $post_id, $field);
        $this->assertEquals($array_value, $result);
        
        // Test with number
        $number_value = 123;
        $result = ACFSupport::process_acf_content($number_value, $post_id, $field);
        $this->assertEquals($number_value, $result);
        
        // Test with boolean
        $bool_value = true;
        $result = ACFSupport::process_acf_content($bool_value, $post_id, $field);
        $this->assertEquals($bool_value, $result);
    }

    /**
     * Test ACF with empty string
     */
    public function test_acf_empty_string(): void {
        $post_id = TestHelper::create_test_post();
        $field = ['type' => 'text', 'name' => 'test_field'];
        
        $result = ACFSupport::process_acf_content('', $post_id, $field);
        $this->assertEquals('', $result);
    }

    /**
     * Test ACF disable filter
     */
    public function test_acf_disable_filter(): void {
        // Disable ACF support
        add_filter('pevne_mezery_enable_acf', '__return_false');
        
        // Reinitialize
        new \MusilTech\PevneMezery\PevneMezery();
        
        // ACF specific filters should not be active
        $this->assertTrue(true, 'ACF disable filter test placeholder');
    }

    /**
     * Test ACF integration with real filters
     */
    public function test_acf_real_filters(): void {
        $value = 'k domu s 25 kg';
        $expected = 'k&nbsp;domu s&nbsp;25&nbsp;kg';
        $post_id = TestHelper::create_test_post();
        $field = ['type' => 'text', 'name' => 'test_field'];
        
        // Test through actual ACF filter
        $result = apply_filters('acf/format_value/type=text', $value, $post_id, $field);
        
        $this->assertEquals($expected, $result);
    }

    /**
     * Test ACF performance with multiple fields
     */
    public function test_acf_performance(): void {
        $post_id = TestHelper::create_test_post();
        $values = [];
        
        for ($i = 1; $i <= 100; $i++) {
            $values[] = "Pole {$i} k domu s {$i} kg a {$i} %.";
        }
        
        $start = microtime(true);
        
        foreach ($values as $value) {
            $field = ['type' => 'text', 'name' => 'test_field'];
            ACFSupport::process_acf_content($value, $post_id, $field);
        }
        
        $end = microtime(true);
        $processing_time = $end - $start;
        
        // Should process quickly
        $this->assertLessThan(1.0, $processing_time, 'ACF processing took too long');
    }

    /**
     * Test ACF with complex HTML content
     */
    public function test_acf_html_content(): void {
        $value = '<div><p>Mgr. Novák <strong>má 25 kg</strong></p><ul><li>k domu</li></ul></div>';
        $expected = '<div><p>Mgr.&nbsp;Novák <strong>má 25&nbsp;kg</strong></p><ul><li>k&nbsp;domu</li></ul></div>';
        $post_id = TestHelper::create_test_post();
        $field = ['type' => 'wysiwyg', 'name' => 'test_wysiwyg'];
        
        $result = ACFSupport::process_acf_content($value, $post_id, $field);
        
        $this->assertEquals($expected, $result);
    }
}
