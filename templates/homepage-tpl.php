<?php
/**
* Template Name: Homepage
*/
?>

<?php get_header(); ?>

<div class="homepage">

    <div class="social__media__wrap hide-for-large">
        <div class="grid-container">
            <div class="social__media__wrap__link">
                <a href="https://steppcommercial.com/covid-news">Get the latest developments on how COVID-19 is affecting landlords, tenants and investors.</a>
            </div>
            <!-- <ul class="social__media__list">
                <li><a href="<?php echo the_field('facebook', 'option'); ?>" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/facebook-white.svg" alt="Facebook"></a></li>
                <li><a href="<?php echo the_field('linkedin', 'option'); ?>" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/linkedin-white.svg" alt="linkedIn"></a></li>
                <li><a href="<?php echo the_field('instagram', 'option'); ?>" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/instagram-white.svg" alt="Instagram"></a></li>
            </ul> -->
        </div>
    </div>
    
    <section class="homepage__hero">
        
        <?php if( get_field('homepage_video') ) : ?>

            <div class="homepage__hero__overlay"></div>
            <video loop muted autoplay="autoplay" preload="auto" class="fullscreen-video">
                <source src="<?php echo get_template_directory_uri(); ?>/assets/video/media.io_stepp-commercial-background-v1-comp2.mp4" type="video/mp4">
            </video>
            <div class="grid-container">
                <div class="homepage__hero__title__wrap padding mb-xs">
                    <div>
                        <p class="homepage__hero__pre-title mb-xs"><?php echo the_field('hero_pre_title'); ?></p>
                        <h1 class="homepage__hero__title mb-sm"><?php echo the_field('hero_title'); ?></h1>
                    </div>
                    <a href="<?php the_field('hero_button_url'); ?>" class="btn btn--round"><?php echo the_field('hero_button_text'); ?></a>
                </div>
            </div>

        <?php elseif( have_rows('homepage_slide') ) : ?>
            
            <div class="homepage__hero__slider">

                <?php while( have_rows('homepage_slide') ): the_row(); 
                
                    // vars
                    $slide_image = get_sub_field('slide_image');
                    $slide_pre_title = get_sub_field('slide_pre_title');
                    $slide_title = get_sub_field('slide_title');
                    $slide_button_text = get_sub_field('slide_button_text');
                    $slide_button_url = get_sub_field('slide_button_url');
                    ?>
                    <div class="homepage__hero__slide" style="background: url( <?php echo $slide_image ?> )" >
                        <div class="homepage__hero__overlay"></div>
                        <div class="grid-container">
                            <div class="homepage__hero__title__wrap mb-xs">
                                <div>
                                    <?php if(  get_sub_field('slide_pre_title') ) : ?>
                                        <p class="homepage__hero__pre-title mb-xs"><?php echo $slide_pre_title; ?></p>
                                    <?php endif; ?>
                                    <?php if(  get_sub_field('slide_title') ) : ?>
                                        <h1 class="homepage__hero__title"><?php echo $slide_title; ?></h1>
                                    <?php endif; ?>
                                </div>
                                <?php if(  get_sub_field('slide_button_text') ) : ?>
                                    <a href="<?php echo $slide_button_url; ?>" class="btn btn--round"><?php echo $slide_button_text; ?></a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
            
                <?php endwhile; ?>

            </div>
            <div class="homepage__hero__slider__dots__wrap">
                <div class="homepage__hero__slider__prev slick-prev">
                    <svg class="arrow-link" xmlns="http://www.w3.org/2000/svg" width="21" height="12" viewBox="0 0 21 12">
                        <g fill="#fff" fill-rule="evenodd" stroke="#fff" stroke-linecap="square" stroke-width="2">
                            <path d="M1 6h18M15.082 2.082L19 6M15.082 9.918L19 6"/>
                        </g>
                    </svg>
                </div>
        
                <div class="homepage__hero__slider__dots"></div>
            
                <div class="homepage__hero__slider__next slick-next">
                    <svg class="arrow-link" xmlns="http://www.w3.org/2000/svg" width="21" height="12" viewBox="0 0 21 12">
                        <g fill="#fff" fill-rule="evenodd" stroke="#fff" stroke-linecap="square" stroke-width="2">
                            <path d="M1 6h18M15.082 2.082L19 6M15.082 9.918L19 6"/>
                        </g>
                    </svg>
                </div>
            </div>
            
        <?php else : ?>

            <div class="homepage__hero__overlay"></div>
            <div class="grid-container">
                <div class="homepage__hero__title__wrap padding mb-xs">
                    <div>
                        <?php if( get_field('hero_pre_title') ) : ?>
                            <p class="homepage__hero__pre-title mb-xs"><?php echo the_field('hero_pre_title'); ?></p>
                        <?php endif; ?>
                        <h1 class="homepage__hero__title mb-sm"><?php echo the_field('hero_title'); ?></h1>
                    </div>
                    <?php if( get_field('hero_button_text') ) : ?>
                        <a href="<?php the_field('hero_button_url'); ?>" class="btn btn--round"><?php echo the_field('hero_button_text'); ?></a>
                    <?php endif; ?>
                </div>
            </div>

        <?php endif; ?>

    </section>

    <section class="contact new-section dark-section padding hide">
        <div class="grid-container">
            <h2 class="section-title white"><?php echo the_field('contact_new_title'); ?></h2>
            <span class="under-bar"></span>
            <div class="grid-x grid-margin-x grid-margin-y">
                <div class="cell large-6">
                    <p class="contact__text white mb-xl"><?php echo the_field('contact_new_text'); ?></p>
                    <div class="contact__button__wrap">
                        <a href="<?php the_field('contact_new_button_url'); ?>" class="btn btn--round white"><?php echo the_field('contact_new_button_text'); ?></a>
                    </div>
                </div> 
                <div class="cell large-6">
                <?php if( get_field('contact_new_image') ) : ?>
                    <img class="contact__image" src="<?php echo get_field('contact_new_image'); ?>" alt="<?php echo the_field('contact_new_text'); ?>">
                <?php endif; ?>
                </div> 
            </div>
        </div>
    </section>

    <section class="featured__listings padding">
        <h2 class="section-title blue text-center"><?php echo the_field('featured_listings_title'); ?></h2>
        <span class="under-bar--centered"></span>
        <div class="grid-x grid-margin-x align-center mt-md">

            <?php 

            $posts = get_field('featured_properties');
            if( $posts ): ?>
                <?php foreach( $posts as $post): // variable must be called $post (IMPORTANT) ?>
                    <?php setup_postdata($post); ?>

                        <?php
                            $location_tab = get_field_object('field_location_tab_group', $post);
                            $general_tab = get_field_object('field_general_tab_group', $post);

                            $city = $location_tab['value']['location_table']['city'];
                            $units = $general_tab['value']['units_count'];
                            //$state = $location_tab['value']['location_table']['state'];
                        ?>

                        <div class="cell medium-6 large-4 featured__property__card text-center">
                        <a class="featured__property__card__top__title" href="<?php the_permalink() ?>" target="_blank">
                            
                        
                                <div class="featured__property__card__title">
                                    
                                        <h3><?php the_title(); ?></h3>
                                
                                    <span class="featured__property__card__separator"></span>
                                    <div class="featured__property__card__hidden__units">
                                        <p class="strong"><?php echo $city; ?></p>
                                        <?php if( $units ) : ?>
                                            <p><?php echo $units; ?> Units</p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </a>
                            <div class="featured__property__card__overlay"></div>
                            <img class="featured__property__card__image" src="<?php the_post_thumbnail_url( $size = 'medium' ); ?>" alt="<?php the_title(); ?>">
                            <a class="featured__property__card__link" href="<?php the_permalink() ?>" target="_blank">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/img/polygon.png" alt="" class="card__polygon">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/img/arrow-white.svg" alt="" class="featured__property__card__arrow">
                            </a>
                        </div>

                    <?php endforeach; ?>
                <?php wp_reset_postdata();  ?>
            <?php endif; ?>

        </div>

        <div class="centered-button-wrap mt-xl">
            <a href="<?php the_field('featured_listings_button_url'); ?>" class="btn btn--round"><?php echo the_field('featured_listings_button_text'); ?></a>
        </div>
    </section>

    <section class="team mb-xxl mt-sm">
        <div class="grid-container no-padding">
            <div class="grid-x grid-margin-x grid-margin-y align-center-large">
                <div class="cell large-6 team__text__wrap">
                    <div>
                        <h2 class="team__title blue"><?php echo the_field('team_title'); ?></h2>
                        <span class="under-bar"></span>
                        <p class="team__text mb-md"><?php echo the_field('team_text'); ?></p>
                    </div>
                    <div class="team__button__wrap">
                        <a href="<?php the_field('team_button_url'); ?>" class="btn btn--round"><?php echo the_field('team_button_text'); ?></a>
                    </div>
                </div>

                <div class="cell large-6 team__image__wrap">
                    <?php if( have_rows('team_members_images') ): ?>
                        <?php while( have_rows('team_members_images') ): the_row(); 

                            // vars
                            $image = get_sub_field('team_member_image');

                            ?>
                            <div class="team__member__image">
                                <img src="<?php echo $image['sizes']['medium']; ?>" alt="Team Member"> 
                            </div>

                        <?php endwhile; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <section class="contact blue-section padding">
        <div class="grid-container">
            <h2 class="contact__title white"><?php echo the_field('contact_title'); ?></h2>
            <span class="under-bar"></span>
            <div class="grid-x grid-margin-x grid-margin-y">
                <div class="cell large-6">
                    <p class="contact__text white mb-xl"><?php echo the_field('contact_text'); ?></p>
                    <div class="contact__button__wrap">
                        <a href="<?php the_field('contact_button_url'); ?>" class="btn btn--round white"><?php echo the_field('contact_button_text'); ?></a>
                    </div>
                </div> 
                <div class="cell large-6">
                <?php if( get_field('contact_image') ) : ?>
                    <img class="contact__image" src="<?php echo get_field('contact_image'); ?>" alt="How can we help you">
                <?php endif; ?>
                </div> 
            </div>
        </div>
    </section>

    <section class="news padding">
        <h2 class="section-title blue text-center"><?php echo the_field('news_title'); ?></h2>
        <span class="under-bar--centered"></span>

        <div class="grid-container mt-md">
            <div class="grid-x grid-margin-x grid-margin-y align-center">

                <?php 
                $args = array(
                    'post_type' => 'post',
                    'posts_per_page' => 6,
                    'cat' => '-66' 
                );

                $the_query = new WP_Query( $args );

               
                    while ( $the_query->have_posts() ) :
                        $the_query->the_post(); ?>

                            <div class="cell large-4 medium-6" data-aos="fade-up">
                                <div class="news__item">
                                    <div class="news__item__image__wrap mb-md">
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

                        <?php endwhile; ?>
                <?php wp_reset_postdata(); ?>
                
            </div>
        </div>

        <div class="centered-button-wrap mt-xl">
            <a href="<?php the_field('news_button_url'); ?>" class="btn btn--round"><?php echo the_field('news_button_text'); ?></a>
        </div>
    </section>

</div>


    

<?php get_footer(); ?>