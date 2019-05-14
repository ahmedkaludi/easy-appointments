<?php /* Template Name: BB Template */ ?>
<?php get_header('simple'); ?>

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

<?php get_footer('simple'); ?>