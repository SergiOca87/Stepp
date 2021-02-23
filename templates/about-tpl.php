<?php
/**
* Template Name: About
*/
?>

<?php get_header(); ?>

<div class="about mt-xl">
    <h2 class="section-title blue text-center"><?php echo the_field('about_page_title'); ?></h2>
    <span class="under-bar--centered"></span>
    <section class="about__hero padding mt-lg" style="background: url( <?php echo the_field('about_intro_image'); ?> ) ">
        <div class="grid-container">
            <div class="about__hero__text__wrap">
                <h3 class="about__hero__text mb-md"><?php echo the_field('about_page_intro'); ?></h3>
                <div class="about__hero__text__footer">
                    <ul class="social__media__list">
                        <li><a href="<?php echo the_field('facebook', 'option'); ?>" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/facebook-light-blue.svg" alt="Facebook"></a></li>
                		<li><a href="<?php echo the_field('linkedin', 'option'); ?>" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/linkedin-light-blue.svg" alt="linkedIn"></a></li>
                		<li><a href="<?php echo the_field('instagram', 'option'); ?>" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/instagram-light-blue.svg" alt="Instagram"></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section class="team">
        <div class="grid-container no-padding">
            <div class="grid-x grid-margin-x grid-margin-y align-center-large">
                <div class="cell large-6">
                    <div class="team__text__wrap padding">
                        <h2 class="team__title blue"><?php echo the_field('meet_team_title'); ?></h2>
                        <p class="team__text mb-md"><?php echo the_field('meet_team_text'); ?></p>
                    </div>
                </div>

                <div class="cell large-6">
                    <div class="grid-x">
                        <!-- Principal -->
                        <?php 
                        $args = array(  
                        'post_type' => 'team',
                            'posts_per_page' => -1,
                            'orderby' => 'menu_order',
                            'tax_query' => array(
                                array (
                                    'taxonomy' => 'position',
                                    'field' => 'slug',
                                    'terms' => 'principal'
                                )
                            ),
                        );

                        $the_query = new WP_Query( $args ); ?>
                        <?php if ( $the_query->have_posts() ) : ?>
                            <?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>

                                <div class="cell medium-6">
                                    <div class="team__member">
                                         <div class="object-fit-img-wrap">
                                            <?php echo the_post_thumbnail('portrait') ?>
                                        </div>
                                        <div class="team__member__body">
                                            <div class="team__member__text__wrap">
                                                <a href="<?php the_permalink(); ?>">
                                                    <h3 class="team__member__title blue"><?php the_title(); ?></h3>
                                                </a>
                                                <p class="team__member__position"><?php echo the_field('job_title'); ?></p>
                                            
                                                <div class="team__member__footer mt-md"> 
                                                    <p class="team__member__number"><?php echo the_field('kwpe_number'); ?></p>
                                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/img/polygon.png" class="card__polygon" alt="">
                                                    <a href="<?php the_permalink(); ?>">
                                                        <svg class="arrow-link" xmlns="http://www.w3.org/2000/svg" width="21" height="12" viewBox="0 0 21 12">
                                                            <g fill="#000" fill-rule="evenodd" stroke="#000" stroke-linecap="square" stroke-width="2">
                                                                <path d="M1 6h18M15.082 2.082L19 6M15.082 9.918L19 6"/>
                                                            </g>
                                                        </svg>
                                                    </a>
                                                </div> 
                                            </div> 
                                        </div>                      
                                    </div>
                                </div>
                            
                            <?php endwhile; ?>
                        <?php wp_reset_postdata(); ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="grid-x">
                <!-- Team -->
                <?php 
                $args = array(  
                'post_type' => 'team',
                    'posts_per_page' => -1,
                    'orderby' => 'menu_order',
                    'tax_query' => array(
                        array (
                            'taxonomy' => 'position',
                            'field' => 'slug',
                            'terms' => 'team'
                        )
                    ),
                );

                $the_query = new WP_Query( $args ); ?>
                <?php if ( $the_query->have_posts() ) : ?>
                    <?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
                    <div class="cell large-3 medium-6">
                        <div class="team__member">
                            <div class="object-fit-img-wrap">
                                <?php echo the_post_thumbnail('portrait') ?>
                            </div>
                            <div class="team__member__body">
                                <div class="team__member__text__wrap">
                                    <a href="<?php the_permalink(); ?>">
                                        <h3 class="team__member__title blue"><?php the_title(); ?></h3>
                                    </a>
                                    <p class="team__member__position"><?php echo the_field('job_title'); ?></p>
                                
                                    <div class="team__member__footer mt-md"> 
                                        <p class="team__member__number"><?php echo the_field('kwpe_number'); ?></p>
                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/img/polygon.png" class="card__polygon" alt="">
                                        <a href="<?php the_permalink(); ?>">
                                            <svg class="arrow-link" xmlns="http://www.w3.org/2000/svg" width="21" height="12" viewBox="0 0 21 12">
                                                <g fill="#000" fill-rule="evenodd" stroke="#000" stroke-linecap="square" stroke-width="2">
                                                    <path d="M1 6h18M15.082 2.082L19 6M15.082 9.918L19 6"/>
                                                </g>
                                            </svg>
                                        </a>
                                    </div> 
                                </div> 
                            </div>                      
                        </div>
                    </div>
                    <?php endwhile; ?>
                <?php wp_reset_postdata(); ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <section class="testimonials blue-section padding">
        <div class="grid-container">
            <h2 class="testimonials__title"><?php echo the_field('testimonials_title'); ?></h2>

            <div class="testimonials__slider mt-sm">
                <?php if( have_rows('testimonial') ): ?>
                    <?php while( have_rows('testimonial') ): the_row(); 
                    
                        // vars
                        $testimonialText = get_sub_field('testimonial_text');
                        $testimonialCompany = get_sub_field('testimonial_company');
                        $testimonialName = get_sub_field('testimonial_name');
                        ?>

                            <div class="testimonial">
                                <h3 class="testimonials__text"><?php echo $testimonialText; ?></h3>
                                <span class="under-bar mt-md mb-md"></span>
                                <div class="testimonials__footer">
                                    <div>
                                        <p class="testimonials__company mb-xs"><?php echo $testimonialCompany; ?></p>
                                        <p class="testimonials__name"><?php echo $testimonialName; ?></p>
                                    </div>
                                </div>
                            </div>
                        
                    <?php endwhile; ?>
                <?php endif; ?>
            </div>
            <div class="testimonials__slider__controls">
                <a class="slick-prev testimonials__slider__prev btn btn--round">
                    <svg class="arrow-link" xmlns="http://www.w3.org/2000/svg" width="21" height="12" viewBox="0 0 21 12">
                        <g fill="#fff" fill-rule="evenodd" stroke="#fff" stroke-linecap="square" stroke-width="2">
                            <path d="M1 6h18M15.082 2.082L19 6M15.082 9.918L19 6"/>
                        </g>
                    </svg>
                    <span>Previous</span>
                </a>
                <a class="slick-next testimonials__slider__next btn btn--round">
                    <span>Next</span>
                    <svg class="arrow-link" xmlns="http://www.w3.org/2000/svg" width="21" height="12" viewBox="0 0 21 12">
                        <g fill="#fff" fill-rule="evenodd" stroke="#fff" stroke-linecap="square" stroke-width="2">
                            <path d="M1 6h18M15.082 2.082L19 6M15.082 9.918L19 6"/>
                        </g>
                    </svg>
                </a>
            </div>
        </div>
    </section>

</div>


<?php get_footer(); ?>