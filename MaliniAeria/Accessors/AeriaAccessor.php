<?php

namespace MaliniAeria\Accessors;

use Malini\Post;
use Malini\Interfaces\AccessorInterface;

class AeriaAccessor implements AccessorInterface
{
    protected static $aeria_posts_fields_cache = [];

    public static function getPostFields($wp_post)
    {
        $key = 'post_'.$wp_post->ID;
        if (!isset(static::$aeria_posts_fields_cache[$key])) {
            static::$aeria_posts_fields_cache[$key] = \get_aeria_fields($wp_post);
        }

        return static::$aeria_posts_fields_cache[$key];
    }

    public function retrieve(Post $post, ...$arguments)
    {
        $wp_post = $post->wp_post;

        $metabox = isset($arguments[0]) ? $arguments[0] : null;
        $metafield = isset($arguments[1]) ? $arguments[1] : null;
        $default = isset($arguments[2]) ? $arguments[2] : null;

        $post_aeria_fields = static::getPostFields($wp_post);

        if (empty($metabox)) {
            return static::getPostFields($wp_post);
        }

        if (empty($metafield)) {
            return isset($post_aeria_fields[$metabox])
                ? $post_aeria_fields[$metabox]
                : null;
        }

        $target = isset($post_aeria_fields[$metabox])
            ? $post_aeria_fields[$metabox]
            : [];

        return isset($target[$metafield])
            ? $target[$metafield]
            : $default;
    }
}
