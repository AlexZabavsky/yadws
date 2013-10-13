<?php
/**
 * Represents the view of sliders list page
 *
 * @package   YADWS
 * @license   GPL-2.0+
 * @link      https://github.com/AlexZabavsky/yadws
 */
?>

<div class="wrap">
    <div id="icon-themes" class="icon32"><br /></div>
    <h2>YADWS Slider Management</h2>

    <div id="poststuff">
      <form method="POST" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
        <div id="post-body" class="metabox-holder columns-2">
          <div class="postbox">
            <h3 class="handle"><span><?php echo $html_form_title ?></span></h3>
            <input type="hidden" name="slider_submitted" value="1" />
            <input type="hidden" name="slider_id" value="<?php echo $arr_slider['id'] ?>" />
            <div class="inside">
              <table class="links-table" cellpadding="0">
                <tr>
                  <th scope="row"><label for="slider_name">Slider name (administrative use only)</label></th>
                  <td><input type="text" name="slider_name" class="code" value="<?php echo $arr_slider['title'] ?>" /></td>
                </tr>
              </table>
            </div>
          </div>
          <center><input class="button-primary" type="submit" name="Save" value="Save Slider" id="submitbutton" /></center>
        </div>
      </form>
    </div>
  </div>
    
</div>
