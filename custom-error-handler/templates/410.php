<?php
/**
 * Custom 410 Template
 */

get_header(); ?>

<div id="primary" class="content-area" style="color: <?php echo get_option("custom_410_text_color", "#000000"); ?>;">
    <main id="main" class="site-main" role="main">
        <section class="error-410">
            <header class="page-header">
                <h1 class="page-title"><?php echo esc_html(get_option("custom_410_title", "Page Gone")); ?></h1>
            </header>
            <div class="page-content">
                <p><?php echo esc_html(get_option("custom_410_message", "Sorry, the page you are looking for is no longer available.")); ?></p>
                <a href="<?php echo home_url(); ?>" class="button"><?php echo esc_html(get_option("custom_410_button_text", "Go to Home")); ?></a>
            </div>
        </section>
    </main>
</div>

<?php get_footer(); ?>