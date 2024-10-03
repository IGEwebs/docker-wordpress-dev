<!-- START template-parts/components/sharebar.php -->

<div class="sharebar">
  <ul class="sharebar__list">
    <li>
      <a class="js--share" href="https://www.facebook.com/sharer/sharer.php?u=<?= get_permalink(); ?>">
        <span class="a11y">Share on Facebook</span>
        <?= $SVG->facebook ?>
      </a>
    </li>
    <li>
      <a class="js--share" href="https://www.linkedin.com/shareArticle?mini=true&url=<?= get_permalink(); ?>&title=<?= get_the_title(); ?>">
        <span class="a11y">Share on LinkedIn</span>
        <?= $SVG->linkedin ?>
      </a>
    </li>
    <li>
      <a href="https://twitter.com/intent/tweet?url=<?= get_permalink(); ?>" class="js--share">
        <span class="a11y">Share on Twitter</span>
        <?= $SVG->twitter ?>
      </a>
    </li>
    <li>
      <a href="mailto:?subject=<?= get_the_title(); ?>&body=<?= get_permalink(); ?>">
        <span class="a11y">Share on Twitter</span>
        <?= $SVG->email ?>
      </a>
    </li>
  </ul>
</div>

<!-- END template-parts/components/sharebar.php -->
