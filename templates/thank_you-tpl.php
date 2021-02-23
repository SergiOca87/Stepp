<?php
/**
* Template Name: Thank You
*/
?>

<?php get_header(); ?>

<?php while (have_posts()): the_post(); ?>
<section class="min-view">
	<h2><?php the_title(); ?></h2>
	
	<div class="entry-content">
		<p class="blue">The form was submitted successfully.</p>
	</div><!-- .entry-content -->
	
</section>
<?php endwhile; ?>

<?php get_footer(); ?>