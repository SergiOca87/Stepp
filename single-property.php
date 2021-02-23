<?php

/**
 * Single
 *
 * Loop container for single post content
 */

get_header(); ?>
<?php while (have_posts()) : the_post(); ?>

    <!-- Variables and Fields -->
    <?php
    $general_tab = get_field_object('field_general_tab_group', $post);
    $location_tab = get_field_object('field_location_tab_group', $post);
	$gallery_tab = get_field_object('field_gallery_tab_group', $post);
	$brokers_tab = get_field_object('field_brokers_tab_group', $post);
    $building_size = $general_tab['value']['surface_table']['surface_value'];
    $building_size_unit = $general_tab['value']['surface_table']['surface_unit'];
    $address = $location_tab['value']['address'];
    $map = $location_tab['value']['map'];
    $city = $location_tab['value']['location_table']['city'];
	$gallery = $gallery_tab['value']['gallery'];
	$broker = $brokers_tab['value']['brokers'];
	$status = $general_tab['value']['status'];
	$price = $general_tab['value']['price_table']['price_value'];
  	$price_postfix = $general_tab['value']['price_table']['price_postfix'];
    ?>

    <?php
    $currentState;
    $states = get_the_terms($post->ID, 'property-state');
    if ($states != null) {
        foreach ($states as $state) {
            $currentState = $state->slug;
            unset($state);
        }
    }
	
	$currentMarket;
    $markets = get_the_terms($post->ID, 'market');
    if ($markets != null) {
        foreach ($markets as $market) {
            $currentMarket = $market->name;
            unset($market);
        }
	}
	
	$currentStatus;
	$statuses = get_the_terms($post->ID, 'status');
    if ($statuses != null) {
        foreach ($statuses as $status) {
            $currentStatus = $status->name;
            unset($status);
        }
    }
    

	$currentType;
		$types = get_the_terms($post->ID, 'property-type');
		if ($types != null) {
			foreach ($types as $type) {
				$currentType = $type->name;
				unset($type);
			}
		}
	?>

    <main class="single-property mb-lg">
		<section>
			<div class="grid-container">
				<div class="single-property__header">
					<div>
						<h1 class="section-title blue"><?php the_title(); ?></h1>
						<p class="single-property__details__address lead black">
							<?php echo $city; ?>, <?php echo strtoupper($currentState); ?>
						</p>
					</div>
					<div>
						<?php if( $price ): ?>
							<p>Sale Price</p>
							<p>$<?php echo number_format($price); ?></p>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</section>

		<section class="single-property__gallery">
			<div class="grid-container fluid">
				<div class="grid-x">
					<div class="cell medium-8">
						<?php
						if ($gallery) : ?>
							<div class="slider-for">
								<?php foreach ($gallery as $image) : ?>
									<div class="single-property__gallery__slide">
										<img src="<?php echo $image['sizes']['large']; ?>" alt="Property Slider Image" />
									</div>
								<?php endforeach; ?>
							</div>
							<div class="slider-nav">
								<?php foreach ($gallery as $image) : ?>
									<div class="single-property__gallery__nav">
										<img src="<?php echo $image['sizes']['small']; ?>" alt="Property Nav Slider Image" />
									</div>
								<?php endforeach; ?>
							</div>
							
						<?php else : ?>
							<div class="single-property__gallery__hero" style="background-image: url(<?php echo get_the_post_thumbnail_url( $post->ID, 'hero'); ?>)">
								
							</div>
						<?php endif; ?>
					</div>
					<div class="cell medium-4">
					<?php

						$featured_posts = $broker;
						if( $featured_posts ): ?>

							<?php foreach( $featured_posts as $post ): 

								setup_postdata($post); ?>
								<div class="single-property__broker">
									<div class="mb-sm">
										<p><?php the_title(); ?></p>
									</div>	
								</div>
							
							<?php
							endforeach;
							wp_reset_postdata(); ?>

						<?php endif; ?>

					</div>
				</div>
			</div>
		</section>

		<section class="single-property__overview">
			<div class="grid-container small-container">
				<h2 class="section-title blue text-center">Investment Overview</h2>
				<div class="grid-x grid-margin-x grid-margin-y">
					<?php if ($market) : ?>
						<div class="cell large-3 medium-6">
							<p class="single-property__overview__title black">Market</p>
							<p class="single-property__overview__text"><?php echo $market; ?></p>
						</div>
					<?php endif; ?>
				</div>
			</div>					
		</section>

		<section class="single-property__offering">
			<div class="grid-container small-container">
				<h2 class="section-title blue text-center">The Offering</h2>
				<div class="single-property__offering__wrap">
					<?php the_content(); ?>
				</div>
			</div>		
		</section>

		<section class="single-property__highlights">
			<div class="grid-container small-container">
				<h2 class="section-title blue text-center">Investment Highlights</h2>
				<div class="single-property__highlights__wrap">
					<p>Highlights</p>
				</div>
			</div>		
		</section>

		<section class="single-property__tour">
			<div class="grid-container small-container">
				<h2 class="section-title blue text-center">Virtual Tour</h2>
				<div class="single-property__tour__wrap">
					<p>Tour</p>
				</div>
			</div>		
		</section>
         
    </main>


<?php endwhile; ?>
<?php get_footer(); ?>