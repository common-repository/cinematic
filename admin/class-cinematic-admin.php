<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://cinematic.bukza.com
 * @since      1.0.0
 *
 * @package    Cinematic
 * @subpackage Cinematic/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and hooks.
 *
 * @package    Cinematic
 * @subpackage Cinematic/admin
 * @author     Bukza <support@bukza.com>
 */
class Cinematic_Admin {

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
	 * @since 1.0.0
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Adds scripts for the admin area.
	 *
	 * @since    1.0.0
	 * @param string $hook The name of the page.
	 */
	public function enqueue_scripts( $hook ) {

		if ( 'toplevel_page_cinematic' !== $hook ) {
			return;
		}

		// Styles.
		wp_enqueue_style( 'Josefin-Sans-400-700-Muli', '//fonts.googleapis.com/css?family=Josefin+Sans:400,700|Muli', array(), '1.0.0', 'all' );
		wp_enqueue_style( 'font-awesome-free', '//use.fontawesome.com/releases/v5.3.1/css/all.css', array(), '5.3.1', 'all' );
		wp_enqueue_style( 'pure', plugin_dir_url( __FILE__ ) . 'css/pure-min.css', array(), '1.0.0', 'all' );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/cinematic-admin.css', array(), $this->version, 'all' );

		// Scripts.
		wp_enqueue_media();
		wp_enqueue_script( 'vue', plugin_dir_url( __FILE__ ) . 'js/vue.min.js', array(), '2.5.17', true );
		wp_enqueue_script( 'vue-resource', plugin_dir_url( __FILE__ ) . 'js/vue-resource.min.js', array( 'vue' ), '1.5.1', true );
		wp_enqueue_script( 'filesaver', plugin_dir_url( __FILE__ ) . 'js/filesaver.js', array(), '1.3.8', true );
		wp_enqueue_script( 'library', plugin_dir_url( __FILE__ ) . 'js/library.js', array(), '1.0.0', true );
		wp_enqueue_script( 'cinematic', plugin_dir_url( __FILE__ ) . 'js/cinematic.min.js', array(), '1.0.0', true );

		wp_register_script( 'cinematic-admin', plugin_dir_url( __FILE__ ) . 'js/cinematic-admin.js', array( 'jquery', 'wp-api', 'vue', 'filesaver', 'library', 'cinematic' ), $this->version, true );

		wp_localize_script(
			'cinematic-admin',
			'wpData',
			array(
				'image_url' => esc_url( plugin_dir_url( __FILE__ ) . 'images' ),
				'rest_url'  => untrailingslashit( esc_url_raw( rest_url() ) ),
				'nonce'     => wp_create_nonce( 'wp_rest' ),
			)
		);

		wp_enqueue_script( 'cinematic-admin' );

	}

