<?php

/*
Plugin Name: Responsive Social Slider Widget PRO
Description: Display Facebook, Twitter, Instagram and more on your website in beautiful responsive box which slides in from page edge in a handy way!
Plugin URI: https://jsns.eu
AUthor: Jakub Skowroński
Author URI: https://jakubskowronski.com
Version: 1.0.0
License: GPLv2 or later
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

$widgetSettings = array(
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
        'id' => 'position',
        'type' => 'radio',
        'default' => '1',
        'label' => 'Position',
        'desc' => 'Position of the sidebar',
        'options' => array(
            0 => 'Right',
            1 => 'Left'
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
        'id' => 'fa-cdn',
        'type' => 'radio',
        'default' => '1',
        'label' => 'Load Font Awesome library (Set to YES if any icons missing)',
        'desc' => 'Set to YES if any icons missing',
        'options' => array(
            0 => 'No',
            1 => 'Yes'
        )
    ),
    array(
        'id' => 'ids_separator',
        'type' => 'separator_ids',
        'default' => '',
        'label' => 'Social channels ID\'s',
        'desc' => ''
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
        'id' => 'instagram_id',
        'type' => 'text',
        'default' => 'instagram',
        'label' => 'Instagram ID',
        'desc' => 'Instagram account ID'
    ),
    array(
        'id' => 'custom_separator',
        'type' => 'separator_custom',
        'default' => '',
        'label' => 'Custom tab aperrance',
        'desc' => ''
    ),
    array(
        'id' => 'custom_color',
        'type' => 'text',
        'default' => '#d3d3d3',
        'label' => 'Custom tab HEX color code',
        'desc' => 'Custom tab color'
    ),
    array(
        'id' => 'custom_text',
        'type' => 'text',
        'default' => 'spotify',
        'label' => 'Custom tab icon text',
        'desc' => 'Custom tab icon text'
    ),
    array(
        'id' => 'custom_icon',
        'type' => 'text',
        'default' => 'fa fa-spotify',
        'label' => 'Custom tab icon',
        'desc' => 'Custom tab icon. Use only Font Awesome'
    ),
    array(
        'id' => 'custom_url',
        'type' => 'text',
        'default' => '',
        'label' => 'Mobile Custom button URL',
        'desc' => ''
    ),
    array(
        'id' => 'custom_html',
        'type' => 'html',
        'default' => '',
        'label' => 'HTML here',
        'desc' => ''
    )
);

add_action( 'wp_head', 'sliderScripts' );
add_action( 'wp_enqueue_scripts', 'sliderScripts' );

function sliderScripts()
{
    wp_enqueue_style( 'social-widget-style', plugin_dir_url(__FILE__) . 'css/style.min.css' );
    wp_enqueue_script( 'social-widget-axios', 'https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.2/axios.min.js', array(), false, true );
    wp_enqueue_script( 'social-widget-script', plugin_dir_url(__FILE__) . 'js/script.js', array(), false, true  );

    if ( trim(get_option('fa-cdn') ) == 1 ) 
    {
        wp_enqueue_style( 'social-widget-font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' );
    }
}

add_action( 'wp_footer', 'sliderFrontend' );

function sliderFrontend()
{
    if ( trim(get_option('show_on_mobile') ) == 1 ) 
    {
        ?>
        <div class="social_mobile_pro">
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
                        $fb_url = 'fb://profile/' . get_option( 'facebook_id' );
                    } else  {
                        if ( $Android ) 
                        {
                            $fb_url = 'fb://page/' . get_option( 'facebook_id' );
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
                    <a class="twitter" href="https://twitter.com/<?php echo get_option( 'twitter_id' ) ?>" target="_blank">
                        <i class="fa fa-twitter"></i>
                    </a>
                    <?php 
                }
                if ( !empty(get_option('instagram_id')) )
                {
                    $sum++;
                    ?>
                    <a class="instagram" href="https://instagram.com/<?php echo get_option( 'instagram_id' ) ?>" target="_blank">
                        <i class="fa fa-instagram"></i>
                    </a>
                    <?php 
                }
                if ( !empty(get_option('custom_html')) )
                {
                    $sum++;
                    ?>
                    <a class="custom" href="<?php echo get_option( 'custom_url' ) ?>" target="_blank">
                        <i class="<?php echo get_option( 'custom_icon' ) ?>"></i>
                    </a>
                    <?php 
                }
            ?>
            </div>
            <style>
                .social_mobile_pro a, .social_mobile_pro a:focus, .social_mobile_pro a:hover { width: calc(100% / <?php echo $sum; ?>);} 
            </style>
        </div>
        <?php
    }
    ?>
    <div class="social_slider_pro"> 
    <?php
        if ( !empty(get_option('facebook_id')) ) 
        { 
            ?>
            <input id="social_slider-tabOne" type="radio" name="tabs" checked />
            <label for="social_slider-tabOne" class="facebook_icon" style="max-width: 32px;"><span>facebook</span><i class="fa fa-facebook-f"></i></label>
            <section id="social_slider-contentOne">
                <div class="facebook_box">
                    <iframe src="https://www.facebook.com/plugins/page.php?href=https://www.facebook.com/<?php echo get_option( 'facebook_id' ); ?>&tabs=timeline,events,messages&width=350&height=1080&small_header=false&adapt_container_width=false&hide_cover=false&show_facepile=true" width="350" height="1080" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true">
                    </iframe>
                </div>
            </section>
            <?php
        }

        if ( !empty(get_option('twitter_id')) ) 
        {
            ?>
            <input id="social_slider-tabTwo" type="radio" name="tabs" />
            <label for="social_slider-tabTwo" class="twitter_icon" style="max-width: 32px;"><span>twitter</span><i class="fa fa-twitter"></i></label>
            <section id="social_slider-contentTwo">
                <div class="twitter_box">
                    <a class="twitter-timeline" data-width="350" data-height="1080" href="https://twitter.com/<?php echo get_option( 'twitter_id' ); ?>">Tweets by <?php echo get_option('twitter_id'); ?></a>
                    <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
                </div>
            </section>
            <?php  
        } 
        if ( !empty(get_option('instagram_id')) ) 
        {
            ?>
            <input id="social_slider-tabThree" type="radio" name="tabs" />
            <label for="social_slider-tabThree" class="instagram_icon" style="max-width: 32px;"><span>instagram</span><i class="fa fa-instagram"></i></label>
            <section id="social_slider-contentThree">
                <div class="instagram_box">
                    <div class="instagram-widget" data-user="<?php echo get_option( 'instagram_id' ); ?>" data-header="yes" data-color="#3897f0" data-columns="3"></div>
                </div>
            </section>
            <?php  
        } 
        if ( !empty(get_option('custom_html')) )
        {
            ?>
            <input id="social_slider-tabFour" type="radio" name="tabs" />
            <label for="social_slider-tabFour" class="custom_icon" style="max-width: 32px;"><span><?php echo get_option('custom_text'); ?></span><i class="<?php echo get_option('custom_icon'); ?>"></i></label>
            <section id="social_slider-contentFour">
                <div class="custom_box">
                <?php
                    echo stripslashes( get_option('custom_html') );
                ?>
                </div>
            </section>
            <?php
        }
    ?>
    </div>
    <?php
}

add_action('wp_head', 'slider_custom_styles', 100);

function slider_custom_styles()
{
    ?>
    <style>
        <?php
        if ( trim(get_option('show_on_mobile') ) == 1 ) 
        {
            ?>
            .social_slider_pro label:first-of-type { margin-top: 15vh; }
            .social_mobile_pro .custom {background-color: <?php echo get_option('custom_color') ?>;}
            <?php
        }
        if ( trim(get_option('position') ) == 1) 
        {
            if ( trim(get_option('border-radius') ) == 1) 
            {
            ?>
            .social_slider_pro .facebook_icon, .social_slider_pro .twitter_icon, .social_slider_pro .instagram_icon, .social_slider_pro .custom_icon {border-radius: 0 7px 7px 0 !important;}
            <?php
            }
        ?>
        .social_slider_pro {left:-370px;}.social_slider_pro:hover{transform: translateX(370px);}
        .social_slider_pro .facebook_icon, .social_slider_pro .twitter_icon, .social_slider_pro .instagram_icon, .social_slider_pro .custom_icon{float:right; clear: right;right:-32px}
        <?php
        } else if ( trim(get_option('position') ) == 0) 
        {
            if ( trim(get_option('border-radius') ) == 1) 
            {
            ?>
            .social_slider_pro .facebook_icon, .social_slider_pro .twitter_icon, .social_slider_pro .instagram_icon, .social_slider_pro .custom_icon {border-radius: 7px 0 0 7px !important;}
            <?php
            }
        ?>
        .social_slider_pro { right:-370px; }.social_slider_pro:hover{transform: translateX(-370px);} 
        .social_slider_pro .facebook_icon, .social_slider_pro .twitter_icon, .social_slider_pro .instagram_icon, .social_slider_pro .custom_icon {float:left;left:-32px; clear: left;}
        <?php
        }
        ?>
        .social_slider_pro .custom_icon {
            background-color: <?php echo get_option('custom_color') ?>;
        } 
        .social_slider_pro .custom_box  {   
            border-left: 10px solid <?php echo get_option('custom_color') ?>;
            border-right: 10px solid <?php echo get_option('custom_color') ?>;
        } 
        .social_slider_pro .custom {
            background-color: <?php echo get_option('custom_color') ?>;
        } 
    </style>
    <?php 
}

add_action( 'admin_menu', 'sliderMenu' );

function sliderMenu()
{
    add_options_page( 'Responsive Social Slider Widget PRO', 'Responsive Social Slider Widget PRO', 'manage_options', 'responsive-social-slider-widget', 'sliderBackend' );
}

function additional_action_links( $links ) 
{
    $links['settings'] = '<a href="' . admin_url( '/options-general.php?page=responsive-social-slider-widget' ) . '">' . __( 'Settings' ) . '</a>';
    $links['support'] = '<a href="https://m.me/JSNetworkSolutions" target="_blank">' . __( 'Support' ) . '</a>';
    return $links;
}
   
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ),'additional_action_links', 10, 2 );

function sliderBackend()
{
    global $widgetSettings;
    if ( $_POST ) 
    {
        foreach ( $widgetSettings as $setting ) 
        {
            $save_setting = stripslashes(addslashes($_POST[$setting['id']]));
            update_option($setting['id'], $save_setting);
        }
        echo '<div class="updated fade"><p><strong>' . __('Settings saved.') . '</strong></p></div>';
    }

    echo '<form method="post">';
        settings_fields('widget-setting-fields');
        do_settings_sections('responsive-social-slider-widget');
        echo '<h1>' . get_admin_page_title() . '</h1></ br><hr>
        <table class="form-table">';
            foreach ( $widgetSettings as $setting ) 
            {
                echo '<tr valign="top"><th scope="row">' . $setting['label'] . '</th><td>';
                switch ( $setting['type'] ) 
                {
                    case 'text':
                        echo '<input type="text" name="' . $setting['id'] . '" value="' . get_option( $setting['id'] ) . '" />';
                        break;

                    case 'textarea':
                        echo '<textarea name="' . $setting['id'] . '">' . get_option( $setting['id'] ) . '</textarea>';
                        break;

                    case 'radio':
                        echo '<select name="' . $setting['id'] . '">';
                        foreach ( $setting['options'] as $optionValue => $optionName ) 
                        {
                            echo '<option value="' . $optionValue . '" ' . ( ($optionValue == get_option($setting['id']) ) ? ' selected="selected"' : '') . '>' . $optionName . '</option>';
                        }

                        echo '</select>';
                        break;
                    case 'html':
                        
                        echo '<textarea name="' . $setting['id'] . '" rows="4" cols="50">' . get_option( $setting['id'] ) . '</textarea>';
                        break;
                    case 'separator_ids':
                
                        echo '<hr>Follow these steps to find your channel ID: <a href="https://github.com/Frostbourn/wp-responsive-facebook-and-twitter-widget" target="_blank">click</a>';                       
                        break;
                    case 'separator_custom':
                    
                        echo '<hr>Find more: <a href="https://fontawesome.com/v4.7.0/icons" target="_blank">icons</a>, <a href="https://www.w3schools.com/colors/colors_picker.asp" target="_blank">colors</a>';                                      
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