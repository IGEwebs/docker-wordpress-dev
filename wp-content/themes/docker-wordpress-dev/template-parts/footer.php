<?php

namespace ideasonpurpose;

$menu_locations = get_nav_menu_locations();
$menus = [];
$menu_keys = ['menu-footer'];
foreach ($menu_keys as $menu_key) {
    /**
     * menu locations with no menu assigned return the integer 0
     * assigned locations return an integer menu id.
     * wp_nav_menu(0) returns null
     */
    if (array_key_exists($menu_key, $menu_locations) && $menu_locations[$menu_key] !== 0) {
        $menu_id = intval($menu_locations[$menu_key]);
        $menus[$menu_key] = wp_nav_menu([
            'menu' => $menu_id,
            'menu_class' => "{$menu_key}__menu",
            'items_wrap' => '<ul class="wp-menu %2$s">%3$s</ul>' . "\n",
            'container' => '',
            'echo' => false,
        ]);
    }
}

$copyright = get_field('copyright', 'options');
if (is_null($copyright) || empty($copyright)) {
    $copyright =
        '%YEAR% ' . get_bloginfo('name') . ' | ' . get_bloginfo('tagline');')';
}
$copyright = str_replace('%YEAR%', date('Y'), $copyright);


?>

<!-- START template-parts/footer.php -->

<footer class="footer">
  <div class="wrapper">
    <div class="row">
      <div class="col-12">
        <h2>Footer</h2>

        <nav>
        <?php if (array_key_exists('menu-footer', $menus)): ?>
            <?= $menus['menu-footer'] ?>
        <?php endif; ?>
        </nav>
      </div>
    </div>
  </div>

  <div class="footer__legal">
    <div class="wrapper">
      <p class="footer__copy">
        © <?= $copyright ?>
      </p>
    </div>
  </div>
</footer>

<?php get_template_part('template-parts/components/cookie-notice.php'); ?>


<p class="a11y" id="extdisclaimer" aria-hidden="true">link to an external site that may or may not meet accessibility guidelines.</p>

<!-- END template-parts/footer.php -->
