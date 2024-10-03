<?php

namespace ideasonpurpose;

$paged = get_query_var('paged') ? intval(get_query_var('paged')) : 1;
$current = $paged - 1; // shift from page number to array index

$page_count = $wp_query->max_num_pages;

if ($page_count <= 1) {
    return;
}
/**
 * HTML Snippets
 */
$fillerSnippet = '<span class="page-numbers is-disabled">...</span>';
$edgeSnippet = '<a class="pagination__edge %s" href="%s"><span class="arrow">'. $SVG->arrowRight .'</span></a>';
$edgeSnippetDisabled = '<span class="pagination__edge is-disabled %s"><span class="arrow">'. $SVG->arrowLeft .'</span></span>';

$allLinks = paginate_links([
    'type' => 'array',
    'show_all' => true,
    'prev_next' => false,
    'prev_text' => 'Prev',
    'next_text' => 'Next'
]);

/**
 * $showLinks is mostly here for possible future re-use, this motif doesn't work with less than 7 visible items
 */
$showLinks = 7;

/**
 * $isEvenOffset shifts even-numbered center values towards the left
 */
$isEvenOffset = intval(!($showLinks & 1));
$displayMid = $showLinks / 2;

/**
 * Calculate how many slices to show in the center. If $current is close to the edges, include the edge (-2),
 * otherwise, omit both edges (-4)
 */
$sliceCount =
    $current > $displayMid && $current < $page_count - floor($displayMid) - 1 ? $showLinks - 4 : $showLinks - 2;

$sliceCount = count($allLinks) <= $showLinks ? count($allLinks) : $sliceCount;

/**
 * Calculate $start based on distance from middle and proximity to edges
 */
if ($current < $displayMid) {
    $start = 0;
} elseif ($current > $page_count - $displayMid - 1) {
    $start = $page_count - $sliceCount;
} else {
    $start = $current - floor($sliceCount / 2) + $isEvenOffset;
}

$centerLinks = array_slice($allLinks, $start, $sliceCount);

$first = [];
$last = [];

/**
 * Assemble first/last links including $fillerSnippet if count($allLinks) < $showLinks
 */
if (count($allLinks) > $showLinks) {
    if (count($centerLinks) < $showLinks - 2 || $current > $page_count / 2) {
        $first = array_slice($allLinks, 0, 1);
        $first[] = $fillerSnippet;
    }
    if (count($centerLinks) < $showLinks - 2 || $current < $page_count / 2) {
        $last = array_slice($allLinks, -1);
        array_unshift($last, $fillerSnippet);
    }
}
/**
 * Active/Inactive Previous/Next arrows, these are expected to be Arrays
 */
$prev =
    $current + 1 > 1
        ? sprintf($edgeSnippet, 'pagination__first', get_pagenum_link($current - 1 + 1))
        : sprintf($edgeSnippetDisabled, 'pagination__first');

$next =
    $current + 1 < $page_count
        ? sprintf($edgeSnippet, 'pagination__last', get_pagenum_link($current + 1 + 1), '')
        : sprintf($edgeSnippetDisabled, 'pagination__last');

$prev = [$prev];
$next = [$next];

/**
 * Finally, merge all the pieces into a single array to be imploded into the HTML
 */
$display = array_merge($prev, $first, $centerLinks, $last, $next);
?>

<!-- START template-parts/components/pagination.php -->

<nav class="pagination">
    <?= implode("\n", $display) ?>
</nav>

<!-- END template-parts/components/pagination.php -->
