<?php
/**
 * Represents the view of a slide creation form
 *
 * @package   YADWS
 * @license   GPL-2.0+
 * @link      https://github.com/AlexZabavsky/yadws
 *
 */
?>

<div class="yadws-field-wrapper">
    <div class="yadws-label"><?php echo __( 'Navigation type', 'yadws' ); ?></div>
    <div class="yadws-input">
        <input type="checkbox" name="navigation[]" value="bullets" <?php echo ( in_array( 'bullets', $custom_fields[ 'navigation' ] ) ? 'checked="checked"' : '' );  ?> /> Bullets
        <br />
        <input type="checkbox" name="navigation[]" value="arrows"  <?php echo ( in_array( 'arrows', $custom_fields[ 'navigation' ] ) ? 'checked="checked"' : '' );  ?> /> Arrows
    </div>
</div>

<div class="yadws-field-wrapper">
    <div class="yadws-label">
        <label><?php echo __( 'Custom CSS class', 'yadws' ); ?></label>
    </div>
    <div class="yadws-input">
        <input type="text" name="custom_css_class" value="<?php echo $custom_fields[ 'custom_css_class' ]; ?>" />
    </div>
</div>

<div class="yadws-field-wrapper">
    <div class="yadws-label">
        <?php echo __( 'Expected initial size of the slider', 'yadws' ); ?>
    </div>
    <div class="yadws-input">
        <input type="text" name="slider_width"  value="<?php echo $custom_fields[ 'slider_width' ]; ?>" size="5" />
        <label><?php echo __( 'Width', 'yadws' ); ?></label>
        <br /> 
        <input type="text" name="slider_height"  value="<?php echo $custom_fields[ 'slider_height' ]; ?>" size="5" />
        <label><?php echo __( 'Height', 'yadws' ); ?></label>
    </div>
</div>