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
    $campus_logo = $general_tab['value']['campus_logo'];
    $building_size = $general_tab['value']['surface_table']['surface_value'];
    $building_size_unit = $general_tab['value']['surface_table']['surface_unit'];
    $address = $location_tab['value']['address'];
    $map = $location_tab['value']['map'];
    $city = $location_tab['value']['location_table']['city'];
	$building_size = $general_tab['value']['building_size'];
	$beds = $general_tab['value']['beds'];
	$units = $general_tab['value']['units'];
    $submarket = $general_tab['value']['submarket'];
    $markets = $general_tab['value']['market'];
	$gallery = $gallery_tab['value']['gallery'];
	$website_url = $general_tab['value']['website_url'];
	$asset_class = $general_tab['value']['asset_class'];
	$date_acquired = $general_tab['value']['date_acquired'];
	$date_sold = $general_tab['value']['date_sold'];
	$status = $general_tab['value']['status'];
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
            <div class="single-property__gallery__wrap">

                <?php
                //$images = get_field('gallery');
                if ($gallery) : ?>

                    <div class="slides single-property__slider">
                        <?php foreach ($gallery as $image) : ?>
                            <div class="single-property__slide">
                                <img src="<?php echo $image['sizes']['large']; ?>" alt="Property Slider Image" />
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="slider__controls">
						<div class="slick-prev">
							<svg xmlns="http://www.w3.org/2000/svg" width="44" height="72" viewBox="0 0 44 72">
								<g fill="none" fill-rule="evenodd">
									<g stroke="#FFF" stroke-width="1.5">
										<g>
											<g>
												<g>
													<path d="M16.125 16.125L0 0M16.125 16.125L.375 32.625" transform="translate(-20 -418) translate(21 419) matrix(-1 0 0 1 42 17.887)"/>
												</g>
											</g>
											<path d="M22.517 0c-.834.364-1.651.757-2.452 1.178C8.135 7.454 0 19.971 0 34.388c0 15.961 9.973 29.594 24.027 35.006" transform="translate(-20 -418) translate(21 419)"/>
										</g>
									</g>
								</g>
							</svg>
						</div>
						<div class="slick-next">
							<svg xmlns="http://www.w3.org/2000/svg" width="44" height="72" viewBox="0 0 44 72">
								<g fill="none" fill-rule="evenodd">
									<g stroke="#FFF" stroke-width="1.5">
										<g>
											<g>
												<g>
													<path d="M16.125 16.125L0 0M16.125 16.125L.375 32.625" transform="translate(-1370 -418) matrix(-1 0 0 1 1413 419) matrix(-1 0 0 1 42 17.887)"/>
												</g>
											</g>
											<path d="M22.517 0c-.834.364-1.651.757-2.452 1.178C8.135 7.454 0 19.971 0 34.388c0 15.961 9.973 29.594 24.027 35.006" transform="translate(-1370 -418) matrix(-1 0 0 1 1413 419)"/>
										</g>
									</g>
								</g>
							</svg>
						</div>
					</div>
				<?php else : ?>
					<div class="grid-container"></div>
					<div class="single-property__hero" style="background-image: url(<?php echo get_the_post_thumbnail_url( $post->ID, 'hero'); ?>)">
						
					</div>
                <?php endif; ?>

            </div>
        </section>

        <section>
            <div class="grid-container">
                <div class="single-property__inner__container grey-bg mt-md">
                    <div class="single-property__details__wrap">
						<div class="grid-x grid-margin-x grid-margin-y">
							<div class="cell large-6">
								<div class="single-property__details__title__wrap">
									<div>
										<h1 class="single-property__details__main__title primary"><?php the_title(); ?></h1>
										<p class="single-property__details__address lead black">
											<?php echo $city; ?>, <?php echo strtoupper($currentState); ?>
										</p>
									</div>
									<?php if( $website_url ) : ?>
										<a href="<?php echo $website_url; ?>" class="btn btn-secondary single-property__details__btn" target="_blank"><span>VIEW WEBSITE</span></a>
									<?php endif; ?>
								</div>
							</div>

							<div class="cell large-6">
								<?php if ($address) : ?>
									<div class="single-property__detail">
										<p class="single-property__detail__title black">Address:</p>
										<p class="single-property__detail__text mt-xs fade-blue"><?php echo $address; ?></p>
									</div>
								<?php endif; ?>
								<?php if ($markets) : ?>
									<div class="single-property__detail">
										<p class="single-property__detail__title black">Market:</p>
										<p class="single-property__detail__text mt-xs fade-blue"><?php echo $currentMarket; ?></p>
									</div>
								<?php endif; ?>
								<?php if ($building_size) : ?>
									<div class="single-property__detail">
										<p class="single-property__detail__title black">Building Size:</p>
										<p class="single-property__detail__text mt-xs fade-blue"><?php echo $building_size; ?></p>
									</div>
								<?php endif; ?>
								<?php if ($beds) : ?>
									<div class="single-property__detail">
										<p class="single-property__detail__title black">Beds:</p>
										<p class="single-property__detail__text mt-xs fade-blue"><?php echo $beds; ?></p>
									</div>
								<?php endif; ?>
								<?php if ($units) : ?>
									<div class="single-property__detail">
										<p class="single-property__detail__title black">Units:</p>
										<p class="single-property__detail__text mt-xs fade-blue"><?php echo $units; ?></p>
									</div>
								<?php endif; ?>
								<?php if ($types) : ?>
									<div class="single-property__detail">
										<p class="single-property__detail__title black">Asset Class:</p>
										<p class="single-property__detail__text mt-xs fade-blue"><?php echo $currentType; ?></p>
									</div>
								<?php endif; ?>
								<?php if ($date_acquired) : ?>
									<div class="single-property__detail">
										<p class="single-property__detail__title black">Date Acquired:</p>
										<p class="single-property__detail__text mt-xs fade-blue"><?php echo $date_acquired; ?></p>
									</div>
								<?php endif; ?>
								<?php if ($date_sold) : ?>
									<div class="single-property__detail">
										<p class="single-property__detail__title black">Date Sold:</p>
										<p class="single-property__detail__text mt-xs fade-blue"><?php echo $date_sold; ?></p>
									</div>
								<?php endif; ?>
								<?php if ($statuses) : ?>
									<div class="single-property__detail">
										<p class="single-property__detail__title black">Status:</p>
										<p class="single-property__detail__text mt-xs fade-blue"><?php echo $currentStatus; ?></p>
									</div>
								<?php endif; ?>
                                  
							</div>
						</div>
					</div>
				</div>

				<div class="text-center mt-md">
					<a href="<?php echo get_site_url(); ?>/portfolio/" class="secondary single-property__breadcrumb"><span></span>VIEW ALL PROPERTIES</a>
				</div>
				<div class="mt-md mb-md">
					<hr>
				</div>
			</div>
		</section>

		<section>
			<div class="grid-container">
				<div class="grid-container-small">
					<?php if( '' !== get_post()->post_content ): ?>
						<h3 class="primary text-center mt-lg mb-sm">Property Description</h3>
						<div class="single-property__description__wrap mb-lg">
							<?php the_content(); ?>
						</div>
					<?php endif; ?>

					<div class="single-property__details__map__wrap mt-xl">
						<?php
						$location_tab = get_field_object('field_location_tab_group', $post);
						$full_address = $location_tab['value']['address'];
						?>
						<div class="single-property__map">
							<div>
								<?php
								if (!empty($map)) :
								?>
									<h3 class="primary text-center mb-sm">On the map</h3>
									<div class="acf-map">
										<div class="marker" data-lat="<?php echo $map['lat']; ?>" data-lng="<?php echo $map['lng']; ?>"></div>
									</div>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>				
                        
    </main>


<?php endwhile; ?>
<?php get_footer(); ?>