<?php /* Template Name: Single column - center */ ?>
<?php get_header(); ?>

    <div class="row">

        <div class="col-md-8 col-md-offset-2">

            <?php if(have_posts()) : ?>
                <?php while(have_posts()) : the_post(); ?>
                    <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <?php the_content(); ?>
                    </div>
                    <?php
                    if (is_singular()) {
                        // support for pages split by nextpage quicktag
                        wp_link_pages();

                        if ( comments_open() || get_comments_number() ) :
                            comments_template();
                        endif;
                        // tags anyone?
                        the_tags();
                    }
                    ?>
                <?php endwhile; ?>

            <?php else : ?>

                <div class="alert alert-info">
                    <strong>No content in this loop</strong>
                </div>

            <?php endif; ?>
        </div>

        <div class="col-md-4">

            <?php
            if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('Sidebar')) : //  Sidebar name
                ?>
                <?php
            endif;
            ?>
        </div>

    </div>

<?php get_footer(); ?>