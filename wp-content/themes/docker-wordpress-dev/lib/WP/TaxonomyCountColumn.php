<?php

namespace IdeasOnPurpose\WP;


/**
 * INitial documentation for this file is here:
 * @link https://gist.github.com/joemaller/94957e2ce14ec5252a8077942a5e90a2
 */
class TaxonomyCountColumn
{
    public function __construct()
    {
        add_action('edited_term_taxonomy', [$this, 'updateCounts'], 100, 2);
        add_action('pre_get_terms', [$this, 'orderByMeta']);
        add_action('wp_loaded', [$this, 'setupTaxonomyAdmin']);
    }

    /**
     * Update a term's 'post_type_counts' termmeta value
     *
     * The WP_Query and update_term_meta calls are wrapped in a 60-second transient to reduce the
     * load on the server and to guard against multiple calls.
     */
    public function updateCounts($termId, $taxName)
    {
        global $post_type;

        $post_types = (array) ($post_type ?? $_REQUEST['post_type'] ?? get_taxonomy($taxName)->object_type);
        $guid = sha1(json_encode([$termId, $taxName, $post_types]));

        if (get_transient($guid) === false) {
            foreach ($post_types as $type) {
                $args = [
                    'posts_per_page' => -1,
                    'post_type' => $type,
                    'tax_query' => [['taxonomy' => $taxName, 'terms' => $termId]],
                ];
                $countQuery = new \WP_Query($args);

                update_term_meta($termId, "count_{$type}", $countQuery->found_posts);
            }
            set_transient($guid, true, 60);
        }
    }

    /**
     * Adds a meta_query exposing the count_{$post_type} field to the Term Query so there are values to
     * order by. Since the query_var has the same name as the termmeta field, we can rely on WordPress
     * to sanitize the input.
     *
     * The query returns whether a number or null depending on whether the key exists.
     *
     *     ref: https://core.trac.wordpress.org/ticket/40335
     *     ref: https://stackoverflow.com/a/47224730/503463
     */
    public function orderByMeta(\WP_Term_Query $query)
    {
        $orderby = $query->query_vars['orderby'] ?? false;

        if (preg_match('/^count_[-a-z]+/', $orderby)) {
            $meta_query = new \WP_Meta_Query([
                $orderby => [
                    'relation' => 'OR',
                    ['key' => $orderby, 'type' => 'NUMERIC'],
                    ['key' => $orderby, 'compare' => 'NOT EXISTS'],
                ],
            ]);
            $query->meta_query = $meta_query;
        }
    }

    /**
     * Adds a sortable Count column and 'Refresh Counts' bulk action to the Taxonomy admin interface
     */
    public function setupTaxonomyAdmin()
    {
        $taxonomies = get_taxonomies(['public' => true], 'names');

        foreach ($taxonomies as $taxonomy) {
            add_filter("manage_edit-{$taxonomy}_columns", [$this, 'addCountColumn'], 100);
            add_filter("manage_edit-{$taxonomy}_sortable_columns", [$this, 'makeCountColumnSortable'], 100);
            add_action("manage_{$taxonomy}_custom_column", [$this, 'renderCountColumn'], 100, 3);

            add_filter("bulk_actions-edit-{$taxonomy}", [$this, 'addResetBulkAction']);
            add_filter("handle_bulk_actions-edit-{$taxonomy}", [$this, 'bulkActionHandler'], 100, 3);
        }
        add_action('admin_notices', [$this, 'addCountUpdateNotice']);
        add_action('admin_enqueue_scripts', [$this, 'adminCountColumnStyles'], 100);
    }

    public function addCountColumn($cols)
    {
        $newCols = $cols;
        unset($newCols['posts']);
        $newCols['post_type_count'] = 'Count';
        return $newCols;
    }

    public function makeCountColumnSortable($cols)
    {
        global $post_type;
        $newCols = $cols;
        $newCols['post_type_count'] = "count_$post_type";
        return $newCols;
    }

    public function renderCountColumn($content, $name, $id)
    {
        $output = $content;
        if ($name === 'post_type_count') {
            $screen = get_current_screen();

            $term = get_term($id);
            $taxonomy = get_taxonomy($term->taxonomy);
            $count = get_term_meta($id, "count_{$screen->post_type}", true);

            $viewHref = add_query_arg(
                [$taxonomy->query_var => $term->slug, 'post_type' => $screen->post_type],
                'edit.php',
            );

            $output .= strlen($count) ? sprintf('<a href="%s">%s</a>', $viewHref, $count) : '--';
        }
        return $output;
    }

    public function addResetBulkAction($actions)
    {
        $newActions = ['reset_post_type_counts' => 'Refresh Counts'];
        return array_merge($newActions, $actions);
    }

    public function bulkActionHandler($redirect, $action, $ids)
    {
        $screen = get_current_screen();

        if (strlen($screen->taxonomy)) {
            if (count($ids)) {
                wp_update_term_count_now($ids, $screen->taxonomy);
                $redirect = add_query_arg(['post_type_count_updated' => count($ids)], $redirect);
            }
        }
        return $redirect;
    }
    /**
     * Note: This method outputs an update message into the admin
     */
    public function addCountUpdateNotice()
    {
        if (!empty($_REQUEST['post_type_count_updated'])) {
            $screen = get_current_screen();
            $taxonomy = get_taxonomy($screen->taxonomy);
            $term = strtolower($taxonomy->labels->singular_name);
            $terms = strtolower($taxonomy->labels->name);
            $count = intval($_REQUEST['post_type_count_updated']);

            $msg = _n("Updated count for {$count} {$term}.", "Updated counts for {$count} {$terms}.", $count);
            printf('<div class="notice notice-success is-dismissible"><p>%s</p></div>', $msg);
        }
    }

    public function adminCountColumnStyles()
    {
        $css = "
        .column-post_type_count {
            width: 74px;
            text-align: center;
        }
        ";
        wp_add_inline_style('wp-admin', $css);
    }
}
