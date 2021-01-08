<?php

/*
Plugin Name: Responsive Social Slider Widget
Description: Display Facebook and Twitter on your website in beautiful responsive box which slides in from page edge in a handy way!
Plugin URI: https://jsns.eu
AUthor: Jakub Skowroński
Author URI: https://jakubskowronski.com
Version: 1.5.2
License: GPLv2 or later
*/

if ( !function_exists( 'rfatw_fs' ) ) {
    // Create a helper function for easy SDK access.
    function rfatw_fs()
    {
        global  $rfatw_fs ;
        
        if ( !isset( $rfatw_fs ) ) {
            // Include Freemius SDK.
            require_once dirname( __FILE__ ) . '/freemius/start.php';
            $rfatw_fs = fs_dynamic_init( array(
                'id'             => '7477',
                'slug'           => 'responsive-facebook-and-twitter-widget',
                'type'           => 'plugin',
                'public_key'     => 'pk_b79816fce1978b98b4ced585cb173',
                'is_premium'     => false,
                'premium_suffix' => 'Proffesional',
                'has_addons'     => false,
                'has_paid_plans' => true,
                'trial'          => array(
                'days'               => 7,
                'is_require_payment' => false,
            ),
                'menu'           => array(
                'slug'    => 'responsive-facebook-and-twitter-widget',
                'support' => false,
                'parent'  => array(
                'slug' => 'options-general.php',
            ),
            ),
                'is_live'        => true,
            ) );
        }
        
        return $rfatw_fs;
    }
    
    // Init Freemius.
    rfatw_fs();
    // Signal that SDK was initiated.
    do_action( 'rfatw_fs_loaded' );
}

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
$widgetSettings = array(
    array(
    'id'      => 'show_on_mobile_devices',
    'type'    => 'radio',
    'default' => '1',
    'label'   => 'Show on mobile devices',
    'desc'    => 'Display Facebook tab on mobile devices',
    'options' => array(
    0 => 'No',
    1 => 'Yes',
),
),
    array(
    'id'      => 'position',
    'type'    => 'radio',
    'default' => '1',
    'label'   => 'Position',
    'desc'    => 'Position of the sidebar',
    'options' => array(
    0 => 'Right',
    1 => 'Left',
),
),
    array(
    'id'      => 'border-radius',
    'type'    => 'radio',
    'default' => '1',
    'label'   => 'Rounded icons',
    'desc'    => 'Change the border radius of the icons',
    'options' => array(
    0 => 'No',
    1 => 'Yes',
),
),
    array(
    'id'      => 'buttons-label',
    'type'    => 'radio',
    'default' => '1',
    'label'   => 'Show button label',
    'desc'    => '',
    'options' => array(
    0 => 'No',
    1 => 'Yes',
),
),
    array(
    'id'      => 'fa-cdn',
    'type'    => 'radio',
    'default' => '1',
    'label'   => 'Load Font Awesome library (Set to YES if any icons missing)',
    'desc'    => 'Set to YES if any icons missing',
    'options' => array(
    0 => 'No',
    1 => 'Yes',
),
),
    array(
    'id'      => 'ids_separator',
    'type'    => 'separator_ids',
    'default' => '',
    'label'   => 'Social channels ID\'s',
    'desc'    => '',
),
    array(
    'id'      => 'facebookId',
    'type'    => 'text',
    'default' => 'facebook',
    'label'   => 'Facebook Page ID',
    'desc'    => 'Facebook Page ID',
),
    array(
    'id'      => 'twitterId',
    'type'    => 'text',
    'default' => 'twitter',
    'label'   => 'Twitter ID',
    'desc'    => 'Twitter account ID',
)
);
add_action( 'wp_head', 'sliderScripts' );
add_action( 'wp_enqueue_scripts', 'sliderScripts' );
function sliderScripts()
{
    wp_enqueue_style( 'social-widget-style', plugin_dir_url( __FILE__ ) . 'css/style.min.css' );
    if ( trim( get_option( 'fa-cdn' ) ) == 1 ) {
        wp_enqueue_style( 'social-widget-font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css' );
    }
}

