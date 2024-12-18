<?php
/**
 * Custom 404 Template
 */

get_header(); ?>

<div id="primary" class="content-area" style="color: <?php echo get_option("custom_404_text_color", "#000000"); ?>;">
    <main id="main" class="site-main" role="main">
        <section class="error-404">
            <header class="page-header">
                <h1 class="page-title"><?php echo esc_html(get_option("custom_404_title", "Page Not Found")); ?></h1>
            </header>
            <div class="page-content">
                <p><?php echo esc_html(get_option("custom_404_message", "Sorry, the page you are looking for could not be found.")); ?></p>
                <a href="<?php echo home_url(); ?>" class="button"><?php echo esc_html(get_option("custom_404_button_text", "Go to Home")); ?></a>
            </div>
        </section>
    </main>
</div>

<?php get_footer(); ?>