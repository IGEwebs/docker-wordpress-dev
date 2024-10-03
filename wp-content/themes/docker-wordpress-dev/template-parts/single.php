<!-- START template-parts/single.php -->

<main>
  <article class="wrapper article">

    <header>
      <?= the_category(); ?>
      <h1><?= get_the_title() ?></h1>

      <?php get_template_part('template-parts/components/sharebar'); ?>
    </header>

    <section class="editorial">
      <?php the_content(); ?>
    </section>

  </article>
</main>

<!-- END template-parts/single.php -->
