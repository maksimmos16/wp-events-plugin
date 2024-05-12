<?php
/*
Plugin Name: Event plugin
Description: Event plugin
Version: 1.0
Author: 
*/

function enqueue_event_plugin_scripts()
{
    wp_enqueue_script('jquery-ui-datepicker');
    wp_enqueue_script('event-plugin', plugin_dir_url(__FILE__) . 'event-plugin.js', array('jquery', 'jquery-ui-datepicker'), null, true);
    wp_enqueue_style('jquery-ui-datepicker-style', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css');
}
add_action('admin_enqueue_scripts', 'enqueue_event_plugin_scripts');

function enqueue_event_plugin_styles_frontend()
{
    wp_enqueue_style('event-css', plugin_dir_url(__FILE__) . 'event-plugin.css');
}
add_action('wp_enqueue_scripts', 'enqueue_event_plugin_styles_frontend');

// Register custom post type "Event"
function custom_post_type_event()
{
    $labels = array(
        'name'               => 'Events',
        'singular_name'      => 'Event',
        'menu_name'          => 'Events',
        'name_admin_bar'     => 'Event',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New Event',
        'new_item'           => 'New Event',
        'edit_item'          => 'Edit Event',
        'view_item'          => 'View Event',
        'all_items'          => 'All Events',
        'search_items'       => 'Search Events',
        'parent_item_colon'  => 'Parent Events:',
        'not_found'          => 'No events found.',
        'not_found_in_trash' => 'No events found in trash.'
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'event'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 20,
        'supports'           => array('title', 'editor', 'thumbnail', 'custom-fields'),
        'taxonomies'         => array('event_category', 'event_tag') 
    );

    register_post_type('event', $args);
}
add_action('init', 'custom_post_type_event');

// Register taxonomy "Event Category"
function register_event_category_taxonomy()
{
    $labels = array(
        'name'                       => __( 'Event Categories', 'text-domain' ),
        'singular_name'              => __( 'Event Category', 'text-domain' ),
        'search_items'               => __( 'Search Event Categories', 'text-domain' ),
        'all_items'                  => __( 'All Event Categories', 'text-domain' ),
        'edit_item'                  => __( 'Edit Event Category', 'text-domain' ),
        'update_item'                => __( 'Update Event Category', 'text-domain' ),
        'add_new_item'               => __( 'Add New Event Category', 'text-domain' ),
        'new_item_name'              => __( 'New Event Category', 'text-domain' ),
        'menu_name'                  => __( 'Event Category', 'text-domain' ),
        'view_item'                  => __( 'View Event Category', 'text-domain' ),
        'popular_items'              => __( 'Popular Event Categories', 'text-domain' ),
        'separate_items_with_commas' => __( 'Separate event categories with commas', 'text-domain' ),
        'add_or_remove_items'        => __( 'Add or remove event categories', 'text-domain' ),
        'choose_from_most_used'      => __( 'Choose from the most used event categories', 'text-domain' ),
        'not_found'                  => __( 'Event categories not found', 'text-domain' ),
    );

    $args = array(
        'labels'            => $labels,
        'public'            => true,
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'event-category'),
    );

    register_taxonomy('event_category', 'event', $args);
}
add_action('init', 'register_event_category_taxonomy');

