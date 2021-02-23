<?php
/**
 * The header for our theme.
 *
 * @package Minikit
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="profile" href="http://gmpg.org/xfn/11">
		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">	
		<?php wp_head(); ?>
	</head>
	
	<body <?php body_class(); ?>>

		<div class="modal">
			<div class="modal__inner__container">
				<div class="modal__close insights-toggle">✕</div>
				<div class="modal__text__wrap text-center">
					<h3 class="mb-sm blue"><?php echo the_field('modal_title', 'option'); ?></h3> 
					<?php if( get_field('modal_subtitle', 'option')) : ?>
						<p class="mb-md"><?php echo the_field('modal_subtitle', 'option'); ?></p>
					<?php endif; ?>
					<hr>
					<div class="modal__form mt-lg">
						<div class="modal__form__wrap">
							<?php echo do_shortcode('[contact-form-7 id="889" title="Insights Form"]'); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	
		<!--HEADER-->
		<header class="site-header" role="banner">

			<div class="grid-container max-width site-header__container">
				<div class="grid-x show-for-large">
					<div class="site-header__logo-wrap">

						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" id="header-logo" rel="home">
							<?php if ( is_front_page() ) :  ?>
								<img src="<?php echo get_template_directory_uri(); ?>/assets/img/logo-white.png" alt="Stepp Logo">
							<?php else : ?>
								<img src="<?php echo get_template_directory_uri(); ?>/assets/img/logo-blue.png" alt="Stepp Logo">
							<?php endif; ?>
						</a>	

						<nav id="navigation" class="main-navigation show-for-large" role="navigation">
							<?php wp_nav_menu( array( 'theme_location' => 'primary', 'container' => false ) ); ?>
						</nav><!-- #navigation -->
						<a class="show-for-large site-header__button btn btn--round white insights-toggle" href="#"><?php echo the_field('modal_button_text', 'option'); ?></a>			
					</div>
				</div>
				<div class="menu-handle__wrap hide-for-large">
					<a href="#" class="menu-handle hide-for-large"><span></span> Menu</a>
					<a class="hide-for-large site-header__button btn btn--green-transparent insights-toggle" href="#"><?php echo the_field('modal_button_text', 'option'); ?></a>
				</div>
				<div class="site-header__logo-wrap hide-for-large">
					
					<?php if ( is_front_page() ) :  ?>
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" id="header-logo" class="mobile-logo" rel="home">
							<img src="<?php echo get_template_directory_uri(); ?>/assets/img/mobile-white-logo.png" alt="Stepp Logo">
						</a>
					<?php endif; ?>
						
				</div>
			</div>
		</header>
		<div class="menu-handle__wrap hide-for-large homepage__menu__handle">
			<a href="#" class="menu-handle hide-for-large"><span></span> Menu</a>
		</div>
		<nav id="navigation-mobile" class="main-navigation mobile-navigation-menu hide-for-large" role="navigation">
			<?php wp_nav_menu( array( 'theme_location' => 'primary', 'container' => false ) ); ?>
			<a class="black hide-for-large site-header__button site-header__mobile__button btn btn--round white insights-toggle" href="#"><?php echo the_field('modal_button_text', 'option'); ?></a>
		</nav>
		<?php if ( !is_front_page() ) :  ?>
			<div class="site-header-mobile-logo-wrap mt-md mb-md hide-for-large">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" id="header-logo" class="mobile-logo" rel="home">
					<img src="<?php echo get_template_directory_uri(); ?>/assets/img/mobile-black-logo.png" alt="Stepp Logo">
				</a>
			</div>
		<?php endif; ?>
		