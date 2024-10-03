<?php
/**
 * `singular.php` is the last template called for single posts, pages and
 * attachments before falling back to index.php. It will be overridden by
 * `page.php` for pages and `single.php` for posts and attachments.
 *
 */

get_header();

while (have_posts()) {
    the_post();

    get_template_part('template-parts/single', $post->post_type);
}

get_footer();
