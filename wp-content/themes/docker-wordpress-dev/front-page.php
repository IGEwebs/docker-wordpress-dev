<?php

get_header();

while (have_posts()):
    the_post(); ?>

<!-- START front-page.php -->

<main>
  <section class="wrapper editorial">
    <?php the_content(); ?>
  </section>
</main>

<!-- END front-page.php -->

<?php
endwhile;
get_footer();