// Register taxonomy "Event Tag"
function register_event_tag_taxonomy()
{
    $labels = array(
        'name'                       => __( 'Event Tags', 'text-domain' ),
        'singular_name'              => __( 'Event Tag', 'text-domain' ),
        'search_items'               => __( 'Search Event Tags', 'text-domain' ),
        'all_items'                  => __( 'All Event Tags', 'text-domain' ),
        'edit_item'                  => __( 'Edit Event Tag', 'text-domain' ),
        'update_item'                => __( 'Update Event Tag', 'text-domain' ),
        'add_new_item'               => __( 'Add New Event Tag', 'text-domain' ),
        'new_item_name'              => __( 'New Event Tag', 'text-domain' ),
        'menu_name'                  => __( 'Event Tag', 'text-domain' ),
        'view_item'                  => __( 'View Event Tag', 'text-domain' ),
        'popular_items'              => __( 'Popular Event Tags', 'text-domain' ),
        'separate_items_with_commas' => __( 'Separate event tags with commas', 'text-domain' ),
        'add_or_remove_items'        => __( 'Add or remove event tags', 'text-domain' ),
        'choose_from_most_used'      => __( 'Choose from the most used event tags', 'text-domain' ),
        'not_found'                  => __( 'Event tags not found', 'text-domain' ),
    );

    $args = array(
        'labels'            => $labels,
        'public'            => true,
        'hierarchical'      => false,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'event-tag'),
    );

    register_taxonomy('event_tag', 'event', $args);
}
add_action('init', 'register_event_tag_taxonomy');

// Adding custom fields for events
function event_custom_fields()
{
    add_meta_box('event_meta_box', 'Event Details', 'display_event_meta_box', 'event', 'normal', 'high');
}
add_action('add_meta_boxes', 'event_custom_fields');

function display_event_meta_box($event)
{
    wp_nonce_field('event_meta_nonce', 'event_meta_nonce_field');

?>
    <table>
        <tr>
            <td style="width: 100%"><?php _e('Short Description:', 'text-domain'); ?></td>
            <td><textarea rows="5" name="event_short_description"><?php echo esc_textarea(get_post_meta($event->ID, 'event_short_description', true)); ?></textarea></td>
        </tr>
        <tr>
            <td style="width: 100%"><?php _e('Date:', 'text-domain'); ?></td>
            <td><input type="text" class="datepicker" name="event_date" value="<?php echo esc_attr(get_post_meta($event->ID, 'event_date', true)); ?>" /></td>
        </tr>
        <tr>
            <td style="width: 100%"><?php _e('Time:', 'text-domain'); ?></td>
            <td><input type="time" name="event_time" value="<?php echo esc_attr(get_post_meta($event->ID, 'event_time', true)); ?>" /></td>
        </tr>
        <tr>
            <td style="width: 100%"><?php _e('Location:', 'text-domain'); ?></td>
            <td><input type="text" name="event_location" value="<?php echo esc_attr(get_post_meta($event->ID, 'event_location', true)); ?>" /></td>
        </tr>
        <tr>
            <td style="width: 100%"><?php _e('Ticket Link:', 'text-domain'); ?></td>
            <td><input type="text" name="event_tickets_link" value="<?php echo esc_attr(get_post_meta($event->ID, 'event_tickets_link', true)); ?>" /></td>
        </tr>
    </table>
<?php
}

function save_event_meta_box_data($event_id)
{
    if (!isset($_POST['event_meta_nonce_field']) || !wp_verify_nonce($_POST['event_meta_nonce_field'], 'event_meta_nonce')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (isset($_POST['post_type']) && 'event' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $event_id)) {
            return;
        }
    } else {
        if (!current_user_can('edit_post', $event_id)) {
            return;
        }
    }

    if (isset($_POST['event_short_description'])) {
        update_post_meta($event_id, 'event_short_description', sanitize_text_field($_POST['event_short_description']));
    }
    if (isset($_POST['event_date'])) {
        update_post_meta($event_id, 'event_date', sanitize_text_field($_POST['event_date']));
    }
    if (isset($_POST['event_time'])) {
        update_post_meta($event_id, 'event_time', sanitize_text_field($_POST['event_time']));
    }
    if (isset($_POST['event_location'])) {
        update_post_meta($event_id, 'event_location', sanitize_text_field($_POST['event_location']));
    }
    if (isset($_POST['event_tickets_link'])) {
        update_post_meta($event_id, 'event_tickets_link', sanitize_text_field($_POST['event_tickets_link']));
    }
}
add_action('save_post', 'save_event_meta_box_data');

