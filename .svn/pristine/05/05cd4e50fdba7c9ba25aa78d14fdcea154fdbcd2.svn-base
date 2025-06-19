<?php

namespace BitSpecter\PevneMezery;

class CacheHandler
{
    const CACHE_DURATION = 12 * HOUR_IN_SECONDS;

    /**
     * Generates a unique cache key based on the content.
     *
     * @param string $content The content to generate the cache key for.
     * @return string The unique cache key.
     */
    private static function generate_cache_key(string $content): string
    {
        return 'fs_fixed_spaces_' . md5($content);
    }

    /**
     * Gets the cached content if it exists and is still valid.
     *
     * @param string $content The original content.
     * @return string|null Cached content if it exists, otherwise null.
     */
    public static function get_cached_content(string $content): ?string
    {
        $cache_key = self::generate_cache_key($content);
        $cached_content = get_transient($cache_key);

        if ($cached_content !== false) {
            return $cached_content;
        }

        return null;
    }

    /**
     * Caches the processed content for a specific duration.
     *
     * @param string $content The original content.
     * @param string $processed_content The processed content to be cached.
     * @return void
     */
    public static function save_cached_content(string $content, string $processed_content): void
    {
        $cache_key = self::generate_cache_key($content);
        set_transient($cache_key, $processed_content, self::CACHE_DURATION);
    }

    /**
     * Deletes the cache for specific content.
     *
     * @param string $content The original content to remove from cache.
     * @return void
     */
    public static function delete_cached_content(string $content): void
    {
        $cache_key = self::generate_cache_key($content);
        delete_transient($cache_key);
    }

    /**
     * Invalidates the cache when a post is saved or updated.
     *
     * @param int $post_id The ID of the post being saved.
     * @return void
     */
    public static function invalidate_cache_on_post_save(int $post_id): void
    {
        $content = get_post_field('post_content', $post_id);
        if ($content) {
            self::delete_cached_content($content);
        }
    }
}
