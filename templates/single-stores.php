<?php
$location = get_field('address');

get_header();

if (have_posts()) : ?>
    <?php while(have_posts()) : the_post(); ?>
        <?php
        if( $location ): ?>
			<div class="acf-map" data-zoom="16">
				<div class="marker" data-lat="<?php echo esc_attr($location['lat']); ?>" data-lng="<?php echo esc_attr($location['lng']); ?>"></div>
			</div>
			<div class="row">
				<div class="col small-12">
					<h1><?php the_title(); ?></h1>

                    <?php the_content(); ?>
				</div>
			</div>


        <?php endif; ?>
    <?php endwhile; ?>
<?php
endif;
get_footer();