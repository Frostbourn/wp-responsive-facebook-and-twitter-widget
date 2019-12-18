<?php

/*
Plugin Name: Responsive Facebook and Twitter Widget
Description: Display Facebook and Twitter on your website in beautiful responsive box which slides in from page edge in a handy way!
Plugin URI: https://github.com/Frostbourn/wp-responsive-facebook-and-twitter-widget
AUthor: Jakub Skowroński
Author URI: https://jakubskowronski.com
Version: 1.2.5
License: GPLv2 or later
*/

defined('ABSPATH') or die('No script kiddies please!');

$lbox_opcje = array(
    array(
        'nazwa' => 'show_on_mobile',
        'typ' => 'radio',
        'default' => '1',
        'label' => 'Show on mobile devices',
        'opis' => 'Display Facebook tab on mobile devices',
        'options' => array(
            0 => 'No',
            1 => 'Yes'
        )
    ) ,
    array(
        'nazwa' => 'border-radius',
        'typ' => 'radio',
        'default' => '1',
        'label' => 'Rounded icons',
        'opis' => 'Change the border radius of the icons',
        'options' => array(
            0 => 'No',
            1 => 'Yes'
        )
    ) ,
    array(
        'nazwa' => 'facebook',
        'typ' => 'radio',
        'default' => '1',
        'label' => 'Facebook',
        'opis' => 'Display Facebook tab',
        'options' => array(
            0 => 'No',
            1 => 'Yes'
        )
    ) ,
    array(
        'nazwa' => 'profile_id',
        'typ' => 'text',
        'default' => 'facebook',
        'label' => 'Page ID',
        'opis' => 'Facebook Page ID'
    ) ,
    array(
        'nazwa' => 'twitter',
        'typ' => 'radio',
        'default' => '1',
        'label' => 'Twitter',
        'opis' => 'Display Twitter tab',
        'options' => array(
            0 => 'No',
            1 => 'Yes'
        )
    ) ,
    array(
        'nazwa' => 'twitter_login',
        'typ' => 'text',
        'default' => 'twitter',
        'label' => 'Twitter ID',
        'opis' => 'Twitter account ID'
    ) ,
);
add_action('wp_head', 'jsftrwlikebox_up');

function jsftrwlikebox_up()
{
    wp_enqueue_style('style', plugin_dir_url(__FILE__) . 'css/style.min.css');
    wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
}

add_action('wp_enqueue_scripts', 'jsftrwlikebox_up');
add_action('wp_footer', 'jsftrwlikebox_down');

function jsftrwlikebox_down()
{
    if (trim(get_option('show_on_mobile')) == 1) {
        // ______________________MOBILE________________________

        if (trim(get_option('facebook')) == 1) {
            $t = 1;
        } else {
            $t = 0;
        }

        if (trim(get_option('twitter')) == 1) {
            $f = 1;
        } else {
            $f = 0;
        }

        $sum = $f + $t; ?>
			<style>
			@media only screen and (min-device-width:0) and (max-width:768px) {
				.social_slider {
				display: none !important;
				}
					#social_mobile {
							display: inline !important;
					}
					#social_mobile a{
					position: relative;
					float: left;
					width: calc(100% / <?php
                echo $sum; ?>);
					display:list-item;
					list-style-type: none;
				}

				#social_mobile a:focus, #social_mobile a:hover {
					width: calc(100% / <?php
                echo $sum; ?>);
					-moz-transition-property: none;
					-webkit-transition-property: none;
					-o-transition-property: none;
					transition-property: none;
				}
				}
				<?php if (trim(get_option('border-radius')) == 1) { ?>
					.social_slider .facebook_icon, .social_slider .twitter_icon	{
								border-radius: 5px 0 0 5px !important;
						}
						<?php } ?>

			</style>
			<div id="social_mobile"><div class="top-left">
			<?php
                if (trim(get_option('facebook')) == 1) { ?>
			<a class="facebook pop-upper" href="https://www.facebook.com/<?php
                    echo get_option('profile_id'); ?>" target="_blank"><i class="fa fa-facebook"></i></a>
			<?php
                    }

        if (trim(get_option('twitter')) == 1) { ?>
			<a class="twitter pop-upper" href="https://twitter.com/<?php
                    echo get_option('twitter_login'); ?>" target="_blank"><i class="fa fa-twitter"></i></a>
			<?php
                    } ?>
			</div></div>
			<?php
    }

    // ______________________DESKTOP________________________ ?> <div class="social_slider" style="top: 10vh;"> <?php
        if (trim(get_option('facebook')) == 1) { ?>
									<input id="tab1" type="radio" name="tabs" checked />
									<label for="tab1" class="facebook_icon"  style="max-width: 32px;"></label>
									<section id="content1">
										<div class="facebook_box">
											<div id="fb-root"></div>
												<script>(function(d, s, id) {
												  var js, fjs = d.getElementsByTagName(s)[0];
												  if (d.getElementById(id)) return;
												  js = d.createElement(s); js.id = id;
												  js.src = 'https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.1&autoLogAppEvents=1';
												  fjs.parentNode.insertBefore(js, fjs);
												}(document, 'script', 'facebook-jssdk'));</script>
												<div class="fb-page" data-href="https://www.facebook.com/<?php
                echo get_option('profile_id'); ?>" data-tabs="timeline" data-width="350" data-height="494" data-small-header="false" data-adapt-container-width="false" data-hide-cover="false" data-show-facepile="true"></div>
												
											 </div>
								</section>
			<?php
            }

    if (trim(get_option('twitter')) == 1) {
        ?>
<input id="tab2" type="radio" name="tabs" />
<label for="tab2" class="twitter_icon" style="max-width: 32px;<?php
            if (trim(get_option('facebook')) == 0) { ?> top: 50px; right:32px;<?php
                } else {
                } ?>"></label>
<?php
            if (trim(get_option('facebook')) == 1) { ?>
	<section id="content2">
<?php
                } else { ?><section id="content2" style="display: block;"> <?php
                } ?>

<div class="twitter_box">
<a class="twitter-timeline" data-width="350" data-height="494" href="https://twitter.com/<?php
            echo get_option('twitter_login'); ?>">Tweets by <?php
            echo get_option('twitter_login'); ?></a> <script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>
			 </div></section>
	<?php
    } ?>
			</div>
			<span class="copyrightlink">Designed with <span style="color: #f44336;">❤</span> by <a title="Projektowanie stron internetowych" target="_blank" href="http://jakubskowronski.com" rel="noopener noreferrer">jakubskowronski.com</a></span>

			<?php
}

