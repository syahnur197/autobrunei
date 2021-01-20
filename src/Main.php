<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://autobrunei.com
 * @since      1.0.0
 */

namespace Autobrunei;

use Autobrunei\Utils\Loader;
use Autobrunei\Utils\Internationalization;
use Autobrunei\Admin\Controller as AdminController;
use Autobrunei\Controllers\Admin\ListingController;
use Autobrunei\Controllers\Front\ListingPageController;
use Autobrunei\Front\Controller as FrontController;
use Autobrunei\Models\Listing;
use Autobrunei\Utils\AdminNotice;

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
 * @author     Sand Eater <sandeater@autobrunei.com>
 */
class Main {

	const PLUGIN_DIR = ABSPATH . 'wp-content/plugins/autobrunei/';

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;


	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;


	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;


	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_name = 'auto-brunei';
		$this->version = '1.0.0';
		$this->loader = new Loader();

		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}


	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Internationalization class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Internationalization();
		$plugin_i18n->set_domain( $this->get_plugin_name() );

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}


	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin  = new AdminController( $this->get_plugin_name(), $this->get_version() );

		// initialise models objects
		$listing_model = new Listing();

		// initialise controllers objects
		$listing_controller = new ListingController();

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		// listing post type and metas
		$this->loader->add_action( 'init', $listing_model, 'create_post_type' );
		$this->loader->add_action( 'save_post_listings', $listing_model, 'save_meta', 10, 3 );
        $this->loader->add_action( 'manage_listings_posts_columns', $listing_model, 'set_custom_columns' );
        $this->loader->add_action( 'manage_listings_posts_custom_column', $listing_model, 'custom_column', 10, 2 );
		
		$this->loader->add_action( 'wp_ajax_get_models_by_brand', $listing_controller, 'get_models_by_brand' );

		
		$this->loader->add_action('admin_notices', new AdminNotice(), 'displayAdminNotice');
		

	}


	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new FrontController( $this->get_plugin_name(), $this->get_version() );
		$listing_page = new ListingPageController();

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		// add booking view shortcode
		$this->loader->add_shortcode( 'all_listings_view', $listing_page, 'all_listings_view' );
		if (!is_admin()) {
    
        }

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
	 * @return    Loader    Orchestrates the hooks of the plugin.
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

	public static function get_path_from_root(string $path): string
	{

		if ($path[0] === "/") {
			$path = substr($path, 1);
		}

		return self::PLUGIN_DIR . $path;
	}

	public static function get_path_from_src(string $path): string
	{
		if ($path[0] === "/") {
			$path = substr($path, 1);
		}
		
		return self::PLUGIN_DIR . 'src/' . $path;
	}



}
