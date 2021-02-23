<?php
/**
* Template Name: Contact
*/
?>

<?php get_header(); ?>

<div class="contact">
    <div class="contact__hero" style="background: url( <?php echo the_field('contact_hero_image'); ?> ) "></div>

    <section>
        <div class="grid-container mt-md mb-md">
            <div class="grid-x grid-margin-x grid-margin-y align-center contact__cards__wrap">

                <?php if( have_rows('contact_card') ): 
                    while( have_rows('contact_card') ): the_row(); 

                        // vars
                        $title = get_sub_field('contact_card_title');
                        $address = get_sub_field('contact_card_address');
                        $directPhone = get_sub_field('contact_card_direct');
                        $cellPhone = get_sub_field('contact_card_cell');
                        $email = get_sub_field('contact_card_email');
                        ?>



                        <div class="cell large-4">
                            <div class="contact__card">
                                <h3 class="contact__card__title mb-xs blue"><?php echo $title; ?></h3>
                                <p class="contact__card__address mb-md"><?php echo $address ?></p>
                                <div class="grid-x small-up-2 large-up-2 mb-md">
                                    <?php $replaceCases = array(" ", "(", ")", "-", "."); ?>
                                    <?php if( $directPhone ) : ?>
                                        <div class="cell no-border">
                                            <h4 class="contact__card__inner__title fade-text">Direct</h1>
                                            <a href="tel:<?php echo str_replace( $replaceCases, "", $directPhone ); ?>" class="contact__card__phone"><?php echo $directPhone ?></a>
                                        </div>
                                    <?php endif; ?>
                                    <?php if( $cellPhone ) : ?>
                                        <div class="cell">
                                            <h4 class="contact__card__inner__title fade-text">Cell</h1>
                                            <a href="tel:<?php echo str_replace( $replaceCases, "", $cellPhone ); ?>" class="contact__card__phone"><?php echo $cellPhone ?></a>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <?php if( $email ) : ?>
                                    <div class="mb-md">
                                        <h4 class="contact__card__inner__title fade-text">Email</h1>
                                        <a href="mailto:<?php echo $email ?>" class="contact__card__email light-blue"><?php echo $email ?></a>
                                    </div>
                                <?php endif; ?>

                                <div>
                                    <a class="contact__card__link blue" href="https://www.google.com/maps/place/<?php echo str_replace( '#', "", $address ); ?>" target="_blank">Show on the Map</a>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php endif; ?>
        
            </div>
        </div>
    </section>

    <section class="blue-section padding">
        <div class="grid-container">
            <div class="grid-x grid-margin-x grid-margin-y align-center">
                <div class="cell large-6">
                    <div class="contact__form__cell">
                        <h1 class="contact__title white"><?php echo the_field('contact_form_title'); ?></h1>
                        <span class="under-bar"></span>
                        <p class="contact__text white mb-lg"><?php echo the_field('contact_form_subtitle'); ?></p>
                        <div class="contact__button__wrap">
                            <a href="mailto:<?php echo the_field('contact_email'); ?>" class="btn btn--round white"><?php echo the_field('contact_button_text'); ?></a>
                        </div>
                    </div>
                </div>
                <div class="cell large-6 contact__form__wrap ">
                    <div class="contact__form__cell">
                        <div>
                            <span class="under-bar"></span>
                            <p class="white"><?php echo the_field('contact_form_text'); ?></p>
                        </div>
                        <div class="mt-md">
                            <!-- <?php echo do_shortcode(get_field('contact_form_shortcode')); ?> -->
                            <?php echo do_shortcode('[contact-form-7 id="1980" title="Contact Form"]'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php get_footer(); ?>