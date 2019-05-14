<?php /* Template Name: Single column - sidebar TOC */ ?>
<?php get_header(); ?>

    <div class="row">
        <div id="main-content" class="col-md-7 col-sm-push-3">

            <?php if(have_posts()) : ?>
                <?php while(have_posts()) : the_post(); ?>
                    <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <div style="position: absolute; top: 0px; color: grey;"><small>Last modified: <?php the_modified_date(); ?></small></div>
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
        <div class="col-md-3 col-sm-pull-7 hidden-xs">
            <div id="doc-sidebar">
                <h4>Content :</h4>
                <ul>

                </ul>
            </div>
            <script>
                jQuery(document).ready(function($) {
                    var titles = $('#main-content').find('h1,h2,h3,h4,h5,h6');
                    var sidebar = $('#doc-sidebar').find('ul');

                    var max_heading = 6;

                    // get max header
                    $.each(titles, function(index, e) {
                        var local = parseInt($(e).prop('tagName').toLowerCase().charAt(1));
                        max_heading = ( local < max_heading ) ? local : max_heading;
                    });

                    $.each(titles, function(index, element) {
                        var $el = $(element);

                        var ident = parseInt($el.prop('tagName').toLowerCase().charAt(1));
                        var margin_left = (ident - max_heading) * 15;

                        var li = $(document.createElement('li'));
                        li.css('padding-left', margin_left);

                        if (max_heading == ident) {
                            li.addClass('root-level');
                        }

                        var link = $('<a href="#' + $el.attr('id') + '">' + $el.text() + '</a>');

                        li.append(link);
                        sidebar.append(li);
                    });
                });
            </script>
            <?php
            if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('Sidebar')) : //  Sidebar name
                ?>
                <?php
            endif;
            ?>
        </div>
    </div>

<?php get_footer(); ?>