add_action( 'wp_footer', 'sliderFrontend' );
function sliderFrontend()
{
    
    if ( trim( get_option( 'show_on_mobile_devices' ) ) == 1 ) {
        ?>
<div class="social_mobile_pro">
    <div class="top-left">
        <?php 
        $sum = 0;
        
        if ( !empty(get_option( 'facebookId' )) ) {
            $sum++;
            $iPhone = stripos( $_SERVER['HTTP_USER_AGENT'], "iPhone" );
            $iPad = stripos( $_SERVER['HTTP_USER_AGENT'], "iPad" );
            $Android = stripos( $_SERVER['HTTP_USER_AGENT'], "Android" );
            
            if ( $iPhone || $iPad ) {
                $fb_url = 'fb://profile/' . get_option( 'facebookId' );
            } else {
                
                if ( $Android ) {
                    $fb_url = 'fb://page/' . get_option( 'facebookId' );
                } else {
                    $fb_url = 'https://facebook.com/' . get_option( 'facebookId' );
                }
            
            }
            
            ?>
        <a class="facebook" href="<?php 
            echo  $fb_url ;
            ?>" target="_blank">
            <i class="fab fa-facebook-f"></i>
        </a>
        <?php 
        }
        
        
        if ( !empty(get_option( 'twitterId' )) ) {
            $sum++;
            ?>
        <a class="twitter" href="https://twitter.com/<?php 
            echo  get_option( 'twitterId' ) ;
            ?>" target="_blank">
            <i class="fab fa-twitter"></i>
        </a>
        <?php 
        }
        
        ?>
    </div>
    <style>
    .social_mobile_pro a,
    .social_mobile_pro a:focus,
    .social_mobile_pro a:hover {
        width: calc(100% / <?php 
        echo  $sum ;
        ?>);
    }
    </style>
</div>
<?php 
    }
    
    ?>
<div class="social_slider_pro">
    <?php 
    
    if ( !empty(get_option( 'facebookId' )) ) {
        ?>
    <input id="social_slider-tabOne" type="radio" name="tabs" checked />
    <label for="social_slider-tabOne" class="facebook_icon"><span>facebook</span><i
            class="fab fa-facebook-f"></i></label>
    <section id="social_slider-contentOne">
        <div class="facebook_box">
            <iframe
                src="https://www.facebook.com/plugins/page.php?href=https://www.facebook.com/<?php 
        echo  get_option( 'facebookId' ) ;
        ?>&tabs=timeline,events,messages&width=350&height=1080&small_header=false&adapt_container_width=false&hide_cover=false&show_facepile=true"
                width="350" height="1080" style="border:none;overflow:hidden" scrolling="no" frameborder="0"
                allowTransparency="true">
            </iframe>
        </div>
    </section>
    <?php 
    }
    
    
    if ( !empty(get_option( 'twitterId' )) ) {
        ?>
    <input id="social_slider-tabTwo" type="radio" name="tabs" />
    <label for="social_slider-tabTwo" class="twitter_icon"><span>twitter</span><i class="fab fa-twitter"></i></label>
    <section id="social_slider-contentTwo">
        <div class="twitter_box">
            <a class="twitter-timeline" data-width="350" data-height="1080"
                href="https://twitter.com/<?php 
        echo  get_option( 'twitterId' ) ;
        ?>">Tweets by
                <?php 
        echo  get_option( 'twitterId' ) ;
        ?></a>
            <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
        </div>
    </section>
    <?php 
    }
    
    ?>
</div>
<?php 
}

