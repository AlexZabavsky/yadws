<?php
/**
 * Represents slides box view
 *
 * @package   YADWS
 * @license   GPL-2.0+
 * @link      https://github.com/AlexZabavsky/yadws
 *
 */

$slide_template = '
    <div class="yadws-admin-slide" data-slide-id="{0}">
        <b>Slide <span class="yadws-slide-number">{0}</span></b> &nbsp; (
            <a href="javascript:void(0);" class="yadws-delete-slide">' . __( 'Delete', 'yadws' ) . '</a> 
            <span class="yadws-separator"><a href="javascript:void(0);" class="yadws-up-slide">' . __( 'Move Up', 'yadws' ) . '</a></span> 
            <span class="yadws-separator"><a href="javascript:void(0);" class="yadws-down-slide">' . __( 'Move Down', 'yadws' ) . '</a></span> 
        )        

        <table class="wp-list-table widefat fixed posts" cellspacing="0">
            <tbody class="yadws-images-list">
                {1}
            </tbody>           
        </table>
        
        <input type="button" class="button" value="' . __( 'Add image', 'yadws' ) . '" />
    </div>
';

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

<script type="text/template" id="yadws-slide-template">
    <?php echo $slide_template ?>
</script>

<script type="text/template" id="yadws-image-template">
    <?php echo $image_template ?>
</script>

<div class="yadws-slides-container">
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