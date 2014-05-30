<?php

class YADWS {
 
    /**
     * Maximum amount of sliders in carousel
     * @var {integer}
     */
    public $_max_slides = 4;
    
    /**
     * Amount of images on one slide in the "Large" view
     * @var {integer}
     */
    public $_images_per_slide = 3;
    
    
    /**
     * Slider types.
     * 
     * Parameters:
     * 'name'       String    Name of the slider type, as it is displayed in the admin area
     * 'width'      integer   Width of an element
     * 'height'     integer   Width of an element
     * 'layout'     String    Layout of the slides
     * 'type'       String    Type of the slider
     * 'navigation' String    Slider navigation type
     * 
     * Layout examples:
     *   [3] - one row with 3 items; 
     *   [3,4] - two rows with 3 items on top and 4 on bottom
     *   [3],[4] - first slide has 3 items, all the rest - 4 items  
     * 
     * @var {array}
     */
    public $_slider_types = array(
        'yadws-large-slider' => array(
            'type' => 'slider'
        ),
        'yadws-small-carousel' => array(
            'type' => 'carousel'
        ),
    );
    
    public $_slide_types = array(
        'images' => 'Images',
        //'markup' => 'Markup' // TODO
    );
    
    public $_settings_fields = array( 
        'navigation', 
        'custom_css_class',
        'slider_width',
        'slider_height',
    );
    
    public $_image_fields = array(
        'yadws_images', 
        'yadws_links'
    );
    
    public function __construct() {
        
        add_action( 'init', array( $this, 'yadws_sliders_init' ) );
        add_action( 'admin_init', array( $this, 'yadws_admin_init'  ) );
        add_action( 'save_post', array( $this, 'yadws_save_postdata' ) );
        add_action( 'plugins_loaded', array( $this, 'yadws_textdomain' ) );
            
        add_shortcode( 'yadws', array( $this, 'yadws_shortcode_handler' ) );
                
        wp_enqueue_style( 'yadws-css', plugins_url( 'css/yadws.css', __FILE__ ) );
        wp_enqueue_script( 'yadws-js', plugins_url( 'js/jquery.yadws.js', __FILE__ ), array( 'jquery' ), array(), false );
    }

    /**
     * Admin scripts init
     * @return void
     */
    public function yadws_admin_init() {
        
        wp_enqueue_style( 'yadws-admin-css', plugins_url( 'css/yadws-admin.css', __FILE__ ) );
        add_filter( 'manage_yadws_posts_columns', array( $this, 'yadws_admin_table_head' ) );
        add_action( 'manage_yadws_posts_custom_column', array( $this, 'yadws_admin_table_columns' ), 10, 2 );
        add_action( 'add_meta_boxes', array( $this, 'yadws_add_custom_boxes' ) );
    }

    /**
     * Loads plugin text domain
     * @return void
     */
    public function yadws_textdomain() {
        
        load_plugin_textdomain('yadws', false, basename( dirname( __FILE__ ) ) . '/languages' );
    }
                   
    /**
     * Register a post type
     * @return void
     */
    public function yadws_sliders_init() {
        
        $labels = array(
            'name' => 'Madonna Sliders',
            'singular_name' => 'Madonna Slider',
            'add_new' => 'Add New',
            'add_new_item' => 'Add New Slider',
            'edit_item' => 'Edit Slider',
            'new_item' => 'New Slider',
            'all_items' => 'All Sliders',
            'view_item' => 'View Slider',
            'search_items' => 'Search Sliders',
            'not_found' => 'No sliders found',
            'not_found_in_trash' => 'No sliders found in Trash',
            'parent_item_colon' => '',
            'menu_name' => 'Madonna Sliders'
        );
        
        $args = array(
            'labels' => $labels,
            'public' => false,
            'description' => 'Madonna Sliders',
            'publicly_queryable' => false,
            'exclude_from_search' > true,
            'show_ui' => true,
            'show_in_nav_menus' => true,
            'show_in_menu' => true,
            'query_var' => true,
            'capability_type' => 'post',
            'has_archive' => false,
            'hierarchical' => false,
            'menu_position' => 3,
            'supports' => array( 'title' ),
            'rewrite' => false
       );
        
        register_post_type( 'yadws', $args );
    }

    /**
     * Update slider list head.
     */
    function yadws_admin_table_head( $defaults ) {
        
        unset( $defaults['date'] );
        $defaults['shortcode'] = 'Shortcode';
        $defaults['date'] = 'Date';
        
        return $defaults;
    }    

    /**
     * Update slider list columns.
     */
    function yadws_admin_table_columns( $column_name, $post_id ) {
    
        if ( $column_name == 'shortcode' ) {
            $post = get_post( $post_id );
            echo "[yadws slug='" . $post->post_name . "']";
        }    
    }    
    
    /**
     * Add a box to the main column on the Post and Page edit screens.
     */
    public function yadws_add_custom_boxes() {

        wp_enqueue_media();
        wp_enqueue_script( 'yadws-admin', plugins_url( 'js/jquery.yadws-admin.js', __FILE__ ), array( 'jquery' ), array(), true );
        
        add_meta_box( 'yadws_settings_box', __( 'Settings', 'yadws' ), array( $this, 'yadws_admin_settings_box'), 'yadws' );
        add_meta_box( 'yadws_slides_box', __( 'Slides', 'yadws' ), array( $this, 'yadws_admin_slides_box'), 'yadws' );
        add_meta_box( 'yadws_slides_form', __( 'Add new slide', 'yadws' ), array( $this, 'yadws_admin_slides_form'), 'yadws' );
    }