add_action( 'wp_head', 'slider_custom_styles', 100 );
function slider_custom_styles()
{
    ?>
<style>
<?php 
    
    if ( trim( get_option( 'show_on_mobile_devices' ) ) == 1 ) {
        ?>.social_slider_pro label:first-of-type {
        margin-top: 15vh;
    }

    .social_mobile_pro .custom {
        background-color: <?php 
        echo  get_option( 'custom_color' ) ;
        ?>;
    }

    <?php 
    }
    
    
    if ( trim( get_option( 'buttons-label' ) ) == 0 ) {
        ?>.social_slider_pro label {
        width: 40px !important;
        height: 40px !important;
    }

    .social_slider_pro label span {
        display: none !important;
    }

    .social_slider_pro i {
        width: 19px !important;
        height: 21px !important;
        display: flex !important;
        justify-content: center !important;
        align-items: center !important;
        color: #fff !important;
        position: relative !important;
        font-size: 18px !important;
        background-color: unset !important;
    }

    .social_slider_pro .facebook_icon {
        box-shadow: 0px 0px 28px rgb(0 0 0 / 38%), 0px 5px 0px #3487d2;
    }

    .social_slider_pro .twitter_icon {
        box-shadow: 0px 0px 28px rgb(0 0 0 / 38%), 0px 5px 0px #23a1d7;
    }

    .social_slider_pro .instagram_icon {
        box-shadow: 0px 0px 28px rgb(0 0 0 / 38%), 0px 5px 0px #4942cf;
    }

    .social_slider_pro .pinterest_icon {
        box-shadow: 0px 0px 28px rgb(0 0 0 / 38%), 0px 5px 0px #da0021
    }

    .social_slider_pro .custom_icon {
        box-shadow: 0px 0px 28px rgb(0 0 0 / 38%), 0px 5px 0px <?php 
        echo  get_option( 'custom_color' ) ;
        ?>;
    }

    <?php 
        
        if ( trim( get_option( 'position' ) ) == 1 ) {
            ?>.social_slider_pro .facebook_icon,
        .social_slider_pro .twitter_icon,
        .social_slider_pro .instagram_icon,
        .social_slider_pro .pinterest_icon,
        .social_slider_pro .custom_icon {
            right: -40px !important;
        }

        <?php 
        } else {
            if ( trim( get_option( 'position' ) ) == 0 ) {
                ?>.social_slider_pro .facebook_icon,
        .social_slider_pro .twitter_icon,
        .social_slider_pro .instagram_icon,
        .social_slider_pro .pinterest_icon,
        .social_slider_pro .custom_icon {
            left: -40px !important;
        }

        <?php 
            }
        }
    
    }
    
    
    if ( trim( get_option( 'position' ) ) == 1 ) {
        if ( trim( get_option( 'border-radius' ) ) == 1 ) {
            ?>.social_slider_pro .facebook_icon,
        .social_slider_pro .twitter_icon,
        .social_slider_pro .instagram_icon,
        .social_slider_pro .pinterest_icon,
        .social_slider_pro .custom_icon {
            border-radius: 0 7px 7px 0 !important;
        }

        <?php 
        }
        ?>.social_slider_pro {
        left: -370px;
    }

    .social_slider_pro:hover {
        transform: translateX(370px);
    }

    .social_slider_pro .facebook_icon,
    .social_slider_pro .twitter_icon,
    .social_slider_pro .instagram_icon,
    .social_slider_pro .pinterest_icon,
    .social_slider_pro .custom_icon {
        float: right;
        clear: right;
        right: -32px;
    }

    <?php 
    } else {
        
        if ( trim( get_option( 'position' ) ) == 0 ) {
            if ( trim( get_option( 'border-radius' ) ) == 1 ) {
                ?>.social_slider_pro .facebook_icon,
        .social_slider_pro .twitter_icon,
        .social_slider_pro .instagram_icon,
        .social_slider_pro .pinterest_icon,
        .social_slider_pro .custom_icon {
            border-radius: 7px 0 0 7px !important;
        }

        <?php 
            }
            ?>.social_slider_pro {
        right: -370px;
    }

    .social_slider_pro:hover {
        transform: translateX(-370px);
    }

    .social_slider_pro .facebook_icon,
    .social_slider_pro .twitter_icon,
    .social_slider_pro .instagram_icon,
    .social_slider_pro .pinterest_icon,
    .social_slider_pro .custom_icon {
        float: left;
        left: -32px;
        clear: left;
    }

    <?php 
        }
    
    }
    
    ?>.social_slider_pro .custom_icon {
    background-color: <?php 
    echo  get_option( 'custom_color' ) ;
    ?>;
}

.social_slider_pro .custom_box {
    border-left: 10px solid <?php 
    echo  get_option( 'custom_color' ) ;
    ?>;
    border-right: 10px solid <?php 
    echo  get_option( 'custom_color' ) ;
    ?>;
}

.social_slider_pro .custom {
    background-color: <?php 
    echo  get_option( 'custom_color' ) ;
    ?>;
}
</style>
<?php 
}

