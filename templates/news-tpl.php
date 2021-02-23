<?php
/**
* Template Name: News
*/
?>

<?php get_header(); ?>

<div class="news">
    
    <h2 class="section-title blue text-center"><?php echo the_field('page_title'); ?></h2>
    <p class="text-center"><?php echo the_field('page_sub_title'); ?></p>

    <section>
        <ul class="news_category_list text-center">
            <li class="cat-item cat-item-all active">All</li>
            <?php wp_list_categories( array(
                'orderby' => 'name'
            ) ); ?> 
        </ul>

        <div class="news__wrap">
            <div class="grid-container">
                <div class="grid-x grid-margin-x grid-margin-y news__items__content">

                <?php
                $the_query = new WP_Query( $args );
            
                    // The Loop
                    if ( $the_query->have_posts() ) :
                        while ( $the_query->have_posts() ) :
                            $the_query->the_post(); ?>

                            <div class="news__item">
                                <div class="news__item__image__wrap">
                                    <?php echo the_post_thumbnail('medium'); ?>
                                </div>
                                <div class="news__item__top">
                                    <span class="news__item__date"><?php echo get_the_date('F j, Y'); ?></span>
                                    <span class="news__item__category">
                                        <?php if ( ! empty( $postcat ) ) :
                                            echo esc_html( $postcat[0]->name );   
                                        endif; ?>
                                    </span>
                                </div>
                                <div class="news__item__body">
                                    <a href="<?php the_permalink(); ?>"><a href="#"><h3 class="news__item__title blue mt-sm mb-xs"><?php the_title(); ?></h3></a>
                                    <p class="news__item__text"><?php the_excerpt(); ?></p>
                                    <a href="<?php the_permalink(); ?>" class="news__item__link"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/arrow-black.svg" alt="Link arrow" /></a>
                                </div>
                            </div>
                     
                        <?php endwhile; ?>
                    <?php endif; ?>
             
                </div>
            </div>
        <?php custom_numeric_posts_nav(); ?> 
        </div>
    </section>

</div>

<?php get_footer(); ?>