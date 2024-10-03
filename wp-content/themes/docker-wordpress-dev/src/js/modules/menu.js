import $ from "jquery";

const $html = $('html');
const $body = $('body');
const $window = $(window);
const menuOpenClass = 'menu-open';
const menuTriggerClass = '.js-toggle-menu';


/**
 * Toggle Menu
 *
 * Toggles the menuOpenClass on <body>
 *
 * Prevents <body> from being scrolled
 * while the hamburger menu is open
 */

function toggleMenu() {
  var top = $window.scrollTop();

  // Pin the body and prevent scrolling while the menu is open
  if (!$body.is('.' + menuOpenClass)) {
    $body.css('top', -1 * top + 'px').attr('data-scroll', top);
  }

  $body.toggleClass(menuOpenClass);

  // Scroll the body back to its initial position
  if (!$body.is('.' + menuOpenClass)) {
    $('body,html').scrollTop($('body').attr('data-scroll'));
  }
}

/**
 * Hamburger button class
 */

$(menuTriggerClass).on('click', toggleMenu);


/**
 * ESC key toggles the menu
 */

$html.on('keyup', function (e) {
  e.keyCode === 27 && toggleMenu();
});
