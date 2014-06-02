<?php
/**
 * Represents a view of a slider creation form
 *
 * @package   YADWS
 * @license   GPL-2.0+
 * @link      https://github.com/AlexZabavsky/yadws
 *
 */
?>

<div class="yadws-field-wrapper">
    <div class="yadws-label"><?php _e( 'Navigation type', 'yadws' ); ?></div>
    <div class="yadws-input">
        <input type="checkbox" name="navigation[]" value="bullets" <?php echo ( in_array( 'bullets', (array) $custom_fields[ 'navigation' ] ) ? 'checked="checked"' : '' );  ?> /> 
            <?php _e( 'Bullets', 'yadws' ); ?>
        <br />
        <input type="checkbox" name="navigation[]" value="arrows"  <?php echo ( in_array( 'arrows', (array) $custom_fields[ 'navigation' ] ) ? 'checked="checked"' : '' );  ?> />  
            <?php _e( 'Arrows', 'yadws' ); ?>
    </div>
</div>

<div class="yadws-field-wrapper">
    <div class="yadws-label">
        <label><?php _e( 'Custom CSS class', 'yadws' ); ?></label>
    </div>
    <div class="yadws-input">
        <input type="text" name="custom_css_class" value="<?php echo $custom_fields[ 'custom_css_class' ]; ?>" />
    </div>
</div>

<div class="yadws-field-wrapper">
    <div class="yadws-label">
        <label><?php _e( 'Rotation interval (seconds)', 'yadws' ); ?></label>
    </div>
    <div class="yadws-input">
        <input type="text" name="rotation_interval" value="<?php echo $custom_fields[ 'rotation_interval' ]; ?>" size="5" /> 
        <label><?php _e( 'Put 0 (zero) or leave blank for no rotation', 'yadws' ); ?></label>
    </div>
</div>

<div class="yadws-field-wrapper">
    <div class="yadws-label">
        <label><?php _e( 'Expected initial size of the slider *', 'yadws' ); ?></label>
    </div>
    <div class="yadws-input">
        <input type="text" name="slider_width"  value="<?php echo $custom_fields[ 'slider_width' ]; ?>" size="5" />
        <label><?php _e( 'Width', 'yadws' ); ?></label>
        <br /> 
        <input type="text" name="slider_height"  value="<?php echo $custom_fields[ 'slider_height' ]; ?>" size="5" />
        <label><?php _e( 'Height', 'yadws' ); ?></label>
    </div>
</div>