	/**
	 * Adds options page.
	 *
	 * @since    1.0.0
	 */
	public function add_menu_item() {

		add_menu_page( esc_html__( 'Cinematic', 'cinematic' ), esc_html__( 'Cinematic', 'cinematic' ), 'manage_options', 'cinematic', 'Cinematic_Admin::menu_page', 'data:image/svg+xml;base64,PCFET0NUWVBFIHN2ZyBQVUJMSUMgIi0vL1czQy8vRFREIFNWRyAyMDAxMDkwNC8vRU4iICJodHRwOi8vd3d3LnczLm9yZy9UUi8yMDAxL1JFQy1TVkctMjAwMTA5MDQvRFREL3N2ZzEwLmR0ZCI+CjxzdmcgdmVyc2lvbj0iMS4wIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI3NjhweCIgaGVpZ2h0PSI3NjhweCIgdmlld0JveD0iMCAwIDc2ODAgNzY4MCIgcHJlc2VydmVBc3BlY3RSYXRpbz0ieE1pZFlNaWQgbWVldCI+CjxnIGlkPSJsYXllcjEwMSIgZmlsbD0iI2ZmZmZmZiIgc3Ryb2tlPSJub25lIj4KIDxwYXRoIGQ9Ik0xODAwIDY1NTUgbDAgLTEyNSAyMzE1IDAgMjMxNSAwIDAgLTE4MTUgMCAtMTgxNSAxMjUgMCAxMjUgMCAwIDE5NDAgMCAxOTQwIC0yNDQwIDAgLTI0NDAgMCAwIC0xMjV6Ii8+CiA8cGF0aCBkPSJNMTQwMCA2MDU1IGwwIC0xMjUgMjI2NSAwIDIyNjUgMCAwIC0xNzY1IDAgLTE3NjUgMTI1IDAgMTI1IDAgMCAxODkwIDAgMTg5MCAtMjM5MCAwIC0yMzkwIDAgMCAtMTI1eiIvPgogPHBhdGggZD0iTTEwMDAgMzg0MCBsMCAtMTg0MCAyMzQwIDAgMjM0MCAwIDAgMTg0MCAwIDE4NDAgLTIzNDAgMCAtMjM0MCAwIDAgLTE4NDB6IG0xOTYyIDg1OSBjMTc5IC0xMzEgMzM2IC0yNDIgMzQ4IC0yNDkgMTEgLTYgMjkgLTkgMzkgLTUgOSAzIDE3MiAxMTggMzYxIDI1NSAxODkgMTM3IDM0NSAyNDggMzQ3IDI0NiAzIC0yIC01NCAtMTg0IC0xMjYgLTQwMyAtNzIgLTIyMCAtMTMxIC00MDcgLTEzMSAtNDE1IDAgLTkgMyAtMjMgNiAtMzIgMyAtOCAxNjMgLTEyOSAzNTQgLTI2OCBsMzQ5IC0yNTMgLTQzNCAtNSBjLTQwNiAtNSAtNDM0IC02IC00NTQgLTI0IC0xOSAtMTcgLTY0IC0xNDcgLTI0NiAtNzE0IC0yMCAtNjMgLTM5IC0xMTIgLTQxIC0xMDkgLTMgMyAtNjMgMTg0IC0xMzQgNDAzIC04NCAyNjAgLTEzNiA0MDUgLTE1MCA0MTggLTIwIDIwIC0zMiAyMSAtNDU1IDI2IGwtNDM0IDUgMzMyIDI0MCBjMTgyIDEzMiAzNDIgMjUwIDM1NCAyNjIgMTMgMTIgMjMgMzIgMjMgNDQgMCAxMyAtNTggMjAxIC0xMzAgNDIwIC03MSAyMTggLTEzMCA0MDAgLTEzMCA0MDQgMCA5IC0zIDExIDM1MiAtMjQ2eiIvPgogPHBhdGggZD0iTTY0MzAgMjIwMCBsMCAtNDAwIDEyNSAwIDEyNSAwIDAgNDAwIDAgNDAwIC0xMjUgMCAtMTI1IDAgMCAtNDAweiIvPgogPHBhdGggZD0iTTU5MzAgMTgwMCBsMCAtNDAwIDEyNSAwIDEyNSAwIDAgNDAwIDAgNDAwIC0xMjUgMCAtMTI1IDAgMCAtNDAweiIvPgogPHBhdGggZD0iTTE5NDUgMTQwMCBsNDAwIC00MDAgMzEwIDAgMzEwIDAgLTQwMCA0MDAgLTQwMCA0MDAgLTMxMCAwIC0zMTAgMCA0MDAgLTQwMHoiLz4KIDxwYXRoIGQ9Ik0zMTE1IDE0MDAgbDQwMCAtNDAwIDMxMCAwIDMxMCAwIC00MDAgNDAwIC00MDAgNDAwIC0zMTAgMCAtMzEwIDAgNDAwIC00MDB6Ii8+CiA8cGF0aCBkPSJNNDI4NSAxNDAwIGw0MDAgLTQwMCAzMTAgMCAzMTAgMCAtNDAwIDQwMCAtNDAwIDQwMCAtMzEwIDAgLTMxMCAwIDQwMCAtNDAweiIvPgogPHBhdGggZD0iTTUzNjUgMTQ5MCBjMTcwIC0xNzAgMzExIC0zMTAgMzEyIC0zMTAgMiAwIDMgMTQwIDMgMzEwIGwwIDMxMCAtMzEyIDAgLTMxMyAwIDMxMCAtMzEweiIvPgogPHBhdGggZD0iTTEwMDAgMTM5NSBsMCAtMzk1IDM5NyAwIDM5OCAwIC0zOTUgMzk1IGMtMjE3IDIxNyAtMzk2IDM5NSAtMzk3IDM5NSAtMiAwIC0zIC0xNzggLTMgLTM5NXoiLz4KIDwvZz4KCjwvc3ZnPg==' );

	}

	/**
	 * Adds menu page.
	 *
	 * @since    1.0.0
	 */
	public static function menu_page() {

		include plugin_dir_path( __FILE__ ) . 'partials/cinematic-admin-display.php';

	}

	/**
	 * Registers custom post type for Cinematic.
	 *
	 * @since    1.0.0
	 */
	public function register_custom_post_type() {

		$labels = array(
			'name'          => esc_html__( 'Cinematic Sliders', 'cinematic' ),
			'singular_name' => esc_html__( 'Cinematic Slider', 'cinematic' ),
		);

		$args = array(
			'labels'       => $labels,
			'description'  => esc_html__( 'Cinematic 3D Photo Slider', 'cinematic' ),
			'public'       => false,
			'supports'     => array( 'title', 'editor', 'custom-fields' ),
			'show_in_rest' => true,
		);

		register_post_type( 'cinematic_slider', $args );

	}

	/**
	 * Resgisters routes.
	 *
	 * @since    1.0.0
	 */
	public function init_rest() {

		require_once plugin_dir_path( __FILE__ ) . 'class-cinematic-rest.php';
		register_rest_route(
			'cinematic/v1',
			'/processMedia',
			array(
				'methods'             => 'POST',
				'callback'            => 'Cinematic_Rest::process_media',
				'permission_callback' => 'Cinematic_Rest::permissions_check',
			)
		);

		register_rest_route(
			'cinematic/v1',
			'/deleteAll',
			array(
				'methods'             => 'DELETE',
				'callback'            => 'Cinematic_Rest::delete_all',
				'permission_callback' => 'Cinematic_Rest::permissions_check',
			)
		);

		register_rest_field(
			'cinematic_slider',
			'cinematic_settings',
			array(
				'get_callback'    => function ( $object, $field_name, $request ) {
					return get_post_meta( $object['id'], $field_name );
				},
				'update_callback' => function ( $value, $object, $field_name ) {
					if ( ! $value || ! is_string( $value ) ) {
						return;
					}
					return update_post_meta( $object->ID, $field_name, wp_slash( $value ) );
				},
				'schema'          => null,
			)
		);

	}

	/**
	 * Handler for post removal.
	 *
	 * @since    1.0.0
	 * @param string $post_id Id of removed post.
	 */
	public function post_removed( $post_id ) {

		if ( 'cinematic_slider' !== get_post_type( $post_id ) ) {
			return true;
		}

		$media = get_children(
			array(
				'post_parent' => $post_id,
				'post_type'   => 'attachment',
			)
		);

		if ( empty( $media ) ) {
			return;
		}

		foreach ( $media as $file ) {
			wp_delete_attachment( $file->ID );
		}
	}
}
