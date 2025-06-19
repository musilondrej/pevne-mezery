<?php
/**
 * Integration tests for WordPress hooks and filters
 *
 * @package PevneMezery\Tests
 */

namespace MusilTech\PevneMezery\Tests\Integration;

use WP_UnitTestCase;
use MusilTech\PevneMezery\Tests\TestHelper;
use MusilTech\PevneMezery\ContentHandler;

class WordPressIntegrationTest extends WP_UnitTestCase {

    protected function setUp(): void {
        parent::setUp();
        TestHelper::disable_debug_mode();
    }

    protected function tearDown(): void {
        TestHelper::cleanup_test_data();
        parent::tearDown();
    }

    /**
     * Test the_title filter
     */
    public function test_the_title_filter(): void {
        $post_id = TestHelper::create_test_post([
            'post_title' => 'Test k domu s 10 kg'
        ]);
        
        $title = get_the_title($post_id);
        
        $this->assertStringContains('k&nbsp;domu', $title);
        $this->assertStringContains('10&nbsp;kg', $title);
    }

    /**
     * Test the_content filter
     */
    public function test_the_content_filter(): void {
        $post_id = TestHelper::create_test_post([
            'post_content' => 'Mgr. Novák má 25 kg a jde k domu.'
        ]);
        
        $content = apply_filters('the_content', get_post_field('post_content', $post_id));
        
        $this->assertStringContains('Mgr.&nbsp;Novák', $content);
        $this->assertStringContains('25&nbsp;kg', $content);
        $this->assertStringContains('k&nbsp;domu', $content);
    }

    /**
     * Test the_excerpt filter
     */
    public function test_the_excerpt_filter(): void {
        $post_id = TestHelper::create_test_post([
            'post_excerpt' => 'Krátký výňatek s 15 % slevou.'
        ]);
        
        $excerpt = apply_filters('the_excerpt', get_post_field('post_excerpt', $post_id));
        
        $this->assertStringContains('15&nbsp;%', $excerpt);
    }

    /**
     * Test comment filters
     */
    public function test_comment_filters(): void {
        $post_id = TestHelper::create_test_post();
        
        $comment_id = wp_insert_comment([
            'comment_post_ID' => $post_id,
            'comment_content' => 'Komentář k domu s 20 kg.',
            'comment_author' => 'Mgr. Tester',
            'comment_approved' => 1,
        ]);
        
        $comment = get_comment($comment_id);
        
        // Test comment_text filter
        $filtered_text = apply_filters('comment_text', $comment->comment_content);
        $this->assertStringContains('k&nbsp;domu', $filtered_text);
        $this->assertStringContains('20&nbsp;kg', $filtered_text);
        
        // Test comment_author filter
        $filtered_author = apply_filters('comment_author', $comment->comment_author);
        $this->assertStringContains('Mgr.&nbsp;Tester', $filtered_author);
    }

    /**
     * Test widget_title filter
     */
    public function test_widget_title_filter(): void {
        $title = 'Widget s 10 % obsahem';
        $filtered_title = apply_filters('widget_title', $title);
        
        $this->assertStringContains('10&nbsp;%', $filtered_title);
    }

    /**
     * Test bloginfo filter
     */
    public function test_bloginfo_filter(): void {
        // Set site description with typographic content
        update_option('blogdescription', 'Blog k domu s 100 % obsahem');
        
        $description = get_bloginfo('description');
        
        $this->assertStringContains('k&nbsp;domu', $description);
        $this->assertStringContains('100&nbsp;%', $description);
    }

    /**
     * Test filter customization
     */
    public function test_filter_customization(): void {
        // Remove the_title from filters
        add_filter('pevne_mezery', function($filters) {
            $key = array_search('the_title', $filters);
            if ($key !== false) {
                unset($filters[$key]);
            }
            return $filters;
        });
        
        // Reinitialize plugin to apply filter changes
        new \MusilTech\PevneMezery\PevneMezery();
        
        $post_id = TestHelper::create_test_post([
            'post_title' => 'Title k domu'
        ]);
        
        $title = get_the_title($post_id);
        
        // Title should NOT be processed now
        $this->assertStringNotContains('k&nbsp;domu', $title);
        $this->assertStringContains('k domu', $title);
    }

    /**
     * Test multiple filters working together
     */
    public function test_multiple_filters_integration(): void {
        $post_id = TestHelper::create_test_post([
            'post_title' => 'Post k domu',
            'post_content' => 'Obsah s 25 kg.',
            'post_excerpt' => 'Výňatek s 15 %.'
        ]);
        
        $title = get_the_title($post_id);
        $content = apply_filters('the_content', get_post_field('post_content', $post_id));
        $excerpt = apply_filters('the_excerpt', get_post_field('post_excerpt', $post_id));
        
        // All should be processed
        $this->assertStringContains('k&nbsp;domu', $title);
        $this->assertStringContains('25&nbsp;kg', $content);
        $this->assertStringContains('15&nbsp;%', $excerpt);
    }

    /**
     * Test performance with multiple posts
     */
    public function test_performance_multiple_posts(): void {
        $post_ids = [];
        
        // Create multiple posts
        for ($i = 1; $i <= 50; $i++) {
            $post_ids[] = TestHelper::create_test_post([
                'post_title' => "Post {$i} k domu s {$i} kg",
                'post_content' => "Obsah {$i} s Mgr. Novákem a {$i} kg."
            ]);
        }
        
        $start = microtime(true);
        
        // Process all posts
        foreach ($post_ids as $post_id) {
            $title = get_the_title($post_id);
            $content = apply_filters('the_content', get_post_field('post_content', $post_id));
        }
        
        $end = microtime(true);
        $processing_time = $end - $start;
        
        // Should process within reasonable time
        $this->assertLessThan(2.0, $processing_time, 'Processing multiple posts took too long');
    }

    /**
     * Test cache integration with WordPress
     */
    public function test_cache_integration(): void {
        $post_id = TestHelper::create_test_post([
            'post_title' => 'Cache test k domu'
        ]);
        
        // First call - should process and cache
        $start = microtime(true);
        $title1 = get_the_title($post_id);
        $time1 = microtime(true) - $start;
        
        // Second call - should use cache
        $start = microtime(true);
        $title2 = get_the_title($post_id);
        $time2 = microtime(true) - $start;
        
        // Results should be identical
        $this->assertEquals($title1, $title2);
        $this->assertStringContains('k&nbsp;domu', $title1);
        
        // Second call should be faster (cached)
        $this->assertLessThan($time1, $time2, 'Cached call should be faster');
    }

    /**
     * Test HTML content preservation in WordPress context
     */
    public function test_html_preservation_wordpress(): void {
        $post_id = TestHelper::create_test_post([
            'post_content' => '<p>Mgr. Novák <strong>má 25 kg</strong> a jde <em>k domu</em>.</p><div>např. takto</div>'
        ]);
        
        $content = apply_filters('the_content', get_post_field('post_content', $post_id));
        
        // Typography should be applied
        $this->assertStringContains('Mgr.&nbsp;Novák', $content);
        $this->assertStringContains('25&nbsp;kg', $content);
        $this->assertStringContains('k&nbsp;domu', $content);
        $this->assertStringContains('např.&nbsp;takto', $content);
        
        // HTML should be preserved
        $this->assertStringContains('<p>', $content);
        $this->assertStringContains('<strong>', $content);
        $this->assertStringContains('<em>', $content);
        $this->assertStringContains('<div>', $content);
    }
}
