</div> <!-- close main container -->
<footer>
    <?php wp_footer(); ?>
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-81699527-1', 'auto');
        ga('send', 'pageview');

    </script>
    <script>
        /**
         * Add to chart button event
         */
        jQuery(document).ready(function(){
            jQuery('.btn-add-cart').on('click', function() {
                var $e = jQuery(this);
                $e.parent().parent().find('.wp-cart-button-form').submit();
            });
        });
    </script>
    <div class="container">
        <div class="row">&nbsp;</div>
        <div class="row">&nbsp;</div>
        <div class="row">
            <div class="col-md-5 col-md-offset-4">Easy Appointments - 2015-2019 - All rights reserved<br>Proudly powered by  <a href="http://wordpress.org">WordPress</a></div>
        </div>
        <div class="row"></div>
    </div>
</footer>
</body>
</html>
