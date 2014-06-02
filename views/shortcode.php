<?php
/**
 * Represents the shortcode view
 *
 * @package   YADWS
 * @license   GPL-2.0+
 * @link      https://github.com/AlexZabavsky/yadws
 *
 */

$image_template = '
    <tr data-line-id="{1}">
        <td width="197"><img src="{3}" /></td>
        <td>
            ' . __( 'URL:', 'yadws' ) . '<br />
            <input type="hidden" name="yadws_images[{0}][{1}]" value="{2}" />
            <input type="text" name="yadws_links[{0}][{1}]" class="yadws-link-input" width="100%" value="{4}" />
        </td>
        <td width="80">
            <a href="javascript:void(0);" class="yadws-delete-image">' . __( 'Delete', 'yadws' ) . '</a><br />
            <a href="javascript:void(0);" class="yadws-up-image">' . __( 'Move Up', 'yadws' ) . '</a><br />
            <a href="javascript:void(0);" class="yadws-down-image">' . __( 'Move Down', 'yadws' ) . '</a>
        </td>
    </tr>
';

?>


<script type="text/javascript">
    jQuery(function ($) {
        $(".yadws-<?php echo $atts['slug']; ?>").yadws(<?php echo json_encode( $options ); ?>);
    });
</script>
<div class="yadws-slider yadws-<?php echo $atts['slug']; ?>">
    <div class="yadws-container">
        <div class="yadws-inner">
            <?php
                foreach ( $custom_fields['yadws_images'] as $slideId => $images ) {
                    
                    $imagesHtml = '';
                    $search = array( '{0}', '{1}', '{2}', '{3}', '{4}' );
        
                    foreach ( $images as $imageId => $image ) {
                        
                        $image_attributes = wp_get_attachment_image_src( $image, 'medium' );                
                        
                        $replace_images = array( 
                            $slideId,
                            $imageId,
                            $image,
                            $image_attributes[0],
                            $custom_fields['yadws_links'][$slideId][$imageId]
                        );
                        
                        $imagesHtml .= str_replace( $search, $replace_images, $image_template );
                    }
                    
                    $replace_slides = array ( $slideId, $imagesHtml );
                    
                    echo str_replace( $search, $replace_slides, $slide_template );
                }
            ?>
        </div>
    </div>
    %s    
</div>