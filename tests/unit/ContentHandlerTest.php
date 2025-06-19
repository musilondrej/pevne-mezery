<?php
/**
 * Unit tests for ContentHandler class
 *
 * @package PevneMezery\Tests
 */

namespace MusilTech\PevneMezery\Tests\Unit;

use PHPUnit\Framework\TestCase;
use MusilTech\PevneMezery\ContentHandler;
use MusilTech\PevneMezery\Tests\TestHelper;

class ContentHandlerTest extends TestCase {

    protected function setUp(): void {
        parent::setUp();
        // Ensure debug mode is off for tests
        TestHelper::disable_debug_mode();
    }

    protected function tearDown(): void {
        TestHelper::disable_debug_mode();
        parent::tearDown();
    }

    /**
     * Test single letter prepositions
     */
    public function test_single_letter_prepositions(): void {
        $test_data = TestHelper::get_typography_test_data()['prepositions'];
        
        foreach ($test_data as $input => $expected) {
            $result = ContentHandler::process_content($input);
            $this->assertEquals($expected, $result, "Failed for input: {$input}");
        }
    }

    /**
     * Test units processing
     */
    public function test_units_processing(): void {
        $test_data = TestHelper::get_typography_test_data()['units'];
        
        foreach ($test_data as $input => $expected) {
            $result = ContentHandler::process_content($input);
            $this->assertEquals($expected, $result, "Failed for input: {$input}");
        }
    }

    /**
     * Test titles processing
     */
    public function test_titles_processing(): void {
        $test_data = TestHelper::get_typography_test_data()['titles'];
        
        foreach ($test_data as $input => $expected) {
            $result = ContentHandler::process_content($input);
            $this->assertEquals($expected, $result, "Failed for input: {$input}");
        }
    }

    /**
     * Test abbreviations processing
     */
    public function test_abbreviations_processing(): void {
        $test_data = TestHelper::get_typography_test_data()['abbreviations'];
        
        foreach ($test_data as $input => $expected) {
            $result = ContentHandler::process_content($input);
            $this->assertEquals($expected, $result, "Failed for input: {$input}");
        }
    }

    /**
     * Test mathematical expressions
     */
    public function test_mathematical_expressions(): void {
        $test_data = TestHelper::get_typography_test_data()['math'];
        
        foreach ($test_data as $input => $expected) {
            $result = ContentHandler::process_content($input);
            $this->assertEquals($expected, $result, "Failed for input: {$input}");
        }
    }

    /**
     * Test dash processing
     */
    public function test_dash_processing(): void {
        $test_data = TestHelper::get_typography_test_data()['dashes'];
        
        foreach ($test_data as $input => $expected) {
            $result = ContentHandler::process_content($input);
            $this->assertEquals($expected, $result, "Failed for input: {$input}");
        }
    }

    /**
     * Test special characters
     */
    public function test_special_characters(): void {
        $test_data = TestHelper::get_typography_test_data()['special'];
        
        foreach ($test_data as $input => $expected) {
            $result = ContentHandler::process_content($input);
            $this->assertEquals($expected, $result, "Failed for input: {$input}");
        }
    }

    /**
     * Test HTML preservation
     */
    public function test_html_preservation(): void {
        $test_data = TestHelper::get_html_test_data();
        
        foreach ($test_data as $input => $expected) {
            $result = ContentHandler::process_content($input);
            $this->assertEquals($expected, $result, "Failed for HTML input: {$input}");
        }
    }

    /**
     * Test empty and invalid inputs
     */
    public function test_empty_and_invalid_inputs(): void {
        // Empty string
        $this->assertEquals('', ContentHandler::process_content(''));
        
        // Whitespace only
        $this->assertEquals('   ', ContentHandler::process_content('   '));
        
        // HTML only
        $this->assertEquals('<br>', ContentHandler::process_content('<br>'));
        $this->assertEquals('<div></div>', ContentHandler::process_content('<div></div>'));
    }

    /**
     * Test complex mixed content
     */
    public function test_complex_mixed_content(): void {
        $input = 'Mgr. Novák má 25 kg a jde k domu s 15 % slevou. např. takto: 5 + 3 = 8.';
        $expected = 'Mgr.&nbsp;Novák má 25&nbsp;kg a&nbsp;jde k&nbsp;domu s&nbsp;15&nbsp;% slevou. např.&nbsp;takto: 5&nbsp;+&nbsp;3&nbsp;=&nbsp;8.';
        
        $result = ContentHandler::process_content($input);
        $this->assertEquals($expected, $result);
    }

    /**
     * Test HTML with mixed content
     */
    public function test_html_with_mixed_content(): void {
        $input = '<p>Mgr. Novák má <strong>25 kg</strong> a jde <em>k domu</em>.</p>';
        $expected = '<p>Mgr.&nbsp;Novák má <strong>25&nbsp;kg</strong> a&nbsp;jde <em>k&nbsp;domu</em>.</p>';
        
        $result = ContentHandler::process_content($input);
        $this->assertEquals($expected, $result);
    }

    /**
     * Test debug mode
     */
    public function test_debug_mode(): void {
        TestHelper::enable_debug_mode();
        
        $input = 'k domu';
        $result = ContentHandler::process_content($input);
        
        // Debug mode should replace &nbsp; with ⭕️
        $this->assertStringContains('⭕️', $result);
        $this->assertEquals('k⭕️domu', $result);
        
        TestHelper::disable_debug_mode();
    }

    /**
     * Test performance with large text
     */
    public function test_performance_large_text(): void {
        $large_text = str_repeat('Mgr. Novák má 25 kg a jde k domu. ', 1000);
        
        $start_time = microtime(true);
        $result = ContentHandler::process_content($large_text);
        $end_time = microtime(true);
        
        $processing_time = $end_time - $start_time;
        
        // Should process within reasonable time (less than 1 second)
        $this->assertLessThan(1.0, $processing_time, 'Processing took too long');
        
        // Should contain proper non-breaking spaces
        $this->assertStringContains('Mgr.&nbsp;Novák', $result);
        $this->assertStringContains('25&nbsp;kg', $result);
        $this->assertStringContains('k&nbsp;domu', $result);
    }

    /**
     * Test edge cases
     */
    public function test_edge_cases(): void {
        // Multiple spaces
        $this->assertEquals('k&nbsp;domu', ContentHandler::process_content('k  domu'));
        
        // Mixed case
        $this->assertEquals('K&nbsp;domu', ContentHandler::process_content('K domu'));
        
        // Numbers with decimals
        $this->assertEquals('5.5&nbsp;kg', ContentHandler::process_content('5.5 kg'));
        
        // Already processed content (should not double-process)
        $this->assertEquals('k&nbsp;domu', ContentHandler::process_content('k&nbsp;domu'));
    }

    /**
     * Test regex patterns don't conflict
     */
    public function test_regex_patterns_no_conflict(): void {
        // Test that multiple rules don't interfere with each other
        $input = 'Mgr. Novák (30 kg) jde k domu s 15 % – např. takto.';
        $result = ContentHandler::process_content($input);
        
        // Should apply all rules correctly
        $this->assertStringContains('Mgr.&nbsp;Novák', $result);
        $this->assertStringContains('30&nbsp;kg', $result);
        $this->assertStringContains('k&nbsp;domu', $result);
        $this->assertStringContains('15&nbsp;%', $result);
        $this->assertStringContains('např.&nbsp;takto', $result);
    }
}
