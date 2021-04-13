<?php
/**
 * Template Name: archive-ftg-tags
 *
 * @link https://feedthemgallery.com/
 *
 * @package Feed Them Social
 * @since 1.1.6
 * @version 1.1.6
 */

get_header();

$gallery_tag      = isset( $_GET['ftg-tags'] ) ? sanitize_text_field( wp_unslash( $_GET['ftg-tags'] ) ) : null;
$image_or_gallery = isset( $_GET['ftg-tags'], $_GET['type'] ) && 'page' === $_GET['type'] ? 'Galleries' : 'Images'; ?>

	<div id="top">
		<div class="title_container">
			<div class="page-header container">
				<div class="ftg-displaying-wrap">Displaying <?php echo esc_html( $image_or_gallery ); ?> with the tag:
										<?php
										echo esc_html( $gallery_tag )
										?>
				</div>
				<?php


				$option = get_option( 'template_settings_page_settings_options' );

				if ( ! empty( $option['fts_tags_search_select'] ) && 'yes' === $option['fts_tags_search_select'] ) {
					?>
					<div class="ftg-tags-list-wrap">
									<?php
									$taxonomies = get_terms(
										array(
											'taxonomy'   => 'ftg-tags',
											'hide_empty' => false,
										)
									);

									// echo '<pre>';
									// print_r( $taxonomies );
									// echo '</pre>';.
										echo '<select onChange="window.document.location.href=this.options[this.selectedIndex].value;">';

									foreach ( $taxonomies as $category ) {

										if ( 0 !== $category->count ) {
											$ftg_category_count_final = $category->count;
											$ftg_option_selected      = $gallery_tag == $category->slug ? 'selected' : '';
											$ftg_category_count       = 1 !== $category->count ? ' (' . $ftg_category_count_final . ')' : '';
											$ftg_url_count            = $category->count > 1 ? '&count=' . $ftg_category_count_final . '' : '';

													echo '<option ' . esc_attr( $ftg_option_selected ) . ' value="' . esc_url( get_site_url() . '?type=image&ftg-tags=' . esc_attr( $category->slug . $ftg_url_count ) ) . '">' . esc_html( $category->name ) . esc_html( $ftg_category_count ) . '</option>';

										}
									}
										echo '</select>';
									?>
					</div>
				<?php } ?>

			</div><!-- .page-header -->
		</div>
	</div>

	<div id="primary" class="content-area container">
		<main id="main" class="site-main" role="main">
			<!-- leaving this search option for later use -->
			<div class="ftg-search-wrap" style="display: none;">
				<form id="ftg-tax-search" method="GET" action="">
					<input name="type" type="hidden" value="image">
					<input name="ftg-tags" type="text" class="ftg-text-search" placeholder="Search for tags">
					<input type="submit" value="Search" id="ftg-submit-search">
				</form>
			</div>
			<div class="ft-gallery-clear"></div>

			<?php
			// Still need to create a select option that shows all the galleries in the list and defaults to the first one below if no gallery id selected.
			if ( ! empty( $gallery_tag ) ) {

				print do_shortcode( '[feed_them_social id=tags]' );
			}
			?>
		</main>
		<!-- #main -->
	</div>
	<!-- #primary -->

<?php
get_footer();

