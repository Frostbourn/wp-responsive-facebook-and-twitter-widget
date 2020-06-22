<?php

/*
Plugin Name: Responsive Social Slider Widget
Description: Display Facebook and Twitter on your website in beautiful responsive box which slides in from page edge in a handy way!
Plugin URI: https://github.com/Frostbourn/wp-responsive-facebook-and-twitter-widget
AUthor: Jakub Skowroński
Author URI: https://jakubskowronski.com
Version: 1.3.5
License: GPLv2 or later
*/

defined('ABSPATH') or die('No script kiddies please!');

$widget_settings = array(
    array(
        'id' => 'show_on_mobile',
        'type' => 'radio',
        'default' => '1',
        'label' => 'Show on mobile devices',
        'desc' => 'Display Facebook tab on mobile devices',
        'options' => array(
            0 => 'No',
            1 => 'Yes'
        )
    ),
    array(
        'id' => 'border-radius',
        'type' => 'radio',
        'default' => '1',
        'label' => 'Rounded icons',
        'desc' => 'Change the border radius of the icons',
        'options' => array(
            0 => 'No',
            1 => 'Yes'
        )
    ),
    array(
        'id' => 'facebook_id',
        'type' => 'text',
        'default' => 'facebook',
        'label' => 'Facebook Page ID',
        'desc' => 'Facebook Page ID'
    ),
    array(
        'id' => 'twitter_id',
        'type' => 'text',
        'default' => 'twitter',
        'label' => 'Twitter ID',
        'desc' => 'Twitter account ID'
    ),
    array(
        'id' => 'fa-cdn',
        'type' => 'radio',
        'default' => '0',
        'label' => 'Load Font Awesome library',
        'desc' => 'Set to YES if icons missing',
        'options' => array(
            0 => 'No',
            1 => 'Yes'
        )
    )
);

add_action('wp_head', 'widgetScripts');
add_action('wp_enqueue_scripts', 'widgetScripts');

function widgetScripts()
{
    wp_enqueue_style('style', plugin_dir_url(__FILE__) . 'css/style.min.css');
    if ( trim(get_option('fa-cdn') ) == 1 ) 
    {
        wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
    }
}

add_action('wp_footer', 'widgetFrontend');

function widgetFrontend()
{
    if ( trim(get_option('show_on_mobile') ) == 1 ) 
    {
        ?>
        <div class="social_mobile">
            <div class="top-left">
            <?php
                $sum = 0;
                if ( !empty(get_option('facebook_id')) )
                {
                    $sum++;
                    $iPhone  = stripos($_SERVER['HTTP_USER_AGENT'], "iPhone");
                    $iPad    = stripos($_SERVER['HTTP_USER_AGENT'], "iPad");
                    $Android = stripos($_SERVER['HTTP_USER_AGENT'], "Android");

                    if ( $iPhone || $iPad ) 
                    {
                        $fb_url = 'fb://profile/' . get_option('facebook_id');
                    } else  {
                        if ( $Android ) 
                        {
                            $fb_url = 'fb://page/' . get_option('facebook_id');
                        }
                    }
                        ?>
                        <a class="facebook" href="<?php echo $fb_url ?>" target="_blank">
                            <i class="fa fa-facebook-f"></i>
                        </a>
                        <?php 
                }
                if ( !empty(get_option('twitter_id')) )
                {
                    $sum++;
                    ?>
                    <a class="twitter" href="https://twitter.com/<?php get_option('twitter_id') ?>" target="_blank">
                        <i class="fa fa-twitter"></i>
                    </a>
                    <?php 
                }
            ?>
            </div>
        </div>
        <?php
    }
    ?>
    <div class="social_slider" style="top: 10vh;"> 
    <?php
        if ( !empty(get_option('facebook_id')) ) 
        { 
            ?>
            <input id="tabOne" type="radio" name="tabs" checked />
            <label for="tabOne" class="facebook_icon" style="max-width: 32px;"><span>facebook</span><i class="fa fa-facebook-f"></i></label>
            <section id="contentOne">
                <div class="facebook_box">
                    <iframe src="https://www.facebook.com/plugins/page.php?href=https://www.facebook.com/<?php echo get_option('facebook_id'); ?>&tabs=timeline,events,messages&width=350&height=490&small_header=false&adapt_container_width=false&hide_cover=false&show_facepile=true" width="350" height="490" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true">
                    </iframe>
                </div>
            </section>
            <?php
        }

        if ( !empty(get_option('twitter_id')) ) 
        {
            ?>
            <input id="tabTwo" type="radio" name="tabs" />
            <label for="tabTwo" class="twitter_icon" style="max-width: 32px;"><span>twitter</span><i class="fa fa-twitter"></i></label>
            <section id="contentTwo">
                <div class="twitter_box">
                    <a class="twitter-timeline" data-width="350" data-height="490" href="https://twitter.com/<?php echo get_option('twitter_id'); ?>">Tweets by <?php echo get_option('twitter_id'); ?></a>
                    <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
                </div>
            </section>
            <?php  
        } 
    ?>
    </div>
    <style>
    <?php
        if ( trim(get_option('show_on_mobile') ) == 1 ) 
        {
            ?>
            .social_mobile a, .social_mobile a:focus, .social_mobile a:hover { width: calc(100% / <?php echo $sum ?>);}
            <?php
        }
        if ( trim(get_option('border-radius') ) == 1 ) 
        { 
            ?>
            .social_slider .facebook_icon, .social_slider .twitter_icon { border-radius: 5px 0 0 5px !important;}
            <?php 
        } 
    ?>
    </style>
    <?php
}



