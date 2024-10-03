<?php

$default_cookie_text = <<<EOF
<p>
  This site uses cookies for analytics. By continuing to browse this site, you agree
  to this use. Read our <a href="/?page_id=3">Privacy Policy</a> to learn more.
</p>
EOF;
$default_button_text = 'I Agree';

$cookie_button = get_field('cookie_accept_button', 'options') ?: $default_button_text;
$cookie_text = get_field('cookie_notice', 'options') ?: $default_cookie_text;
?>

<!-- START template-parts/components/cookie-notice.php -->

<div class="cookie-notice">
  <div class="wrapper">
    <div class="row">
      <div class="col-12 md:col-8">
        <?= $cookie_text ?>
      </div>
      <div class="col-12 md:col-4">
          <button class="wp-block-button__link js--cookie-notice-close"><?= $cookie_button ?></button>
      </div>
    </div>
  </div>
</div>

<!-- END template-parts/components/cookie-notice.php -->