add_action('admin_init', 'jsftrwlikebox_settings');

function jsftrwlikebox_settings()
{
    global $lbox_opcje;
    foreach ($lbox_opcje as $opcja) {
        add_option($opcja['nazwa'], $opcja['default']);
        register_setting('jslbox-settings', $opcja['nazwa']);
    }
}

add_action('admin_menu', 'jsftrwlikebox_menu');

function jsftrwlikebox_menu()
{
    add_plugins_page('Responsive Facebook and Twitter Widget', 'Responsive Facebook and Twitter Widget', 'manage_options', 'responsive-facebook-and-twitter-widget', 'jsftrwlikebox_opcje');
}

function jsftrwlikebox_opcje()
{
    global $lbox_opcje;
    if ($_POST) {
        foreach ($lbox_opcje as $opcja) {
            $jslikebox_nazwa = sanitize_text_field($_POST[$opcja['nazwa']]);
            update_option($opcja['nazwa'], $jslikebox_nazwa);
        }

        echo '<div class="updated"><p><strong>' . __('Options saved.') . '</strong></p></div>';
    }

    echo '<form method="post">';
    settings_fields('jslbox-settings');
    do_settings_sections('responsive-facebook-and-twitter-widget');
    echo '<h3>Follow these steps to find your Facebook & Twitter ID: <a href="https://github.com/Frostbourn/wp-responsive-facebook-and-twitter-widget" target="_blank">Tutorial</a></h3>
	<table class="form-table">';
    foreach ($lbox_opcje as $opcja) {
        echo '<tr valign="top"><th scope="row">' . $opcja['label'] . '</th><td>';
        switch ($opcja['typ']) {
        case 'text':
            echo '<input type="text" name="' . $opcja['nazwa'] . '" value="' . get_option($opcja['nazwa']) . '" />';
            break;

        case 'textarea':
            echo '<textarea cols="30" rows="5" name="' . $opcja['nazwa'] . '">' . get_option($opcja['nazwa']) . '</textarea>';
            break;

        case 'radio':
            echo '<select name="' . $opcja['nazwa'] . '">';
            foreach ($opcja['options'] as $optionn => $optionv) {
                echo '<option value="' . $optionn . '" ' . (($optionn == get_option($opcja['nazwa'])) ? ' selected="selected"' : '') . '>' . $optionv . '</option>';
            }

            echo '</select>';
            break;
            }

        echo '</td></tr>';
    }

    echo '</table>

		<p class="submit">
		<input type="submit" class="button-primary" value="' . __('Save Changes') . '" />
		</p>

	</form>
</div>';
}

?>