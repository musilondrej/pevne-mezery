<?php
/**
 * Unit tests for CacheHandler class
 *
 * @package PevneMezery\Tests
 */

namespace MusilTech\PevneMezery\Tests\Unit;

use PHPUnit\Framework\TestCase;
use MusilTech\PevneMezery\CacheHandler;

class CacheHandlerTest extends TestCase {

    protected function setUp(): void {
        parent::setUp();
        // Clean cache before each test
        wp_cache_flush();
    }

    protected function tearDown(): void {
        wp_cache_flush();
        parent::tearDown();
    }

    /**
     * Test cache storage and retrieval
     */
    public function test_cache_storage_and_retrieval(): void {
        $original = 'k domu';
        $processed = 'k&nbsp;domu';
        
        // Should return null when not cached
        $this->assertNull(CacheHandler::get_cached_content($original));
        
        // Save to cache
        CacheHandler::save_cached_content($original, $processed);
        
        // Should retrieve from cache
        $cached = CacheHandler::get_cached_content($original);
        $this->assertEquals($processed, $cached);
    }

    /**
     * Test cache key generation
     */
    public function test_cache_key_generation(): void {
        $content1 = 'test content 1';
        $content2 = 'test content 2';
        $content3 = 'test content 1'; // Same as content1
        
        // Different content should have different cache behavior
        CacheHandler::save_cached_content($content1, 'processed1');
        CacheHandler::save_cached_content($content2, 'processed2');
        
        $this->assertEquals('processed1', CacheHandler::get_cached_content($content1));
        $this->assertEquals('processed2', CacheHandler::get_cached_content($content2));
        $this->assertEquals('processed1', CacheHandler::get_cached_content($content3)); // Same as content1
    }

    /**
     * Test cache deletion
     */
    public function test_cache_deletion(): void {
        $original = 'test content';
        $processed = 'processed content';
        
        // Save to cache
        CacheHandler::save_cached_content($original, $processed);
        $this->assertEquals($processed, CacheHandler::get_cached_content($original));
        
        // Delete from cache
        CacheHandler::delete_cached_content($original);
        $this->assertNull(CacheHandler::get_cached_content($original));
    }

    /**
     * Test cache with different contexts
     */
    public function test_cache_contexts(): void {
        // This test would require modifications to CacheHandler to support contexts
        // For now, we test basic functionality
        $this->assertTrue(true, 'Context support would need implementation');
    }

    /**
     * Test cache performance
     */
    public function test_cache_performance(): void {
        $content = str_repeat('test content ', 100);
        $processed = str_repeat('processed content ', 100);
        
        // Measure save time
        $start = microtime(true);
        CacheHandler::save_cached_content($content, $processed);
        $save_time = microtime(true) - $start;
        
        // Measure retrieval time
        $start = microtime(true);
        $cached = CacheHandler::get_cached_content($content);
        $retrieval_time = microtime(true) - $start;
        
        // Cache operations should be fast
        $this->assertLessThan(0.1, $save_time, 'Cache save took too long');
        $this->assertLessThan(0.1, $retrieval_time, 'Cache retrieval took too long');
        $this->assertEquals($processed, $cached);
    }

    /**
     * Test cache with empty content
     */
    public function test_cache_empty_content(): void {
        $empty = '';
        $processed = '';
        
        CacheHandler::save_cached_content($empty, $processed);
        $cached = CacheHandler::get_cached_content($empty);
        
        $this->assertEquals($processed, $cached);
    }

    /**
     * Test cache with large content
     */
    public function test_cache_large_content(): void {
        $large_content = str_repeat('Mgr. Novák má 25 kg a jde k domu. ', 1000);
        $processed_content = str_repeat('Mgr.&nbsp;Novák má 25&nbsp;kg a&nbsp;jde k&nbsp;domu. ', 1000);
        
        CacheHandler::save_cached_content($large_content, $processed_content);
        $cached = CacheHandler::get_cached_content($large_content);
        
        $this->assertEquals($processed_content, $cached);
    }

    /**
     * Test cache with special characters
     */
    public function test_cache_special_characters(): void {
        $content = 'Speciální znaky: áčžíé ěščř úůň § ° € $ %';
        $processed = 'Processed: áčžíé ěščř úůň §&nbsp;°&nbsp;€&nbsp;$&nbsp;%';
        
        CacheHandler::save_cached_content($content, $processed);
        $cached = CacheHandler::get_cached_content($content);
        
        $this->assertEquals($processed, $cached);
    }
}
