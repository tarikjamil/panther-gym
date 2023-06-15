<?php

/**
 * View: Events Bar
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events/v2/components/events-bar.php
 *
 * See more documentation about our views templating system.
 *
 * @link http://evnt.is/1aiy
 *
 * @version 5.2.0
 *
 * @var bool $display_events_bar   Boolean on whether to display the events bar.
 * @var bool $disable_event_search Boolean on whether to disable the event search.
 */

if (empty($display_events_bar)) {
    return;
}

$heading = $disable_event_search
    ? __('Views Navigation', 'the-events-calendar')
    : sprintf(__('%s Search and Views Navigation', 'the-events-calendar'), tribe_get_event_label_plural());

$classes = [];
if (empty($disable_event_search)) {
    $classes[] = ;
}
?>

<h2 class="tribe-common-a11y-visual-hide">
    <?php echo esc_html($heading); ?>
</h2>

<div class="calendar-filters" <?php tribe_classes($classes); ?> data-js="tribe-events-events-bar">

    <div class="calendar-filter-box">
        <div>SEARCH</div>
        <div class="calendar-input">
            <?php if ( empty( $disable_event_search ) ) : ?>
                <?php $this->template( 'components/events-bar/search-button' ); ?>

                <div
                    class=""
                    id="tribe-events-search-container"
                    data-js="tribe-events-search-container"
                >
                    <?php $this->template( 'components/events-bar/search' ); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="calendar-filter-box">
        <div>VIEW</div>
        <div class="calendar-view-options">
            <?php $this->template('components/events-bar/views'); ?>
        </div>
    </div>

    <div class="calendar-filter-box is--categories">
        <div>Filter by Category</div>
        <div class="calendar-view-options">
            <?php

            // get the terms
            $terms = get_terms( array(
                'taxonomy'   => 'tribe_events_cat',
                'post_type' => 'tribe_events',
                'hide_empty' => true,
            ) );

            // go through each term and create link
            foreach ($terms as $term):
                ?>
                <div class="calendar-view-option">
                    <div>
                        <a href="<?php echo get_term_link( $term->term_id, 'tribe_events_cat' ); ?>">
                            <?php echo $term->name; ?>
                        </a>
                    </div>
                </div>
                <?php
            endforeach;

            ?>
        </div>
    </div>
</div>
