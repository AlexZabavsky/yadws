<?php
/**
 * YADWS
 *
 * @package   YADWS
 * @license   GPL-2.0+
 * @link      https://github.com/AlexZabavsky/yadws
 *
 * Special thanks to creators of Wordpress Plugin Boilerplate - https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate
 */

/**
 * Plugin class.
 * @package YADWS
 */
class YADWS {

    /**
     * Plugin version, used for cache-busting of style and script file references.
     * @var    string
     */
    const VERSION = '0.1';

    /**
     * Unique identifier of the plugin.
     * @var    string
     */
    protected $plugin_slug = 'yadws';

    /**
     * Instance of this class.
     * @var      object
     */
    protected static $instance = null;

    /**
     * Slug of the plugin screen.
     * @var      string
     */
    protected $plugin_screen_hook_suffix = null;

    /**
     * Plugin intialization
     */
    private function __construct() {

        // Load plugin text domain
        add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

        // Activate plugin when new blog is added
        add_action( 'wpmu_new_blog', array( $this, 'activate_new_site' ) );

        // Add the options page and menu item.
        add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );

        // Add an action link pointing to the options page.
        $plugin_basename = plugin_basename( plugin_dir_path( __FILE__ ) . 'yadws.php' );
        add_filter( 'plugin_action_links_' . $plugin_basename, array( $this, 'add_action_links' ) );

        add_shortcode('YADWS', array($this, 'yadws_shortcode'));
        
        /*
        // Load admin style sheet and JavaScript.
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

        // Load public-facing style sheet and JavaScript.
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );


        // Define custom functionality. Read more about actions and filters: http://codex.wordpress.org/Plugin_API#Hooks.2C_Actions_and_Filters
    }

    /**
     * Return an instance of this class.
     * @return    object    A single instance of this class.
     */
    public static function get_instance() {

        // If the single instance hasn't been set, set it now.
        if ( null == self::$instance ) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * Fired when the plugin is activated.
     * @param    boolean    $network_wide    True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog.
     */
    public static function activate( $network_wide ) {
        if ( function_exists( 'is_multisite' ) && is_multisite() ) {
            if ( $network_wide  ) {
                // Get all blog ids
                $blog_ids = self::get_blog_ids();

                foreach ( $blog_ids as $blog_id ) {
                    switch_to_blog( $blog_id );
                    self::single_activate();
                }
                restore_current_blog();
            } else {
                self::single_activate();
            }
        } else {
            self::single_activate();
        }
    }

    /**
     * Fired when the plugin is deactivated.
     * @param    boolean    $network_wide    True if WPMU superadmin uses "Network Deactivate" action, false if WPMU is disabled or plugin is deactivated on an individual blog.
     */
    public static function deactivate( $network_wide ) {
        if ( function_exists( 'is_multisite' ) && is_multisite() ) {
            if ( $network_wide ) {
                // Get all blog ids
                $blog_ids = self::get_blog_ids();

                foreach ( $blog_ids as $blog_id ) {
                    switch_to_blog( $blog_id );
                    self::single_deactivate();
                }
                restore_current_blog();
            } else {
                self::single_deactivate();
            }
        } else {
            self::single_deactivate();
        }
    }

    /**
     * Get all blog ids of blogs in the current network that are:
     * - not archived
     * - not spam
     * - not deleted
     *
     * @return  array|false The blog ids, false if no matches.
     */
    private static function get_blog_ids() {
        global $wpdb;

        // get an array of blog ids
        $sql = "
            SELECT blog_id FROM $wpdb->blogs
            WHERE archived = '0' AND spam = '0'
            AND deleted = '0'
        ";
        return $wpdb->get_col( $sql );
    }
    
    
    /**
     * Load the plugin text domain for translation.
     */
    public function load_plugin_textdomain() {
        $domain = $this->plugin_slug;
        $locale = apply_filters( 'plugin_locale', get_locale(), $domain );

        load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
        load_plugin_textdomain( $domain, FALSE, basename( dirname( __FILE__ ) ) . '/languages' );
    }

    /**
     * Fired when a new site is activated with a WPMU environment.
     * @param	int	$blog_id ID of the new blog.
     */
    public function activate_new_site( $blog_id ) {
        if ( 1 !== did_action( 'wpmu_new_blog' ) )
            return;

        switch_to_blog( $blog_id );
        self::single_activate();
        restore_current_blog();
    }

    /**
     * Fired for each blog when the plugin is activated.
     */
    private static function single_activate() {
        // TODO: Define activation functionality here
    }

    /**
     * Fired for each blog when the plugin is deactivated.
     */
    private static function single_deactivate() {
      // TODO: Define deactivation functionality here
    }

    /**
     * Register and enqueue admin-specific style sheet.
     * @return    null    Return early if no settings page is registered.
     */
    public function enqueue_admin_styles() {
        if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
            return;
        }

        $screen = get_current_screen();
        if ( $screen->id == $this->plugin_screen_hook_suffix ) {
            //wp_enqueue_style( $this->plugin_slug .'-admin-styles', plugins_url( 'css/admin.css', __FILE__ ), array(), self::VERSION );
        }
    }

