<?php
/**
 * Unit tests for Utils class
 *
 * @package PevneMezery\Tests
 */

namespace MusilTech\PevneMezery\Tests\Unit;

use PHPUnit\Framework\TestCase;
use MusilTech\PevneMezery\Utils;

class UtilsTest extends TestCase {

    /**
     * Test regex escaping
     */
    public function test_escape_for_regex(): void {
        $input = ['test.string', 'another+string', 'special*chars', 'normal'];
        $expected = ['test\.string', 'another\+string', 'special\*chars', 'normal'];
        
        $result = Utils::escape_for_regex($input);
        $this->assertEquals($expected, $result);
    }

    /**
     * Test regex escaping with special characters
     */
    public function test_escape_special_regex_characters(): void {
        $input = ['.', '+', '*', '?', '[', '^', ']', '$', '(', ')', '{', '}', '=', '!', '<', '>', '|', ':', '-'];
        $result = Utils::escape_for_regex($input);
        
        foreach ($result as $escaped) {
            // Each character should be escaped with backslash
            $this->assertStringContains('\\', $escaped);
        }
    }

    /**
     * Test abbreviations replacement
     */
    public function test_replace_abbreviations(): void {
        $content = 'např. takto a apod. další text atd. konec';
        $abbreviations = ['např.', 'apod.', 'atd.'];
        
        $result = Utils::replace_abbreviations($content, $abbreviations);
        
        $this->assertStringContains('např.&nbsp;takto', $result);
        $this->assertStringContains('apod.&nbsp;další', $result);
        $this->assertStringContains('atd.&nbsp;konec', $result);
    }

    /**
     * Test abbreviations with word boundaries
     */
    public function test_abbreviations_word_boundaries(): void {
        $content = 'např. test a taknapř. něco';
        $abbreviations = ['např.'];
        
        $result = Utils::replace_abbreviations($content, $abbreviations);
        
        // Should replace only the word-bounded abbreviation
        $this->assertStringContains('např.&nbsp;test', $result);
        $this->assertStringContains('taknapř. něco', $result); // This should NOT be replaced
    }

    /**
     * Test empty abbreviations array
     */
    public function test_empty_abbreviations(): void {
        $content = 'např. test content';
        $abbreviations = [];
        
        $result = Utils::replace_abbreviations($content, $abbreviations);
        
        // Content should remain unchanged
        $this->assertEquals($content, $result);
    }

    /**
     * Test empty content
     */
    public function test_empty_content(): void {
        $content = '';
        $abbreviations = ['např.', 'atd.'];
        
        $result = Utils::replace_abbreviations($content, $abbreviations);
        
        $this->assertEquals('', $result);
    }

    /**
     * Test abbreviations case sensitivity
     */
    public function test_abbreviations_case_sensitivity(): void {
        $content = 'např. test NAPŘ. test Např. test';
        $abbreviations = ['např.'];
        
        $result = Utils::replace_abbreviations($content, $abbreviations);
        
        // Should only replace exact case match due to \b word boundary
        $this->assertStringContains('např.&nbsp;test', $result);
        // Other cases should remain unchanged based on current implementation
    }

    /**
     * Test special characters in abbreviations
     */
    public function test_special_characters_in_abbreviations(): void {
        $content = 'Ph.D. student a MVDr. veterinář';
        $abbreviations = ['Ph.D.', 'MVDr.'];
        
        $result = Utils::replace_abbreviations($content, $abbreviations);
        
        $this->assertStringContains('Ph.D.&nbsp;student', $result);
        $this->assertStringContains('MVDr.&nbsp;veterinář', $result);
    }

    /**
     * Test multiple occurrences
     */
    public function test_multiple_occurrences(): void {
        $content = 'např. první, např. druhý, např. třetí';
        $abbreviations = ['např.'];
        
        $result = Utils::replace_abbreviations($content, $abbreviations);
        
        // All occurrences should be replaced
        $expected_count = substr_count($result, 'např.&nbsp;');
        $this->assertEquals(3, $expected_count);
    }

    /**
     * Test performance with large arrays
     */
    public function test_performance_large_arrays(): void {
        $large_array = array_fill(0, 1000, 'test.string');
        
        $start = microtime(true);
        $result = Utils::escape_for_regex($large_array);
        $end = microtime(true);
        
        $processing_time = $end - $start;
        
        // Should process quickly
        $this->assertLessThan(0.1, $processing_time);
        $this->assertCount(1000, $result);
        $this->assertEquals('test\.string', $result[0]);
    }
}
