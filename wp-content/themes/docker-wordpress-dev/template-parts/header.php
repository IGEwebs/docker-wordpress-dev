<?php

namespace ideasonpurpose;

$menu_locations = get_nav_menu_locations();
$menus = [];
$menu_keys = ['menu-main']; // Any menus appearing in the header should be added here
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
?>

<!-- START template-parts/header.php -->

<header class="header">
  <nav class="header__container wrapper">
    <div class="header__bar">
      <a class="header__logo" href="<?= bloginfo('url') ?>">
        <?= $SVG->siteLogo ?>
      </a>

      <button type="button" class="header__menu-button js-toggle-menu">
        <span class="header__menu-button-lines"></span>
        <span class="a11y">Toggle main menu</span>
      </button>
    </div>

    <div class="header__menu">
      <?php get_template_part('template-parts/components/search-form', 'header'); ?>

      <?php if (array_key_exists('menu-main', $menus)): ?>
      <?= $menus['menu-main'] ?>
      <?php endif; ?>
    </div>
  </nav>
</header>

<!-- END template-parts/header.php -->
