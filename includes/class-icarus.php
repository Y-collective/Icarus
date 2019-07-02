<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://y-collective.com
 * @since      1.0.0
 *
 * @package    Icarus
 * @subpackage Icarus/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Icarus
 * @subpackage Icarus/includes
 * @author     Y-collective <hello@y-collective.com>
 */
class Icarus {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Icarus_Loader $loader Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $plugin_name The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $version The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, and set the hooks for the core functionality.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_name = 'icarus';
		$this->version     = '1.0.0';

		$this->load_dependencies();
		$this->define_core_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Icarus_Loader. Orchestrates the hooks of the plugin.
	 * - Icarus_i18n. Defines internationalization functionality.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-icarus-loader.php';

		$this->loader = new Icarus_Loader();

	}

	/**
	 * Register all of the hooks related to the core functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_core_hooks() {

		$this->loader->add_action( 'admin_notices', $this, 'display_third_party_plugin_dependency_warning', 10 );
		$this->loader->add_action( 'fly_image_created', $this, 'optimize_image', 10, 2 );

	}

	/**
	 * Returns whether both WP Smush and Fly Dynamic Image Resizer plugins are active or not.
	 *
	 * @return bool Whether both WP Smush and Fly Dynamic Image Resizer plugins are active or not
	 */
	private function has_wp_smush_and_fdir() {

		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

		return (
			is_plugin_active( 'wp-smushit/wp-smush.php' ) &&
			is_plugin_active( 'fly-dynamic-image-resizer/fly-dynamic-image-resizer.php' )
		);

	}

	/**
	 * Displays a warning message on the admin area if either Fly Dynamic Image Resizer or WP Smush
	 * are missing.
	 */
	public function display_third_party_plugin_dependency_warning() {

		if ( $this->has_wp_smush_and_fdir() ) {
			// Bail early if both plugins are active
			return;
		}

		ob_start();

		?>

		<div class="notice notice-warning">
			<p>You have the Icarus plugin installed and activated, but you're missing either
				<a href="https://wordpress.org/plugins/fly-dynamic-image-resizer/" target="_blank" rel="noopener noreferrer">Fly
					Dynamic Image Resizer</a> or
				<a href="https://wordpress.org/plugins/wp-smushit/" target="_blank" rel="noopener noreferrer">WP
					Smush</a>, without which this plugin is effectively useless. Please install both
				plugins in order to have this plugin working.</p>
		</div>

		<?php

		ob_end_flush();

	}

	/**
	 * Runs upon a resized fly image is created, and pipes the path of the generated image file
	 * over to WP Smush's optimization function.
	 *
	 * @param string $attachment_id ID of the attachment in the database.
	 * @param string $fly_file_path Absolute path of the generated image file.
	 */
	public function optimize_image( $attachment_id, $fly_file_path ) {

		if (in_array($attachment_id, apply_filters('icarus_disallow_optimize_image', array()))) {
			return;
		}

		global $WpSmush;
		$WpSmush->do_smushit( $fly_file_path );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {

		$this->loader->run();

	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {

		return $this->plugin_name;

	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Icarus_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {

		return $this->loader;

	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {

		return $this->version;

	}

}
