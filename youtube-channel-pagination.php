<?php
/*
  Plugin Name: Youtube Channel Pagination
  Plugin URI:
  Description: The plugin is show all youtube channel video with pagination.
  Version: 1.0
  Author: Mr. Balasaheb Bhise
  Author URI:
  License: GPL2

  Copyright 2016 Balasaheb Bhise  (email : balasahebbhise1@gmail.com)
 */
defined('ABSPATH') or die('No script kiddies please!');
add_action('plugins_loaded', array('YoutubeChannelPagination', 'init'));

class YoutubeChannelPagination {

    public static function init() {
        $class = __CLASS__;
        new $class;
    }

    public function __construct() {

        add_shortcode('Youtube_Channel_Pagination', array($this, 'YoutubeChannelPagination_Shortcode'));
        add_action('wp_enqueue_scripts', array($this, 'register_scripts_and_register_styles'));
    }

    public function YoutubeChannelPagination_Shortcode($atts) {

        $sd = shortcode_atts(array('channel_id' => 'default',), $atts);

        $youtube_channel_id_field_val = get_option('youtube_channel_id_field_name');
        $youtube_channel_api_key_field_val = get_option('youtube_channel_api_key_field_name');
        $youtube_channel_result_per_page_field_val = get_option('youtube_channel_result_per_page_field_name');

        $YCOagination = '<div id="youtube-channel-pagination" class="container"><div id="youtube-channel-video" class="row"><div class="display-watch-video"></div></div><div id="yc-pagination-watch" class="row"></div>
          <div class="row">
          <div class="col-sm-12">
          <input type="hidden" id="pageToken" value="" />
          <input type="hidden" id="youtube_channel_id_field_name" name="youtube_channel_id_field_name" value="'.$youtube_channel_id_field_val.'" />
          <input type="hidden" id="youtube_channel_api_key_field_name" name="youtube_channel_api_key_field_name" value="'.$youtube_channel_api_key_field_val.'" />
          <input type="hidden" id="youtube_channel_result_per_page_field_name" name="youtube_channel_result_per_page_field_name" value="'.$youtube_channel_result_per_page_field_val.'" />
          <div class="prev-next-outer">          
          <button type="button" id="pageTokenPrev" value="" class="btn btn-default pull-left"><i class="fa fa-angle-left"></i> Previous</button>
          <button type="button" id="pageTokenNext" value="" class="btn btn-default pull-right">Next <i class="fa fa-angle-right"></i></button>
          </div></div>
          </div></div>';

        return $YCOagination;
    }

    /* Load script file */

    public function register_scripts_and_register_styles() {

        wp_enqueue_style('youtube-channel-pagination-bootstrap', plugins_url('/bootstrap.min.css', __FILE__));
        wp_enqueue_style('youtube-channel-pagination-channel-styles', plugins_url('/channel-styles.css', __FILE__));

        wp_enqueue_script('jquery');
        wp_enqueue_script('youtube-channel-pagination', plugins_url('/channel-scripts.js', __FILE__), false, false, true);

        /*
          $protocol = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
          $params = array(
          'ajaxurl' => admin_url('admin-ajax.php', $protocol),
          );
          wp_localize_script('youtube-channel-pagination', 'ytcAjax', $params);
         */
    }

}

add_action('admin_menu', 'youtube_channel_pagination_setup_menu');

function youtube_channel_pagination_setup_menu() {
    add_menu_page('Youtube Channel Pagination Plugin Page', 'Youtube Channel Pagination Setting', 'manage_options', 'youtube-channel-pagination-setting', 'youtube_channel_pagination_init');
}

function youtube_channel_pagination_init() {
    // variables for the field and option names 

    $hidden_field_name = 'youtube_channel_pagination';
    $youtube_channel_id_field_name = 'youtube_channel_id_field_name';
    $youtube_channel_api_key_field_name = 'youtube_channel_api_key_field_name';
    $youtube_channel_result_per_page_field_name = 'youtube_channel_result_per_page_field_name';

    // Read in existing option value from database
    $youtube_channel_id_field_val = get_option($youtube_channel_id_field_name);
    $youtube_channel_api_key_field_val = get_option($youtube_channel_api_key_field_name);
    $youtube_channel_result_per_page_field_val = get_option($youtube_channel_result_per_page_field_name);

    // See if the user has posted us some information
    // If they did, this hidden field will be set to 'Y'
    if (isset($_POST[$hidden_field_name]) && $_POST[$hidden_field_name] == 'Y') {
        // Read their posted value
        $youtube_channel_id_field_val = $_POST[$youtube_channel_id_field_name];
        $youtube_channel_api_key_field_val = $_POST[$youtube_channel_api_key_field_name];
        $youtube_channel_result_per_page_field_val = $_POST[$youtube_channel_result_per_page_field_name];

        // Save the posted value in the database
        update_option($youtube_channel_id_field_name, $youtube_channel_id_field_val);
        update_option($youtube_channel_api_key_field_name, $youtube_channel_api_key_field_val);
        update_option($youtube_channel_result_per_page_field_name, $youtube_channel_result_per_page_field_val);
    }
    ?>
    <div class="wrap">

        <h1>Youtube Channel Pagination Setting</h1>


        <form name="youtube_channel_pagination"  method="post" action="" novalidate="novalidate">
            <input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
            <table class="form-table">
                <tbody>
                    <tr>
                        <th scope="row"><label for="blogname"><?php _e("Youtube Channel Id:", 'youtube-channel-pagination'); ?> </label></th>
                        <td>
                            <input class="regular-text" type="text" name="<?php echo $youtube_channel_id_field_name; ?>" value="<?php echo $youtube_channel_id_field_val; ?>" size="20">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="blogdescription"><?php _e("Youtube Channel API Key:", 'youtube-channel-pagination'); ?></label></th>
                        <td>
                            <input type="text" name="<?php echo $youtube_channel_api_key_field_name; ?>" value="<?php echo $youtube_channel_api_key_field_val; ?>" size="20" class="regular-text">
                    </tr>
                    <tr>
                        <th scope="row"><label for="default_role">Results Per Page</label></th>
                        <td>
                            <select name="<?php echo $youtube_channel_result_per_page_field_name ?>" id="default_role">
                                <option selected="selected" value="4">4</option>
                                <option selected="selected" value="<?php echo $youtube_channel_result_per_page_field_val ?>"><?php echo $youtube_channel_result_per_page_field_val ?></option>
                                <option value="4">4</option>
                                <option value="8">8</option>
                                <option value="12">12</option>
                                <option value="16">16</option>
                                <option value="20">20</option>
                                <option value="24">24</option>
                                <option value="28">28</option>
                                <option value="32">32</option>
                                <option value="36">36</option>
                                <option value="40">40</option>
                                <option value="44">44</option>
                                <option value="48">48</option>
                            </select>
                        </td>
                    </tr>
                </tbody>
            </table>
            <p class="submit">
                <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php esc_attr_e('Save Changes') ?>">
            </p>
        </form>        
    </div>
    <?php
}
?>

