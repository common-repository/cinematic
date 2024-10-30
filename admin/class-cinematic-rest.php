<?php
/**
 * Rest methods implementations.
 *
 * @link       https://cinematic.bukza.com
 * @since      1.0.0
 *
 * @package    Cinematic
 * @subpackage Cinematic/admin
 */

/**
 * Rest methods implementations.
 *
 * @package    Cinematic
 * @subpackage Cinematic/admin
 * @author     Bukza <support@bukza.com>
 */
class Cinematic_Rest {
	/**
	 * Uploads media files to gallery and returns links to files
	 *
	 * @since    1.0.0
	 * @param string $data      Input data.
	 */
	public static function process_media( $data ) {

		require_once ABSPATH . 'wp-admin/includes/image.php';
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/media.php';

		$result = array();
		foreach ( $data['urls'] as $url ) {
			array_push( $result, media_sideload_image( $url, $data['id'], 'cinematic_media', 'src' ) );
		}
		return $result;

	}

	/**
	 * Deletes all slider posts
	 *
	 * @since    1.0.0
	 */
	public static function delete_all() {

		$allposts = get_posts(
			array(
				'post_type'   => 'cinematic_slider',
				'numberposts' => -1,
			)
		);

		foreach ( $allposts as $eachpost ) {
			wp_delete_post( $eachpost->ID, true );
		}

	}

	/**
	 * Check if a given request has access
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 * @return WP_Error|bool
	 */
	public static function permissions_check( $request ) {
		return current_user_can( 'edit_posts' );
	}
}
