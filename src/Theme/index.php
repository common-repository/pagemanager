<?php

get_header(); ?>


		<?php
		if ( have_posts() ) :

			if ( is_home() && ! is_front_page() ) : ?>
				<header>
					<h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
				</header>

			<?php
			endif;



            \Pagemanager\PageManager::run('index');



		else :

            \Pagemanager\PageManager::run('index');

            get_template_part( 'template-parts/content', 'none' );

		endif; ?>


<?php
get_sidebar();
get_footer();
