<?php
/**
 * Fired during plugin activation
 *
 * @link       https://cinematic.bukza.com
 * @since      1.0.0
 *
 * @package    Cinematic
 * @subpackage Cinematic/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Cinematic
 * @subpackage Cinematic/includes
 * @author     Bukza <support@bukza.com>
 */
class Cinematic_Activator {

	/**
	 * Activates plugin.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		$cpt_post = get_page_by_title( esc_html__( 'Example', 'cinematic' ), 'OBJECT', 'cinematic_slider' );
		if ( is_null( $cpt_post ) ) {
			$sample_post = array(
				'post_title'   => esc_html__( 'Example', 'cinematic' ),
				'post_content' => '    <div class="cinematic cinematic-inactive" id="cinematicsliderExample">
				<figure><div data-height="50%" data-zoom="2" data-timing="ease-out" data-duration="5">
					<img src="' . esc_url( plugin_dir_url( __FILE__ ) ) . '../admin/images/slide1/1.jpg"  data-distance="0.8" />
					<img src="' . esc_url( plugin_dir_url( __FILE__ ) ) . '../admin/images/slide1/2.png"  data-distance="0.6" />
					<img src="' . esc_url( plugin_dir_url( __FILE__ ) ) . '../admin/images/slide1/3.png"  data-distance="0.4" />
					<img src="' . esc_url( plugin_dir_url( __FILE__ ) ) . '../admin/images/slide1/4.png"  data-distance="0.2" />
				</div></figure>
				<figure><div data-height="50%" data-zoom="2" data-timing="ease-out" data-duration="5">
					<img src="' . esc_url( plugin_dir_url( __FILE__ ) ) . '../admin/images/slide2/1.jpg"  data-distance="0.6" />
					<img src="' . esc_url( plugin_dir_url( __FILE__ ) ) . '../admin/images/slide2/2.png"  data-distance="0.4" />
					<img src="' . esc_url( plugin_dir_url( __FILE__ ) ) . '../admin/images/slide2/3.png"  data-distance="0.2" />
				</div></figure>
				<figure><div data-height="50%" data-zoom="2" data-timing="ease-out" data-duration="5">
					<img src="' . esc_url( plugin_dir_url( __FILE__ ) ) . '../admin/images/slide3/1.jpg"  data-distance="0.7" />
					<img src="' . esc_url( plugin_dir_url( __FILE__ ) ) . '../admin/images/slide3/2.png"  data-distance="0.6" />
					<img src="' . esc_url( plugin_dir_url( __FILE__ ) ) . '../admin/images/slide3/3.png"  data-distance="0.4" />
					<img src="' . esc_url( plugin_dir_url( __FILE__ ) ) . '../admin/images/slide3/4.png"  data-distance="0.3" />
				</div></figure>
			</div>
			<script>
				document.addEventListener(\'DOMContentLoaded\',
					function () {
						new Cinematic(document.getElementById(\'cinematicsliderExample\'), { dots: true, speed: 4000 });
					});
			</script>',
				'post_status'  => 'publish',
				'post_type'    => 'cinematic_slider',
			);

			$cpt_id = wp_insert_post( $sample_post );

			update_post_meta( $cpt_id, 'cinematic_settings', '{"height":"","dots":true,"slides":[{"id":1,"zoom":2,"duration":5,"timing":"ease-out","items":[{"isText":false,"id":1,"url":"' . esc_url( plugin_dir_url( __FILE__ ) ) . '../admin/images/slide1/1.jpg","distance":0.8,"left":"","top":"","width":"","height":""},{"isText":false,"id":2,"url":"' . esc_url( plugin_dir_url( __FILE__ ) ) . '../admin/images/slide1/2.png","distance":0.6,"left":"","top":"","width":"","height":""},{"isText":false,"id":3,"url":"' . esc_url( plugin_dir_url( __FILE__ ) ) . '../admin/images/slide1/3.png","distance":0.4,"left":"","top":"","width":"","height":""},{"isText":false,"id":4,"url":"' . esc_url( plugin_dir_url( __FILE__ ) ) . '../admin/images/slide1/4.png","distance":0.2,"left":"","top":"","width":"","height":""}]},{"id":2,"zoom":2,"duration":5,"timing":"ease-out","items":[{"isText":false,"id":1,"url":"' . esc_url( plugin_dir_url( __FILE__ ) ) . '../admin/images/slide2/1.jpg","distance":0.6,"left":"","top":"","width":"","height":""},{"isText":false,"id":2,"url":"' . esc_url( plugin_dir_url( __FILE__ ) ) . '../admin/images/slide2/2.png","distance":0.4,"left":"","top":"","width":"","height":""},{"isText":false,"id":3,"url":"' . esc_url( plugin_dir_url( __FILE__ ) ) . '../admin/images/slide2/3.png","distance":0.2,"left":"","top":"","width":"","height":""}]},{"id":3,"zoom":2,"duration":5,"timing":"ease-out","items":[{"isText":false,"id":1,"url":"' . esc_url( plugin_dir_url( __FILE__ ) ) . '../admin/images/slide3/1.jpg","distance":0.7,"left":"","top":"","width":"","height":""},{"isText":false,"id":2,"url":"' . esc_url( plugin_dir_url( __FILE__ ) ) . '../admin/images/slide3/2.png","distance":0.6,"left":"","top":"","width":"","height":""},{"isText":false,"id":3,"url":"' . esc_url( plugin_dir_url( __FILE__ ) ) . '../admin/images/slide3/3.png","distance":0.4,"left":"","top":"","width":"","height":""},{"isText":false,"id":4,"url":"' . esc_url( plugin_dir_url( __FILE__ ) ) . '../admin/images/slide3/4.png","distance":0.3,"left":"","top":"","width":"","height":""}]}]}' );

		}
	}

}
