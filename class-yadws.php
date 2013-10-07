<?php
/**
 * YADWS
 *
 * @package   YADWS
 * @license   GPL-2.0+
 * @link      https://github.com/AlexZabavsky/yadws
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
        /*
        // Load admin style sheet and JavaScript.
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

        // Load public-facing style sheet and JavaScript.
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );


        // Define custom functionality. Read more about actions and filters: http://codex.wordpress.org/Plugin_API#Hooks.2C_Actions_and_Filters
        add_action( 'TODO', array( $this, 'action_method_name' ) );
        add_filter( 'TODO', array( $this, 'filter_method_name' ) );*/
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
    * Register the administration menu for this plugin into the WordPress Dashboard menu.
    */
    public function add_plugin_admin_menu() {

        /*
        * Add a settings page for this plugin to the Settings menu.
        *
        * TODO:
        * - Check out http://codex.wordpress.org/Administration_Menus and create a separate menu block
        * - Change 'manage_options' to the capability that fit (http://codex.wordpress.org/Roles_and_Capabilities)
        */
        $this->plugin_screen_hook_suffix = add_options_page(
            __( 'YADWS Settings', $this->plugin_slug ),
            __( 'Add new slider', $this->plugin_slug ),
            'manage_options',
            $this->plugin_slug,
            array( $this, 'display_plugin_admin_page' )
        );
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

}