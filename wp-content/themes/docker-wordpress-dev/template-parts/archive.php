<!-- START template-parts/blog.php -->

<main>
  <div class="wrapper">
    <h1><?= get_the_title(get_option('page_for_posts', true)); ?></h1>
  </div>

  <div class="wrapper">
    <div class="row">
      <?php while (have_posts()) {
          the_post();
          get_template_part('template-parts/components/card');
      } ?>
    </div>

    <?php get_template_part('template-parts/components/pagination'); ?>
  </div>
</main>

<!-- END template-parts/blog.php -->
