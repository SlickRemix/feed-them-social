<?php
/**
 * Template Name: Feed Them Social Template
 *
 * @link https://feedthemgallery.com/
 *
 * @package Feed Them Social
 * @since 1.1.6
 * @version 1.1.6
 */

get_header(); ?>

<?php
while ( have_posts() ) :
	the_post();
	?>

	<div id="top">
		<div class="title_container">
			<div class="page-header container">
				<h2>
				<?php
				the_title();
				?>
				</h2>
			</div><!-- .page-header -->
		</div>
	</div>

	<div id="primary" class="content-area container">
		<main id="main" class="site-main" role="main">
			<?php

			global $post;

			$gallery_id = $post->ID;
			if ( ! empty( $gallery_id ) ) {
				print do_shortcode( '[feed_them_social id="' . esc_html( $gallery_id ) . '"]' );
			}

			?>
		</main>
		<!-- #main -->
	</div>
	<!-- #primary -->
<?php endwhile;  // End of the loop. ?>

<?php
get_footer();