    /**
     * Register and enqueue admin-specific JavaScript.
     * @return    null    Return early if no settings page is registered.
     */
    public function enqueue_admin_scripts() {
        if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
            return;
        }

        $screen = get_current_screen();
        if ( $screen->id == $this->plugin_screen_hook_suffix ) {
            //wp_enqueue_script( $this->plugin_slug . '-admin-script', plugins_url( 'js/admin.js', __FILE__ ), array( 'jquery' ), self::VERSION );
        }
    }

    /**
     * Register and enqueue public-facing style sheet.
     */
    public function enqueue_styles() {
        //wp_enqueue_style( $this->plugin_slug . '-plugin-styles', plugins_url( 'css/public.css', __FILE__ ), array(), self::VERSION );
    }

    /**
     * Register and enqueues public-facing JavaScript files.
     */
    public function enqueue_scripts() {
        //wp_enqueue_script( $this->plugin_slug . '-plugin-script', plugins_url( 'js/public.js', __FILE__ ), array( 'jquery' ), self::VERSION );
    }
    
    /**
     * Register the administration menu for this plugin into the WordPress Dashboard menu.
     */
    public function add_plugin_admin_menu() {
    
        // TODO: Specify permissions
        
        $this->plugin_screen_hook_suffix = add_menu_page(
            __( 'YADWS - Yet Another Dynamic Wordpress Slider', $this->plugin_slug ),
            __( 'YADWS', $this->plugin_slug ),
            1,
            $this->plugin_slug,
            array( $this, 'display_admin_sliders_list' ),
            '',
            3.1
        );
        add_submenu_page(
            $this->plugin_slug,
            __( 'List of sliders', $this->plugin_slug ),
            __( 'Sliders list', $this->plugin_slug ),
            1,
            $this->plugin_slug.'_sliders_list',
            array( $this, 'display_admin_sliders_list' )
        );        
        add_submenu_page(
            $this->plugin_slug,
            __( 'Add new slider', $this->plugin_slug ),
            __( 'Add new slider', $this->plugin_slug ),
            1,
            $this->plugin_slug.'_create_slider',
            array( $this, 'display_admin_add_slider_form' )
        );
        remove_submenu_page($this->plugin_slug,$this->plugin_slug);
    }

    /**
     * Add settings action link to the plugins page.
     */
    public function add_action_links( $links ) {
        return array_merge(
            array(
                'settings' => '<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_slug ) . '">' . __( 'Settings', $this->plugin_slug ) . '</a>'
            ),
            $links
        );
    }

    /**
     * Render the list of sliders.
     */
    public function display_admin_sliders_list() {
        include_once( 'views/admin-sliders-list.php' );
    }

    /**
     * Render the slider creation form
     */
    public function display_admin_add_slider_form() {
        include_once( 'views/admin-slider-form.php' );
    }
     
    public function yadws_shortcode() {
    }

}