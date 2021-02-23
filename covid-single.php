<?php get_header(); ?>

<?php while (have_posts()): the_post(); ?>

<div class="news-single">

	<div class="grid-container mb-md mt-lg">
		<div>
			<h1 class="news-single__title blue"><?php the_title(); ?></h1>
			<span class="under-bar"></span>
			<div class="breadcrumb mb-md">
				
				<p class="fade"><?php echo get_the_date('F j, Y'); ?></p>

				<div class="news-single__share__buttons">
					<span class="light-blue news-single__category">

					</span>
					<a href="https://www.facebook.com/sharer/sharer.php?u=example.org" target="_blank">
						<img src="<?php echo get_template_directory_uri(); ?>/assets/img/facebook-white.svg" alt="linkedin icon" /> SHARE
					</a>
					<a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php the_permalink(); ?>&title=<?php the_title(); ?>" target="_blank">
						<img src="<?php echo get_template_directory_uri(); ?>/assets/img/linkedin-white.svg" alt="linkedin icon" /> SHARE
					</a>
				</div>

			</div>
		</div>
	</div>

	<section class="news-single__post">
		<div class="grid-container">

			<div class="breadcrumb-link__wrap">
				<svg class="arrow-link" xmlns="http://www.w3.org/2000/svg" width="21" height="12" viewBox="0 0 21 12">
					<g fill="#065ea9" fill-rule="evenodd" stroke="#065ea9" stroke-linecap="square" stroke-width="2">
						<path d="M1 6h18M15.082 2.082L19 6M15.082 9.918L19 6"/>
					</g>
				</svg>
				<a class="breadcrumb-link blue" href="https://steppcommercial.com/covid-news/">back to all news</a>
			</div>

			<div class="grid-x grid-margin-x">
				<div class="cell large-8 news-single__post__main__content__wrap">

					<div class="news-single__post__featured mb-md">
						<?php the_post_thumbnail( 'full' ); ?>
					</div>
					<div class="entry-content news-single__post__main__content">
						<?php the_content(); ?>

						<div class="news-single__share__buttons mt-md mb-lg">
							<a href="https://www.facebook.com/sharer/sharer.php?u=example.org" target="_blank">
								<img src="<?php echo get_template_directory_uri(); ?>/assets/img/facebook-white.svg" alt="linkedin icon" /> SHARE
							</a>
							<a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php the_permalink(); ?>&title=<?php the_title(); ?>" target="_blank">
								<img src="<?php echo get_template_directory_uri(); ?>/assets/img/linkedin-white.svg" alt="linkedin icon" /> SHARE
							</a>
						</div>
					</div>
				</div>
			
				<div class="cell large-4 news-single__post__sidebar">

                <?php $the_query = new WP_Query( array(
							'posts_per_page' => 4,
							'post__not_in' => array( $post->ID ),
							'cat' => 66
						));

                    while ($the_query -> have_posts()) : ?>
                    
					<p class="news-single__post__sidebar__title mb-lg">RECENT NEWS</p>
					<ul class="news-single__post__sidebar__list">

                        <?php
						 $the_query -> the_post(); ?>
					
								<a class="blue" href="<?php the_permalink(); ?>">
									<li>
										<span class="news-single__post__sidebar__list__date"><?php echo get_the_date('F j, Y'); ?></span>
										<span class="news-single__post__sidebar__list__title"><?php the_title(); ?></span>
									</li>
								</a>

                    </ul>
                    <?php
                    endwhile;
                    wp_reset_postdata();
                    ?>
				</div>
			</div>
		</div>
	</section>

	<section class="news-single__related padding">
		
		<?php
		$related = get_posts( 
			array( 
				'category__in' => wp_get_post_categories( $post->ID ), 
				'numberposts'  => 3, 
				'post__not_in' => array( $post->ID ) 
			) 
		);

		if( $related ) { ?>

			<h2 class="section-title blue text-center">Related Articles</h2>
			<span class="under-bar--centered"></span>

			<div class="grid-container">
				<div class="grid-x grid-margin-x grid-margin-y">

					<?php
					foreach( $related as $post ) {
						setup_postdata($post); ?>
							<div class="cell medium-6 large-4 news__item">
								<span class="news__item__date"><?php echo get_the_date('F j, Y'); ?></span>
								<a href="<?php the_permalink(); ?>"><h3 class="news__item__title blue mt-sm mb-sm"><?php the_title();?></h3></a>
								<div class="news__item__text mb-sm"><?php the_excerpt(); ?></div>
								<a href="<?php the_permalink(); ?>" class="news__item__link"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/arrow-black.svg" alt="Link arrow" /></a>
							</div>
						<?php
					}
					wp_reset_postdata(); ?>

				</div>
			</div>
		<?php } ?>

	</section>
</div>

<?php endwhile; ?>

<?php get_footer(); ?>