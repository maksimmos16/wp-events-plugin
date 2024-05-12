<?php
get_header(); 
while (have_posts()) :
    the_post();
    ?>
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <div class="event-single-content">
            
            <?php 
            if (has_post_thumbnail()) {
                echo '<div class="event-featured-image">';
                the_post_thumbnail('full');
                echo '</div>';
            } ;?>

            <h1><?php the_title(); ?></h1>
            <?php
            the_content();

           // Get custom field values for the current post (event)
            $event_date = get_post_meta(get_the_ID(), 'event_date', true);
            $event_time = get_post_meta(get_the_ID(), 'event_time', true);
            $event_location = get_post_meta(get_the_ID(), 'event_location', true);
            $event_tickets_link = get_post_meta(get_the_ID(), 'event_tickets_link', true);

            // Output custom field values
            if ($event_date) {
                echo '<p><strong>Date of event:</strong> ' . esc_html($event_date) . '</p>';
            }
            if ($event_time) {
                echo '<p><strong>Time of event:</strong> ' . esc_html($event_time) . '</p>';
            }
            if ($event_location) {
                echo '<p><strong>Location of event:</strong> ' . esc_html($event_location) . '</p>';
            }
            if ($event_tickets_link) {
                echo '<p><strong>Tickets link:</strong> <a href="' . esc_url($event_tickets_link) . '">Buy tickets</a></p>';
            }

            ?>
        </div>
    </article>
    <?php
endwhile;

get_footer(); 
