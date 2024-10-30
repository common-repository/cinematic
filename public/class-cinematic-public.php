<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://cinematic.bukza.com
 * @since      1.0.0
 *
 * @package    Cinematic
 * @subpackage Cinematic/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Cinematic
 * @subpackage Cinematic/public
 * @author     Bukza <support@bukza.com>
 */
class Cinematic_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param string $plugin_name The name of the plugin.
	 * @param string $version     The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function register_script() {

		wp_register_script( 'cinematic', plugin_dir_url( __FILE__ ) . 'js/cinematic.min.js', array(), '1.0.0', true );

	}

	/**
	 * Adds short code
	 *
	 * @since    1.0.0
	 */
	public function add_shortcode() {

		add_shortcode( 'cinematic', 'Cinematic_Public::cinematic_shortcode_function' );

	}

	/**
	 * Short code creation
	 *
	 * @since    1.0.0
	 * @param string $atts Short code attributes.
	 */
	public static function cinematic_shortcode_function( $atts ) {

		wp_enqueue_script( 'cinematic' );
		return get_post_field( 'post_content', $atts['id'] );

	}
}
