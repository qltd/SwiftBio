<?php
/**
* The front page template file
*
* @package _q
*/

get_header(); ?>
<?php $masthead_img = get_field('masthead_image'); ?>
<div class="masthead" style="background-image: url(<?php echo $masthead_img['url']; ?>);">
    <div class="row">
      <a href="/" title="home" class="logo"><img src="<?php bloginfo('template_directory'); ?>/img/swift-logo.svg" alt="Swift Biosciences" /></a>
        <div class="col left">


          <div class="text">
              <h1><?php the_field('masthead_title'); ?></h1>
              <p><?php the_field('masthead_text'); ?></p>
          </div>
        </div>
        <div class="col right">
          <ul class="product-list">
              <?php wp_nav_menu(array('container' => '', 'items_wrap' => '%3$s', 'theme_location' => 'masthead', 'menu_id' => 'product-menu'));?>
          </ul>
        </div>
    </div>
</div>

<div class="applications-section">
    <div class="row">
        <h2>See How Swift Technology Benefits Your Application</h2>
        <?php
            $applications = get_children(array(
                'post_parent'       => 32,
                'numberposts'    => -1,
                'post_status' => 'publish',
                'orderby' => 'menu_order',
                'order' => 'ASC'
            ));
        ?>
        <select id="applications" class="applications">
            <option></option>
            <?php foreach ($applications as $a): ?>
                <option value="<?php echo get_permalink($a->ID); ?>"><?php echo $a->post_title; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
</div>

<div class="callouts">
    <div class="row">

        <?php while(have_rows('callouts')): the_row(); ?>
            <div class="callout">
                <h3><?php the_sub_field('text'); ?></h3>
                <a href="<?php echo get_sub_field('page'); ?>" class="button"><?php the_sub_field('button_text'); ?></a>
            </div>
        <?php endwhile; ?>

    </div>
    </div> <!-- .application-section -->


    <div class="testimonial">
        <div class="row">
            <h4>What Swift Clients Say</h4>
            <?php
                $rows = get_field('testimonials'); // get all the rows
                $rand_row = $rows[ array_rand( $rows ) ]; // get a random row
                $rand_row_quote = $rand_row['quote' ]; // get the sub field value
            ?>
            <p><em><?php echo $rand_row_quote; ?></em></p>
        </div>
        </div> <!-- .testimonial -->

        <div class="features">
            <div class="row">
                <div class="feature product">
                    <?php
                        $post = get_field('featured_product');
                        setup_postdata( $post );
                        $img_url = $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'large' );
                    ?>
                    <a href="<?php echo get_permalink(); ?>"><img src="<?php echo $img_url[0]; ?>" /></a>
                    <h2><a href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a></h2>
                    <p><strong><?php the_field('featured_product_sub_title'); ?></strong></p>
                    <p><?php echo wp_trim_words(get_field('featured_product_text', 80), 53); ?>  <a href="<?php echo get_permalink(); ?>">more &raquo;</a></p>
                    <?php wp_reset_postdata(); ?>

                    <a href="<?php echo get_field('similar_products_link'); ?>" class="button">Similar Products</a>
                </div>

                <div class="feature news">
                    <?php
                    $args = array(
                            'numberposts' => 1,
                            'orderby' => 'post_date',
                            'order' => 'DESC',
                            'post_type' => 'post',
                            'post_status' => 'publish',
                            'suppress_filters' => true
                        );

                        $recent_post = new WP_Query( $args );
                    ?>
                    <?php if (have_posts()) : while (have_posts()) : the_post(); $recent_post->the_post(); ?>
                    <h2><a href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a></h2>
                    <p><strong>Posted <?php the_date('F j, Y'); ?></strong></p>
                    <p><?php echo(get_the_excerpt()); ?> <a href="<?php echo get_permalink(); ?>">more &raquo;</a></p>

                    <a href="<?php echo get_permalink(105); ?>" class="button">All News</a>
                    <?php endwhile; endif; wp_reset_postdata(); ?>
                </div>
            </div>
            </div> <!-- .features -->

            <?php
                $today = date('Ymd');
                $args = array(
                        'numberposts' => 1,
                        'post_type' => 'events',
                        'meta_key'  => 'start_date',
                        'orderby'   => 'meta_value',
                        'order' => 'ASC',
                         'meta_query' => array(
                            array(
                                'key'       => 'end_date',
                                'compare'   => '>=',
                                'value'     => $today,
                            )
                        ),
                    );
                $wp_query = new WP_Query( $args );
                $recent_event = $wp_query->posts;
            ?>
            <?php if (!empty($recent_event)): ?>
            <div class="event-callout">
                <div class="left">
                    <span class="day"><?php echo date('D', strtotime(get_field('start_date', $recent_event[0]->ID))); ?><?php echo (get_field('end_date', $recent_event[0]->ID)) ? ' - '. date('D', strtotime(get_field('end_date', $recent_event[0]->ID)))  : ''; ?></span>
                    <span class="date"><?php echo date('M j', strtotime(get_field('start_date', $recent_event[0]->ID))); ?><?php echo (get_field('end_date', $recent_event[0]->ID)) ? '-'. date('j', strtotime(get_field('end_date', $recent_event[0]->ID)))  : ''; ?></span>
                    <span class="year"><?php echo date('Y', strtotime(get_field('start_date', $recent_event[0]->ID))); ?></span>
                </div>
                <div class="middle">
                    <h2><a href="<?php echo get_permalink($recent_event[0]->ID); ?>"><?php echo $recent_event[0]->post_title; ?></a></h2>
                    <h5><?php the_field('location', $recent_event[0]->ID); ?> <?php echo (get_field('booth', $recent_event[0]->ID)) ? '| ' . get_field('booth', $recent_event[0]->ID) : ''; ?></h5>
                </div>
                <div class="right">
                    <a href="mailto:<?php echo get_field('meet_with_swift_email', 'options'); ?>?subject=Upcoming Event: Let's get together!&body=I'm planning on attending this event and would like to meet with Swift while I am there. Here are some days and times I am available, a location I would like to meet, and my preferred method of contact:" class="button">Meet with Swift</a>
                    <a href="<?php echo get_permalink(1140); ?>" class="button">See All Events</a>
                </div>
            </div> <!-- .event-callout -->
            <?php endif; ?>



                <?php get_footer();