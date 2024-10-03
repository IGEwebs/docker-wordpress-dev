<?php

/**
 * Template Name: Child Page
 *
 * @var $args may come from another template file which acts as a decorator for this one
 */
$args = $args ?? ['child' => true];

get_header();

while (have_posts()) {
    the_post();

    get_template_part('template-parts/page', $post->post_name, $args);
}

get_footer();
