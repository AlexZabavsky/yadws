<?php

class YADWS {
 
    /**
     * Maximum amount of sliders in carousel
     * @var {integer}
     */
    public $_max_slides = 3;
    
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
            'name' => 'Large Slider',
            'width' => 315,
            'height' => 478,
            'layout' => '[3]',
            'type' => 'slider'
        ),
        'yadws-small-carousel' => array(
            'name' => 'Small Carousel',
            'width' => 100,
            'height' => 150,
            'layout' => '',
            'type' => 'carousel'
        ),
    );
    
    public function __construct() {
        
        add_action( 'init', array( $this, 'yadws_sliders_init' ));
        add_filter( 'rwmb_meta_boxes', array( $this, 'yadws_register_meta_boxes' ));
        add_shortcode( 'yadws', array( $this, 'yadws_shortcode_handler' ));
        
        foreach ( $this->_slider_types as $key => $val) {
            add_image_size( $key, $val['width'], $val['height'], true );
        }
        
        wp_enqueue_style( 'yadws', plugins_url( 'css/yadws.css', __FILE__ ) );
        wp_enqueue_script( 'yadws', plugins_url( 'js/jquery.yadws.js', __FILE__ ), array( 'jquery' ), array(), false);
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
     * Create meta boxes with additional fields
     */
    public function yadws_register_meta_boxes() {
        
        $prefix = 'yadws_';
        
        $types = array();
        
        foreach ( $this->_slider_types as $key => $value ) {
            $types[$key] = __( $value['name'], 'yadws' );
        }
        
        $meta_boxes[] = array(
            'id' => 'yadws_additional_fields',
            'title' => __( 'Details', 'yadws' ),
            'pages' => array( 'yadws' ),
            'context' => 'normal', // try advanced
            'priority' => 'low',
            'autosave' => false,
            'fields' => array(
                array(
                    'name' =>__( 'Slider Type', 'yadws' ),
                    'id'   => $prefix.'slider_type',
                    'type' => 'radio',
                    'options' => $types
                ),
                array(
                    'name' => __( 'Navigation type', 'yadws' ),
                    'id'   => $prefix.'navigation_type',
                    'type' => 'checkbox_list',
                    'options' => array(
                        'arrows' => __( 'Arrows', 'yadws' ),
                        'bullets' => __( 'Bullets', 'yadws' ),
                    ),
                ),
            ),
        );
    
        for ( $i = 1; $i <= $this->_max_slides; $i++ ) {
            
            $image_fields = array();
            
            for ( $j = 1; $j <= $this->_images_per_slide; $j++ ) {
                $image_fields[] = array(
                    'name' => __( 'URL for image '.$j, 'yadws' ),
                    'id'   => $prefix.'slide_'.$i.'_url_'.$j,
                    'type' => 'text',
                );
            } 
            
            $meta_boxes[] = array(
                'id' => $prefix.'slides_'.$i,
                'title' => __( 'Slide '.$i, 'yadws' ),
                'pages' => array( 'yadws' ),
                'context' => 'normal', // try advanced
                'priority' => 'low',
                'autosave' => false,
                'fields' => array_merge(
                    array(
                        array(
                            'name' => 'Images ('.$this->_images_per_slide.')',
                            'id' => $prefix.'slide_imgs_'.$i,
                            'type' => 'plupload_image',
                            'max_file_uploads' => 3,
                        )
                    ),
                    $image_fields
                ),
            );
        }
        
        return $meta_boxes;
    }

    /**
     * Create a shortcode
     */
    public function yadws_shortcode_handler( $atts, $content = null ) {
        
        if( ! $atts['slug'] ) {
            return;
        }
        
        $post = $this->getPostBySlug( $atts['slug'] );
        $fields = get_post_custom( $post->ID );
        
        $options_array = $this->_slider_types[$fields['yadws_slider_type'][0]];
        $options_array['navigation'] = $fields['yadws_navigation_type'];
        
        $options = json_encode( $options_array );

        // Slides creation
                
        $slides = '';
        
        for ( $i = 1; $i <= $this->_max_slides; $i++ ) {
            
            $slides .= '<div class="yadws-slide ' . ( $i>1 ? 'yadws-hidden' : '' ) . '">';
            
            for ( $j = 0; $j < $this->_images_per_slide; $j++ ) {
                $slides .= '<a href="' . $fields['yadws_slide_' . $i . '_url_' . ($j+1)][0] . '" class="yadws-item">'
                    . wp_get_attachment_image( $fields['yadws_slide_imgs_' . $i][$j], 'yadws-large-slider' )
                    . '</a>';
            }
            
            $slides .= '</div>';
        }

        // Navigation creation. 
        
        $navigation = '';
        
        if ( in_array( 'arrows', $fields['yadws_navigation_type'] ) ) {
            
            $navigation .= '
                <div class="yadws-next"></div>
                <div class="yadws-prev"></div>
            ';
        }
        
        if ( in_array( 'bullets', $fields['yadws_navigation_type'] ) ) {
            
            $navigation .= '<div class="yadws-navigation">';
            
            for ( $k = 0; $k < $this->_max_slides; $k++ ) {                            
                $navigation .= '<div class="yadws-bullet"></div>';
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
        
        $shortcode = sprintf( $template, $atts['slug'], $options, $atts['slug'], $slides, $navigation );
               
        return $shortcode;        
    }

    /**
     * Get post by slug
     * 
     * @param   String  $slug   Machine name of the post
     * @return  object  Post
     */
    private function getPostBySlug( $slug ) {
        
        $args=array(
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
}

$yadws = new YADWS();