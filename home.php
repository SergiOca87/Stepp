<?php get_header(); ?>

<div class="news mt-lg">
    
    <div class="grid-container">
        <h2 class="section-title blue text-center"><?php echo the_field('news_page_title', get_option('page_for_posts')); ?></h2>
        <span class="under-bar--centered"></span>
        <p class="news__subtitle text-center"><?php echo the_field('news_page_intro', get_option('page_for_posts')); ?></p>
    </div>

    <section class="news__section mt-lg">
        <div class="grid-container">
            <ul class="news__category__list text-center">
                <li class="cat-item-all active">ALL</li>
                <?php wp_list_categories(array(
                    'title_li' => '',
                    'exclude' => 66
                )); ?> 
            </ul>

            <div class="news__wrap">
               <div class="news__items__content grid-x grid-margin-x grid-margin-y">
                
                    <?php
                    $query = new WP_Query( array( 'cat' => -66 ) );
                        while ($query->have_posts()): $query->the_post(); ?>
        
                        <div class="cell large-4 medium-6" data-aos="fade-up">
				            <div class="news__item">
                                <div class="news__item__image__wrap mb-md object-fit-img-wrap">
                                    <?php echo the_post_thumbnail('medium'); ?>
                                </div>
                                <div class="news__item__top">
                                    <span class="news__item__date"><?php echo get_the_date('F j, Y'); ?></span>
                                    <span class="news__item__category">
                                        #<?php
                                            $categories = get_the_category();
            
                                            if ( ! empty( $categories ) ) {
                                                echo esc_html( $categories[0]->name );   
                                            }
                                        ?>
                                    </span>
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
                    ?>
                </div>
            </div>
        </div>
   
        <?php custom_numeric_posts_nav(); ?>
       
    </section>

</div>

<?php get_footer(); ?>