// Shortcode to display information about a specific event by ID
function single_event_shortcode($atts)
{
    // Shortcode attribute processing
    $atts = shortcode_atts(array(
        'id' => '', // Event ID
    ), $atts);

    // Get event information
    $event_id = absint($atts['id']);
    $event = get_post($event_id);

    // Check if an event with the specified ID exists
    if (!$event) {
        return '<p>' . __('Event with the specified ID not found', 'text-domain') . '</p>';
    }

    $event_description = $event->post_content;
    $event_time = get_post_meta($event_id, 'event_time', true);
    $event_date = get_post_meta($event_id, 'event_date', true);
    $event_location = get_post_meta($event_id, 'event_location', true);
    $event_ticket_link = get_post_meta($event_id, 'event_tickets_link', true);
    $event_featured_image_url = get_the_post_thumbnail_url($event_id, 'large');

    // Form output
    $output = '<div class="single-event">';
    if ($event_featured_image_url) {
        $output .= '<div class="event-featured-image"><img src="' . esc_url($event_featured_image_url) . '" alt="' . get_the_title($event_id) . '"></div>';
    }
    $output .= '<h2>' . get_the_title($event_id) . '</h2>';
    $output .= '<p><strong>' . __('Description:', 'text-domain') . '</strong> ' . $event_description . '</p>';
    if ($event_time) {
        $output .= '<p><strong>' . __('Time:', 'text-domain') . '</strong> ' . $event_time . '</p>';
    }
    if ($event_date) {
        $output .= '<p><strong>' . __('Date:', 'text-domain') . '</strong> ' . $event_date . '</p>';
    }
    if ($event_location) {
        $output .= '<p><strong>' . __('Location:', 'text-domain') . '</strong> ' . $event_location . '</p>';
    }
    if ($event_ticket_link) {
        $output .= '<p><strong>' . __('Ticket Link:', 'text-domain') . '</strong> <a href="' . esc_url($event_ticket_link) . '">' . __('Buy Tickets', 'text-domain') . '</a></p>';
    }
    $output .= '</div>';

    return $output;
}
add_shortcode('single_event', 'single_event_shortcode');

// Shortcode to display a list of events
function events_list_shortcode($atts) {
    // Parse shortcode attributes
    $atts = shortcode_atts(array(
        'count'      => 5, 
        'categories' => '',
        'tags'       => '',
    ), $atts);

    // Query arguments
    $args = array(
        'post_type'      => 'event',
        'posts_per_page' => $atts['count'],
    );

    // Add event categories if specified
    if (!empty($atts['categories'])) {
        $args['tax_query'][] = array(
            'taxonomy' => 'event_category',
            'field'    => 'slug',
            'terms'    => $atts['categories'],
        );
    }

    // Add event tags if specified
    if (!empty($atts['tags'])) {
        $args['tax_query'][] = array(
            'taxonomy' => 'event_tag',
            'field'    => 'slug',
            'terms'    => $atts['tags'],
        );
    }

    // Posts query
    $events_query = new WP_Query($args);

    // Output
    $output = '<ul class="events-list">';
    if ( $events_query->have_posts() ) {
        while ( $events_query->have_posts() ) {
            $events_query->the_post();
            $event_id = get_the_ID();
            $event_description = get_the_excerpt();
            $event_time = get_post_meta( $event_id, 'event_time', true );
            $event_date = get_post_meta( $event_id, 'event_date', true );
            $event_location = get_post_meta( $event_id, 'event_location', true );
            $event_featured_image_url = get_the_post_thumbnail_url($event_id, 'large');

            $output .= '<li class="event-list-item">';
            if ($event_featured_image_url) {
                $output .= '<div class="event-featured-image"><img src="' . esc_url($event_featured_image_url) . '" alt="' . get_the_title($event_id) . '"></div>';
            }
            $output .= '<div class="event-list-item-descriptions">';
            $output .= '<h3><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3>';
            $output .= '<p><strong>' . __('Description:', 'text-domain') . '</strong> ' . $event_description . '</p>';
            if ($event_date) {
                $output .= '<p><strong>' . __('Date:', 'text-domain') . '</strong> ' . $event_date . '</p>';
            }
            if ($event_time) {
                $output .= '<p><strong>' . __('Time:', 'text-domain') . '</strong> ' . $event_time . '</p>';
            }
            if ($event_location) {
                $output .= '<p><strong>' . __('Location:', 'text-domain') . '</strong> ' . $event_location . '</p>';
            }
            $output .= '</div>';
            $output .= '</li>';
        }
        wp_reset_postdata();
    } else {
        $output .= '<li>' . __('Events not found', 'text-domain') . '</li>';
    }
    $output .= '</ul>';

    return $output;
}
add_shortcode( 'events_list', 'events_list_shortcode' );

