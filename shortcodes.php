<?php namespace feedthemsocial;

/**
 * Shortcodes for Feed Them Social
 */
class Shortcodes extends Gallery {

	/**
	 * Shortcodes constructor.
	 */
	public function __construct(){ }

	/**
	 * Gallery Album Shortcode
	 *
	 * @param $atts
	 * @since 1.0.8
	 */
	public function fts_album( $atts ) {

		$shortcode_atts = shortcode_atts(
			array(
				// All We need is ID of Gallery Post the rest will be passed through a rest call!
				'id' => '',
			),
			$atts
		);

		$album_gallery_ids = get_post_meta( $shortcode_atts['id'], 'fts_album_gallery_ids', true );

		if ( isset( $album_gallery_ids ) && ! empty( $album_gallery_ids ) ) {

			$albums_class = new Albums();

			?>
			<div class="ftg-album-wrap">
			<?php

			foreach ( $album_gallery_ids as $key => $gallery ) {

				$gallery_meta = get_post( $gallery );

				if ( $gallery_meta ) {
					$gallery_img_url = $albums_class->gallery_featured_first( $gallery_meta->ID );

					$gallery_edit_url          = get_edit_post_link( $gallery_meta->ID );
					$gallery_post_link         = get_post_permalink( $gallery_meta->ID );
					$gallery_attachments_count = $albums_class->fts_count_gallery_attachments( $gallery_meta->ID );
					$attachments_count         = ! empty( $gallery_attachments_count ) ? '(' . $gallery_attachments_count . ')' : '';
					?>
					<div class="ftg-album-item-wrap">
						<a href="<?php echo esc_url( $gallery_post_link ); ?>"><img src="<?php echo esc_url( $gallery_img_url ); ?>"/></a>
						<a href="<?php echo esc_url( $gallery_post_link ); ?>"><span><?php echo esc_html( $gallery_meta->post_title ); ?> <?php echo esc_html( $attachments_count ); ?></span></a>
					</div>
					<?php
				}
			}
			?>
			</div>
			<?php
		} else {
			echo esc_html__( 'No Galleries in this Album. Please attach Galleries to use this feature', 'feed_them_social' );
		}
	}
}
