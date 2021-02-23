<?php get_header(); ?>

<?php while (have_posts()): the_post(); ?>

<div class="team-single">

    <section>
        <div class="grid-container">
            <div class="grid-x grid-margin-x grid-margin-y align-middle mt-md">

                <div class="cell large-6 text-center large-order-2">
                    <div class="team-single__image">
                        <?php echo the_post_thumbnail('full'); ?>
                    </div>
                </div>

                <div class="cell large-6 padding large-order-1">
                    <div class="team-single__details__wrap">
                        <div class="team-single__title__wrap">
                            <h1 class="team-single__title blue"><?php the_title(); ?></h1>
                            <h2 class="team-single__position mb-xs"><?php echo the_field('job_title'); ?></h2>
                        </div>
                        <div class="team-single__details">

                        <div class="grid-x small-up-2 large-up-2">
                            <?php if(get_field('phone')) : ?>
                                <div class="cell">
                                    <h4 class="team-single__details__title">Office</h4>
                                    <a href="tel:<?php echo the_field('phone'); ?>" class="team-single__details__text"><?php echo the_field('phone'); ?></a>
                                </div>
                            <?php endif; ?>
                            <?php if(get_field('location')) : ?>
                                <div class="cell">
                                    <h4 class="team-single__details__title">Location</h4>
                                    <p class="team-single__details__text"><?php echo the_field('location'); ?></p>
                                </div>
                            <?php endif; ?>
                            <?php if(get_field('cell')) : ?>
                                <div class="cell">
                                    <h4 class="team-single__details__title">Cell</h4>
                                    <a href="tel:<?php echo the_field('cell'); ?>" class="team-single__details__text"><?php echo the_field('cell'); ?></a>
                                </div>
                            <?php endif; ?>
                            <?php if(get_field('license')) : ?>
                                <div class="cell">
                                    <h4 class="team-single__details__title">CA DRE LICENSE</h4>
                                    <p class="team-single__details__text"><?php echo the_field('license'); ?></p>
                                </div>
                            <?php endif; ?>

                            <!-- Extra fields -->
                            <?php if( have_rows('extra_fields') ): ?>
                                <?php while( have_rows('extra_fields') ): the_row(); 

                                    // vars
                                    $extraFieldTitle = get_sub_field('extra_field_name');
                                    $extraFieldText = get_sub_field('extra_field_value');

                                    ?>
                                    <div class="cell">
                                        <h4 class="team-single__details__title"><?php echo $extraFieldTitle; ?></h1>
                                        <p class="team-single__details__text"><?php echo $extraFieldText; ?></p>
                                    </div>

                                <?php endwhile; ?>
                            <?php endif; ?>
                        </div>

                            <?php if(get_field('email')) : ?>
                                <h4 class="team-single__details__title">Email</h4>
                                <a href="mailto:<?php echo the_field('email'); ?>" class="team-single__details__email"><?php echo the_field('email'); ?></a>
                            <?php endif; ?>
                        
                            <div class="team-single__details__buttons mt-md">
                                <?php if(get_field('email')) : ?>
                                    <a href="mailto:<?php echo the_field('email'); ?>"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/mail-icon.svg" alt="mail icon"> SEND MESSAGE</a>
                                <?php endif; ?>
                                <?php if(get_field('linkedin')) : ?>
                                    <a href="<?php the_field('linkedin'); ?>" target="_blank"> <img src="<?php echo get_template_directory_uri(); ?>/assets/img/linkedin-white.svg" alt="mail icon"> CONNECT</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section class="team-single__about padding">
        <div class="grid-container">

            <?php
                $title = get_the_title();
                $arr = explode(' ',trim($title));
            ?>
            <h2 class="team-single__about__title">About <?php echo $arr[0]; ?></h2>
            <div class="team-single__about__text"><?php echo the_content(); ?></div>
        </div>
    </section>

    <div class="team-single__controls">
        <div class="testimonials__slider__controls">
            <a href="<?php echo get_permalink( get_adjacent_post( false, '', false ) ); ?>" class="slick-prev testimonials__slider__prev btn btn--round">
                <svg class="arrow-link" xmlns="http://www.w3.org/2000/svg" width="21" height="12" viewBox="0 0 21 12">
                    <g fill="#fff" fill-rule="evenodd" stroke="#fff" stroke-linecap="square" stroke-width="2">
                        <path d="M1 6h18M15.082 2.082L19 6M15.082 9.918L19 6"/>
                    </g>
                </svg>
                <span>Previous</span>
            </a>
            <a href="<?php echo get_permalink( get_adjacent_post( false, '', true ) ); ?>" class="slick-next testimonials__slider__next btn btn--round">
                <span>Next</span>
                <svg class="arrow-link" xmlns="http://www.w3.org/2000/svg" width="21" height="12" viewBox="0 0 21 12">
                    <g fill="#fff" fill-rule="evenodd" stroke="#fff" stroke-linecap="square" stroke-width="2">
                        <path d="M1 6h18M15.082 2.082L19 6M15.082 9.918L19 6"/>
                    </g>
                </svg>
            </a>
        </div>
    </div>
	
</div>

<?php endwhile; ?>

<?php get_footer(); ?>