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
        $aeria_automerge_level = $this->getConfig('automerge_level', $options, 1);

        // accept only 1 or 2
        $aeria_automerge_level = max(1, min(2, $aeria_automerge_level));

        if ($aeria_automerge_fields) {
            $post_fields = AeriaAccessor::getPostFields($wp_post);
            $fields_keys = array_keys(
                $post_fields
            );

            foreach ($fields_keys as $key) {
                if ($aeria_automerge_level == 2) {
                    foreach ($post_fields[$key] as $sub_key => $_element) {
                        $post->addAttribute(
                            $sub_key,
                            '@aeria:' . $key . ',' . $sub_key
                        );
                    }
                } else {
                    $post->addAttribute(
                        $key,
                        '@aeria:' . $key
                    );
                }
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