    /**
     * Output settings box.
     *
     * @param WP_Post $post The object for the current post/page.
    */
    public function yadws_admin_settings_box( $post ) {
        
        foreach ( $this->_settings_fields as $field ) {
            $custom_fields[$field] = get_post_meta( $post->ID, $field, true );
        }
        
        include_once( 'views/admin-settings-box.php' );
    }
    
    /**
     * Output slides box.
     *
     * @param WP_Post $post The object for the current post/page.
    */
    public function yadws_admin_slides_box( $post ) {
        
        wp_nonce_field( 'yadws_slides_box', 'yadws_slides_box_nonce' );
        
        foreach ( $this->_image_fields as $field ) {
            $custom_fields[$field] = get_post_meta( $post->ID, $field, true );
        }
        
        include_once( 'views/admin-slides-box.php' );
    }
    
    /**
     * Output slides creation form.
     *
     * @param WP_Post $post The object for the current post/page.
     */
    public function yadws_admin_slides_form( $post ) {
      
        include_once( 'views/admin-slides-form.php' );
    }

    /**
     * Save slides and images
     */
    public function yadws_save_postdata( $post_id ) {
        
        if ( ! isset( $_POST['yadws_slides_box_nonce'] ) ) {
            return $post_id;
        }
    
        $nonce = $_POST['yadws_slides_box_nonce'];
    
        if ( ! wp_verify_nonce( $nonce, 'yadws_slides_box' ) ) {
            return $post_id;
        }
    
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
        }
    
        if ( 'yadws' == $_POST['post_type'] ) {
            if ( ! current_user_can( 'edit_page', $post_id ) ) {
                return $post_id;
            }
        } else {
            if ( ! current_user_can( 'edit_post', $post_id ) ) {
                return $post_id;
            }
        }
        
        foreach ( $this->_settings_fields as $field ) {
            update_post_meta( $post_id, $field, $_POST[$field] );
        }

        foreach ( $this->_image_fields as $field ) {
            update_post_meta( $post_id, $field, $this->prepare_meta_data( $_POST[$field] ) );
        }
    }
        
    /**
     * Output shortcode
     */
    public function yadws_shortcode_handler( $atts, $content = null ) {
        
        if ( ! $atts['slug'] ) {
            return;
        }
        
        $post = $this->getPostBySlug( $atts['slug'] );
        $fields = get_post_custom( $post->ID );
        
        foreach ( get_post_custom( $post->ID ) as $field_name => $field_value ) {
            $fields[$field_name] = maybe_unserialize( $field_value[0] );
        }
        
        foreach ( $this->_settings_fields as $key ) {
            $options[$key] = maybe_unserialize( $fields[$key] );
        }
        
        // Slides creation
                
        $slides_html = '';
        
        foreach ( $fields['yadws_images'] as $slide_id => $slide_array) {
            
            $slides_html .= '<div class="yadws-slide ' . ( $slide_id > 1 ? 'yadws-hidden' : '' ) . '" data-slide-id="' . ( $slide_id - 1 ) . '">';
            
            foreach ( $slide_array as $image_id => $image_number ) {
                
                $item_width = round( 100 / count( $slide_array ), 2 );
                
                $image_atts = wp_get_attachment_image_src( $image_number, array( round( $options['slider_width'] / 3 ), $options['slider_height'] ) );
                
                $slides_html .= '<a href="' . $fields['yadws_links'][$slide_id][$image_id] . '" class="yadws-item" style="width:' . $item_width . '%">'
                             . '<img src="' . $image_atts[0] . '" />'
                             . '</a>';
            }
            
            $slides_html .= '</div>';  
        }
        
        // Navigation creation. 
        
        $navigation = '';
        
        if ( in_array( 'arrows', $fields['navigation'] ) ) {
            $navigation .= '
                <div class="yadws-next" style="display: none;"></div>
                <div class="yadws-prev" style="display: none;"></div>
            ';
        }
        
        if ( in_array( 'bullets', $fields['navigation'] ) ) {
            
            $navigation .= '<div class="yadws-navigation">';
            
            for ( $i = 0; $i < count($fields['yadws_images']); $i++ ) {                            
                $navigation .= '<div class="yadws-bullet" data-bullet-id="' . $i . '"></div>';
            }
            
            $navigation .= '</div>';
        }
                    
        $template = '
            <script type="text/javascript">
                jQuery(function ($) {
                    $(".yadws-%s").yadws(%s);
                });
            </script>
            <div class="yadws-slider yadws-%s">
                <div class="yadws-container">
                    <div class="yadws-inner">
                        %s
                    </div>
                </div>
                %s    
            </div>
        ';
                
        $shortcode = sprintf( $template, $atts['slug'], json_encode( $options ), $atts['slug'], $slides_html, $navigation );
               
        return $shortcode;        
    }

    /**
     * Get post by slug
     * 
     * @param   String  $slug   Machine name of the post
     * @return  object  Post
     */
    private function getPostBySlug( $slug ) {
        
        $args = array(
            'name' => $slug,
            'post_type' => 'yadws',
            'post_status' => 'publish',
            'numberposts' => 1
        );
        
        $posts = get_posts( $args );
                        
        if ( $posts ) {
            return $posts[0];
        } else {
            return null;
        }       
    }
    
    /**
     * Sanitize and reorder array
     * 
     * @param   array  $array   Machine name of the post
     * @return  array 
     */
    private function prepare_meta_data ( $array ) {
        
        $result = array();
        $i = 0;
        
        foreach ( $array as $subArray ) {
            
            $resultSub = array();
            $j = 0;
            
            foreach ( $subArray as $subValue ) {
                 $resultSub[ ++$j ] = $subValue;
            }
            
            $result[ ++$i ] = $resultSub;
        }
        
        return $result;
    }
}

$yadws = new YADWS();