<?php

namespace MaliniAeria\Decorators;

use Malini\Post;
use Malini\Abstracts\PostDecorator;
use Malini\Interfaces\PostDecoratorInterface;
use MaliniAeria\Accessors\AeriaAccessor;

class WithAeria extends PostDecorator implements PostDecoratorInterface
{

    public function decorate(Post $post, array $options = []) : Post {
        $wp_post = $post->wp_post;

        $aeria_automerge_fields = $this->getConfig('automerge_fields', $options, false);

        if ($aeria_automerge_fields) {
            $fields_keys = array_keys(
                AeriaAccessor::getPostFields($wp_post)
            );
            foreach ($fields_keys as $key) {
                $post->addAttribute(
                    $key,
                    '@aeria:' . $key
                );
            }
            $this->filterConfig(
                $post,
                $options,
                $fields_keys
            );
        } else {
            $post->addRawAttributes([
                'aeria_fields'   => '@aeria'
            ]);
            $this->filterConfig(
                $post,
                $options,
                [ 'aeria_fields' ]
            );
        }

        return $post;
    }

}