add_action( 'admin_menu', 'sliderMenu' );
function sliderMenu()
{
    add_options_page(
        'Responsive Social Slider Widget',
        'Responsive Social Slider Widget',
        'manage_options',
        'responsive-facebook-and-twitter-widget',
        'sliderBackend'
    );
}

function additional_action_links( $links )
{
    $links['settings'] = '<a href="' . admin_url( '/options-general.php?page=responsive-facebook-and-twitter-widget' ) . '">' . __( 'Settings' ) . '</a>';
    return $links;
}

add_filter(
    'plugin_action_links_' . plugin_basename( __FILE__ ),
    'additional_action_links',
    10,
    2
);
function sliderBackend()
{
    global  $widgetSettings ;
    
    if ( $_POST ) {
        foreach ( $widgetSettings as $setting ) {
            $save_setting = htmlentities( stripslashes( $_POST[$setting['id']] ) );
            update_option( $setting['id'], $save_setting );
        }
        echo  '<div class="updated fade"><p><strong>' . __( 'Settings saved.' ) . '</strong></p></div>' ;
    }
    
    echo  '<style>#wpfooter {position: relative !important;}</style>
        <form method="post">' ;
    settings_fields( 'widget-setting-fields' );
    do_settings_sections( 'responsive-facebook-and-twitter-widget' );
    echo  '<h1>' . get_admin_page_title() . ' Settings</h1></ br><hr>' ;
    if ( rfatw_fs()->is_not_paying() ) {
        echo  '<p><a href="' . rfatw_fs()->get_upgrade_url() . '" style="font-size: 16px; color: #00ac1a; font-weight: 600;  text-decoration: none;">' . __( 'Upgrade now to get all premium features unlocked', 'my-text-domain' ) . '</a></p>' ;
    }
    echo  '<table class="form-table">' ;
    foreach ( $widgetSettings as $setting ) {
        echo  '<tr valign="top"><th scope="row">' . $setting['label'] . '</th><td>' ;
        switch ( $setting['type'] ) {
            case 'text':
                echo  '<input type="text" name="' . $setting['id'] . '" value="' . get_option( $setting['id'] ) . '" />' ;
                break;
            case 'textarea':
                echo  '<textarea name="' . $setting['id'] . '">' . get_option( $setting['id'] ) . '</textarea>' ;
                break;
            case 'radio':
                echo  '<select name="' . $setting['id'] . '">' ;
                foreach ( $setting['options'] as $optionValue => $optionName ) {
                    echo  '<option value="' . $optionValue . '" ' . (( $optionValue == get_option( $setting['id'] ) ? ' selected="selected"' : '' )) . '>' . $optionName . '</option>' ;
                }
                echo  '</select>' ;
                break;
            case 'html':
                echo  '<textarea name="' . $setting['id'] . '" rows="4" cols="50">' . get_option( $setting['id'] ) . '</textarea>' ;
                break;
            case 'separator_ids':
                echo  '<hr>1. Get numerical Facebook ID here: <a href="https://lookup-id.com" target="_blank">click</a><br />2. Twitter/Instagram/Pinterest is what comes after the www.twitter.com/ not the whoule URL' ;
                break;
            case 'separator_custom':
                echo  '<hr>Find more: <a href="https://fontawesome.com/icons?d=gallery" target="_blank">icons</a>, <a href="https://www.w3schools.com/colors/colors_picker.asp" target="_blank">colors</a>' ;
                break;
        }
        echo  '</td></tr>' ;
    }
    echo  '
            </table>
                <p class="submit">
                    <input type="submit" class="button-primary" value="' . __( 'Save Changes' ) . '" />
                </p>
	    </form>
    </div>' ;
}
