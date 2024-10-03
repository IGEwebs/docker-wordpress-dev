<?php

$portrait_src = wp_get_attachment_image_src(get_post_thumbnail_id($post), 'large');
$portrait_src = is_array($portrait_src) ? $portrait_src[0] : false;

/**
 * Note: `get_the_terms()` returns an array or `false` if there are no terms.
 * This shorthand ternary returns an empty array instead of false.
 * the `?: []` at the end is easy to miss, but saves a silly false-check conditional.
 */
$categories = get_the_terms($post, 'category') ?: [];

$terms = array_map(function ($term) {
    return "<span>{$term->name}</span>";
}, $categories);

?>

<!-- START template-parts/components/card.php -->

<div class="col-12 col-md-6 card">

  <a href="<?= get_the_permalink() ?>">
    <?php if ($portrait_src): ?>
    <div class="card__image" style="background-image: url('<?= $portrait_src ?>');"></div>
    <?php endif; ?>

    <div class="card__content">
      <?php if (count($terms)): ?>
        <div class="post-categories">
          <?= implode("\n", $terms) ?>
        </div>
      <?php endif; ?>

      <h3 class="card__title"><?= get_the_title() ?></h3>
    </div>
  </a>

</div>

<!-- END template-parts/components/card.php -->
