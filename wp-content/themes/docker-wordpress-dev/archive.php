<?php

/**
 * This is the default template of
 * category, tag, date, author,
 * CPT and custom taxonomy pages.
 */

get_header();

get_template_part('template-parts/archive', $post->post_type);

get_footer();
