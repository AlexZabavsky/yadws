<?php
/**
 * Represents the view of sliders list page
 *
 * @package   YADWS
 * @license   GPL-2.0+
 * @link      https://github.com/AlexZabavsky/yadws
 *
 * TODO: Replace slides_number with dynamic field, the top value of which will be obtained from settings
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
                            <tr>
                                <th scope="row"><label for="number of items per slide">Number of items per slide</label></th>
                                <td>
                                    <select name="slides_number" class="code">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                        <option value="10">10</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="slider_name">Transition Type</label></th>
                                <td>
                                    <select name="transition_type" class="code">
                                        <option value="page">One page at a time</option>
                                        <option value="smooth">Smooth scrolling</option>
                                        <option value="item">Item by item</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="slider_name">Items</label></th>
                                <td>
                                    <div class="slides_container"></div>
                                    <input class="button-primary" type="button" name="Add item" value="Add item" id="additembtn" />
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <center><input class="button-primary" type="submit" name="Save" value="Save slider" id="submitbutton" /></center>
            </div>
        </form>
    </div>
</div>
