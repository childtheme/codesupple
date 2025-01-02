
<?php 
/*
Plugin Name: Featured Posts Block
Description: Adds a "Featured Posts" block to the sidebar with customizable settings.
Version: 1.0.1
Author: Dmitrii Chempalov
*/

// Register Sidebar Widget
add_action( 'widgets_init', function() {
    register_widget( 'Featured_Posts_Block' );
});

class Featured_Posts_Block extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'featured_posts_block',
            __( 'Featured Posts Block', 'featured-posts' ),
            array( 'description' => __( 'Displays featured posts in the sidebar.', 'featured-posts' ) )
        );
    }

    public function widget( $args, $instance ) {
        $post_ids = ! empty( $instance['post_ids'] ) ? explode( ',', $instance['post_ids'] ) : [];
        $layout = ! empty( $instance['layout'] ) ? $instance['layout'] : 'stacked';

        $query_args = array(
            'post__in'            => $post_ids,
            'orderby'             => 'post__in',
            'posts_per_page'      => count( $post_ids ),
            'ignore_sticky_posts' => true,
        );

        $posts = new WP_Query( $query_args );

        echo $args['before_widget'];
        echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];

        if ( $posts->have_posts() ) {
            if ($layout === 'slider') {
                echo '<div class="swiper featured-posts-slider"><div class="swiper-wrapper">';
            } else {
                echo '<div class="featured-posts stacked">';
            }

            while ( $posts->have_posts() ) {
                $posts->the_post();
                if ($layout === 'slider') {
                    echo '<div class="swiper-slide featured-post-item">';
                } else {
                    echo '<div class="featured-post-item">';
                }
                ?>
                <a href="<?php the_permalink(); ?>">
                    <?php if ( has_post_thumbnail() ) { ?>
                        <div class="featured-post-thumbnail">
                            <?php the_post_thumbnail( 'thumbnail' ); ?>
                        </div>
                    <?php } ?>
                    <h4><?php the_title(); ?></h4>
                </a>
                </div>
                <?php
            }

            if ($layout === 'slider') {
                echo '</div><div class="swiper-pagination"></div><div class="swiper-button-next"></div><div class="swiper-button-prev"></div></div>';
            } else {
                echo '</div>';
            }
        }

        wp_reset_postdata();
        echo $args['after_widget'];
    }

    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Featured Posts', 'featured-posts' );
        $post_ids = ! empty( $instance['post_ids'] ) ? $instance['post_ids'] : '';
        $layout = ! empty( $instance['layout'] ) ? $instance['layout'] : 'stacked';
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'post_ids' ); ?>"><?php _e( 'Post IDs (comma-separated):' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'post_ids' ); ?>" name="<?php echo $this->get_field_name( 'post_ids' ); ?>" type="text" value="<?php echo esc_attr( $post_ids ); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'layout' ); ?>"><?php _e( 'Layout:' ); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id( 'layout' ); ?>" name="<?php echo $this->get_field_name( 'layout' ); ?>">
                <option value="stacked" <?php selected( $layout, 'stacked' ); ?>><?php _e( 'Stacked' ); ?></option>
                <option value="slider" <?php selected( $layout, 'slider' ); ?>><?php _e( 'Slider' ); ?></option>
            </select>
        </p>
        <?php
    }

    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = strip_tags( $new_instance['title'] );
        $instance['post_ids'] = strip_tags( $new_instance['post_ids'] );
        $instance['layout'] = strip_tags( $new_instance['layout'] );
        return $instance;
    }
}

// Enqueue Scripts and Styles
add_action( 'wp_enqueue_scripts', function() {
    wp_enqueue_style( 'swiper-style', 'https://unpkg.com/swiper/swiper-bundle.min.css' );
    wp_enqueue_script( 'swiper-script', 'https://unpkg.com/swiper/swiper-bundle.min.js', array(), null, true );
    wp_enqueue_style( 'featured-posts-style', plugin_dir_url( __FILE__ ) . 'assets/featured-posts-slider.css' );
    wp_enqueue_script( 'featured-posts-slider-script', plugin_dir_url( __FILE__ ) . 'assets/featured-posts.js', array( 'swiper-script' ), null, true );
});
