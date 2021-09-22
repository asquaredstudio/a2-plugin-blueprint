<?php

get_header();

if ( have_posts() ) :

	echo do_shortcode( '[facetwp facet="map"]' ); ?>
	<div class="row">
		<div class="col small-8">
			<h4>Location</h4>
			<?php
			echo do_shortcode( '[facetwp facet="proximity"]' ); ?>
		</div>
		<div class="col small-4">
			<h4>Category</h4>
			<?php
			echo do_shortcode( '[facetwp facet="store_categories"]' ); ?>
		</div>
	</div>
	<div class="row">
		<div class="col small-12">
			<div class="facetwp-template">
				<h2>Stores</h2>
				<?php
				while ( have_posts() ) : the_post(); ?>
					<h2><a href="<?php
						the_permalink(); ?>"><?php
							the_title(); ?></a></h2>
					<?php
					the_content(); ?>
				<?php
				endwhile; ?>
			</div>

		</div>
	</div>

<?php
endif;
get_footer();