add_action('admin_menu', 'widgetMenu');

function widgetMenu()
{
    add_options_page('Responsive Social Slider Widget', 'Responsive Social Slider Widget', 'manage_options', 'responsive-facebook-and-twitter-widget', 'widgetBackend');
}

function filter_action_links( $links ) {
    $links['settings'] = '<a href="' . admin_url( '/options-general.php?page=responsive-facebook-and-twitter-widget' ) . '">' . __( 'Settings' ) . '</a>';
    $links['support'] = '<a href="https://m.me/JSNetworkSolutions" target="_blank">' . __( 'Support' ) . '</a>';
    // if( class_exists( 'CT_DB_Pro_Admin' ) ) {
    //  $links['upgrade'] = '<a href="https://discussionboard.pro">' . __( 'Upgrade', 'wp-discussion-board' ) . '</a>';
    // }
    return $links;
   }
   
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ),'filter_action_links', 10, 2 );

function widgetBackend()
{
    global $widget_settings;
    if ( $_POST ) 
    {
        foreach ( $widget_settings as $setting ) 
        {
            $save_setting = sanitize_text_field($_POST[$setting['id']]);
            update_option($setting['id'], $save_setting);
        }
        echo '<div class="updated fade"><p><strong>' . __('Settings saved.') . '</strong></p></div>';
    }

    echo '<form method="post">';
        settings_fields('setting-fields');
        do_settings_sections('responsive-facebook-and-twitter-widget');
        echo '<h1>' . get_admin_page_title() . '</h1></ br><h3>Follow these steps to find your Facebook & Twitter ID: <a href="https://github.com/Frostbourn/wp-responsive-facebook-and-twitter-widget" target="_blank">Tutorial</a></h3>
        <table class="form-table">';
            foreach ( $widget_settings as $setting ) 
            {
                echo '<tr valign="top"><th scope="row">' . $setting['label'] . '</th><td>';
                switch ( $setting['type'] ) 
                {
                    case 'text':
                        echo '<input type="text" name="' . $setting['id'] . '" value="' . get_option($setting['id']) . '" />';
                        break;

                    case 'textarea':
                        echo '<textarea cols="30" rows="5" name="' . $setting['id'] . '">' . get_option($setting['id']) . '</textarea>';
                        break;

                    case 'radio':
                        echo '<select name="' . $setting['id'] . '">';
                        foreach ($setting['options'] as $optionn => $optionv) 
                        {
                            echo '<option value="' . $optionn . '" ' . (($optionn == get_option($setting['id'])) ? ' selected="selected"' : '') . '>' . $optionv . '</option>';
                        }

                        echo '</select>';
                        break;
                }

                echo '</td></tr>';
            }

    echo '
            </table>
                <p class="submit">
                    <input type="submit" class="button-primary" value="' . __('Save Changes') . '" />
                </p>
	    </form>
    </div>';
}

?>