// Shortcode for events grid
function events_grid_shortcode( $atts ) {
    // Parsing shortcode attributes
    $atts = shortcode_atts( array(
        'count'      => 6, // Default to display 6 events
        'categories' => '',
        'tags'       => '',
    ), $atts );

    // Query arguments
    $args = array(
        'post_type'      => 'event',
        'posts_per_page' => $atts['count'],
    );

    // Add event categories if specified
    if ( ! empty( $atts['categories'] ) ) {
        $args['tax_query'][] = array(
            'taxonomy' => 'event_category',
            'field'    => 'slug',
            'terms'    => $atts['categories'],
        );
    }

    // Add event tags if specified
    if ( ! empty( $atts['tags'] ) ) {
        $args['tax_query'][] = array(
            'taxonomy' => 'event_tag',
            'field'    => 'slug',
            'terms'    => $atts['tags'],
        );
    }

    // Post query
    $events_query = new WP_Query( $args );

    // Output
    $output = '<div class="events-grid">';
    if ( $events_query->have_posts() ) {
        $output .= '<div class="custom-row">';
        $i = 0;
        while ( $events_query->have_posts() ) {
            $events_query->the_post();
            $event_id = get_the_ID();
            $event_description = get_the_excerpt(); 
            $event_time = get_post_meta( $event_id, 'event_time', true );
            $event_date = get_post_meta( $event_id, 'event_date', true );
            $event_location = get_post_meta( $event_id, 'event_location', true );
            $output .= '<div class="event-item">';
            $output .= '<div class="event">';
            $output .= '<h3><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3>';
            $output .= '<p><strong>' . __( 'Description:', 'text-domain' ) . '</strong> ' . $event_description . '</p>';
            if ($event_date) {
                $output .= '<p><strong>' . __( 'Event Date:', 'text-domain' ) . '</strong> ' . $event_date . '</p>';
            }
            if ($event_time) {
                $output .= '<p><strong>' . __( 'Event Time:', 'text-domain' ) . '</strong> ' . $event_time . '</p>';
            }
            if ($event_location) {
                $output .= '<p><strong>' . __( 'Event Location:', 'text-domain' ) . '</strong> ' . $event_location . '</p>';
            }
            $output .= '</div>';
            $output .= '</div>'; 
            // If this is the second item in the row, close the current row and start a new one
            $i++;
            if ( $i % 2 == 0 ) {
                $output .= '</div>'; 
                $output .= '<div class="custom-row">';
            }
        }
        $output .= '</div>'; 
        wp_reset_postdata();
    } else {
        $output .= '<p>' . __( 'Events not found', 'text-domain' ) . '</p>';
    }
    $output .= '</div>'; 

    return $output;
}
add_shortcode( 'events_grid', 'events_grid_shortcode' );

function custom_event_template($template) {
    if (is_singular('event')) {
        $template = plugin_dir_path(__FILE__) . 'single-event-template.php';
    }
    return $template;
}
add_filter('template_include', 'custom_event_template');


