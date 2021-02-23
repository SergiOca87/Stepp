<?php
/**
* Template Name: Covid Blog
*/
?>

<?php get_header(); ?>

<div class="<?php if( get_field('popup') ): ?>
	<?php echo 'popup'; ?>
<?php endif; ?> news covid-blog mt-lg">

<!-- <div class="news covid-blog mt-lg"> -->
    
    <div class="grid-container">
        <h2 class="section-title blue text-center"><?php echo the_field('page_title'); ?></h2>
        <span class="under-bar--centered"></span>
        <p class="news__subtitle text-center"><?php echo the_field('page_intro')?></p>
    </div>

    <section class="news__section mt-lg">
        <div class="grid-container">
            
            <div class="news__wrap padding">
               <div class="news__items__content grid-x grid-margin-x grid-margin-y">
                
                    <?php
                    $query = new WP_Query( array( 'cat' => 66 ) );
                        while ($query->have_posts()): $query->the_post(); ?>
        
                        <div class="cell large-4 medium-6" data-aos="fade-up">
				            <div class="news__item">
                               
                                <div class="news__item__top">
                                    <span class="news__item__date"><?php echo get_the_date('F j, Y'); ?></span>
                                </div>
                                <div class="news__item__body">
                                    <a href="<?php the_permalink(); ?>"><h3 class="news__item__title blue mt-sm mb-sm"><?php the_title(); ?></h3></a>
                                    <div class="news__item__text mb-sm">
                                        <?php the_excerpt(); ?>
                                    </div>
                                    <a href="<?php the_permalink(); ?>" class="news__item__link"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/arrow-black.svg" alt="Link arrow" /></a>
                                </div>
                            </div>
                        </div>
                        <?php endwhile;
                        wp_reset_postdata(); 
                    ?>
                </div>
            </div>
        </div>
   
        <?php custom_numeric_posts_nav(); ?>
       
    </section>

</div>

<?php get_footer(); ?>
