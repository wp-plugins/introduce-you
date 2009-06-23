<?php

/*
Plugin Name: Introduce You!
Plugin URI: http://soluo.fr/?utm_campaign=WordPress_plugin_directory&utm_medium=web&utm_source=introduce-you
Description: Allows you to introduce individuals, one per page
Version: 0.1
Author: Thibaut CAMBERLIN
Author URI: http://soluo.fr/?utm_campaign=WordPress_plugin_directory&utm_medium=web&utm_source=introduce-you

/*  Copyright 2009 Thibaut CAMBERLIN  (email : thibaut@soluo.fr)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


// Define some constants here
define('PLUGIN_DIRECTORY', 'introduce-you');


// Load plugin domain
$locale = get_locale();

if (!empty($locale)) {
	load_textdomain( 'IntroduceYou', WP_PLUGIN_DIR . '/' . PLUGIN_DIRECTORY . '/languages/' . $locale . '.mo' );
}


function introduceYouJsTranslation()
{
	$locale = get_locale();

    $jsTranslationFileDir = WP_PLUGIN_DIR . '/' . PLUGIN_DIRECTORY . '/languages/' . $locale . '.js';
    $jsTranslationFileUrl = WP_PLUGIN_URL . '/' . PLUGIN_DIRECTORY . '/languages/' . $locale . '.js';

    if (!empty($locale) && file_exists($jsTranslationFileDir)) {
        wp_enqueue_script('introduceYouJsTranslationFile', $jsTranslationFileUrl);
        
	}
}
add_action('wp_print_scripts','introduceYouJsTranslation');
add_action('admin_print_scripts','introduceYouJsTranslation');

/**
 * This function inserts javascript code (jQuery) into the admin header. This code allows image insertion
 */
function introduceYouAdminScript()
{
    $scriptDir = WP_PLUGIN_DIR . '/' . PLUGIN_DIRECTORY . '/js/codeJS.js';
    $scriptUrl = WP_PLUGIN_URL . '/' . PLUGIN_DIRECTORY . '/js/codeJS.js';

    if(file_exists($scriptDir)){
        wp_enqueue_script('introduceYouAdminScript', $scriptUrl); // array('introduceYouJsTranslationFile')
    }
}
add_action('admin_print_scripts','introduceYouAdminScript');


/**
 * This function displays meta boxes to page administration
 */
function introduceYouMeta()
{
	global $post, $wpdb;
	
	$IntroduceYou		= array();
    $IntroduceYou 		= unserialize(get_post_meta( $post->ID, 'IntroduceYou', true ));

	?>
	<div class="wrap" >
	<table class="form-table" style="font-size:13px">

		<tr valign="top">
			<th scope="row"><label for="IntroduceYouIsActive"><?php _e('Is Introduce Me Active ?', 'IntroduceYou'); ?></label></th>
			<td><input type="checkbox" id="IntroduceYouIsActive" name="IntroduceYou[isActive]" value="active"
			<?php if ( $IntroduceYou['isActive'] == "active" ) { echo "checked='checked'"; } ?> />
			</td>
		</tr>
		
		
		<tr valign="top">
			<th scope="row"><?php _e('Image : ', 'IntroduceYou'); ?></th>
		<td>	
		<?php if (!empty($IntroduceYou['imageId'])) { // IMAGE SET ?>

					<input type="hidden" id="IntroduceYouImageId" name="IntroduceYou[imageId]" value="<?php echo $IntroduceYou['imageId'] ?>" />
					<a style="float:left" href="<?php echo wp_get_attachment_url($IntroduceYou['imageId']) ?>" id="IntroduceYouImageTarget" rel="lightbox" target="_blank">
						<img src="<?php echo wp_get_attachment_thumb_url($IntroduceYou['imageId']) ?>"
                        alt="background Image" id="IntroduceYouImage" class="clear" />
					</a>
					<input type="text" readonly size="45" name="IntroduceYouImageUrl" id="IntroduceYouImageUrl" value="<?php echo wp_get_attachment_url($IntroduceYou['imageId']) ?>" tabindex="10" />
					<a href="media-upload.php?post_id=<?php echo  $post->ID ?>'&amp;type=image&amp;TB_iframe=true" id="add_image" class="thickbox button" tabindex="11">
						<?php _e('Change Image', 'IntroduceYou'); ?>
					</a>
		<?php	} else { // IMAGE NOT SET ?> 
					<input type="hidden" id="IntroduceYouImageId" name="IntroduceYou[imageId]" value="<?php echo $IntroduceYou['imageId'] ?>" />
					<input type="text" readonly size="45" name="IntroduceYouImageUrl" id="IntroduceYouImageUrl" value="" tabindex="10" />
					<a href="media-upload.php?post_id=<?php echo $post->ID ?>'&amp;type=image&amp;TB_iframe=true" id="add_image" class="thickbox button" tabindex="11">
						<?php _e("Upload Image...", 'IntroduceYou'); ?>
					</a>
		<?php	} ?>
		
		</td>
		</tr>
		
		
		<tr valign="top">
			<th scope="row"><label for="IntroduceYouFName"><?php _e('First Name :', 'IntroduceYou'); ?></label> </th>
			<td><input type="text" id="IntroduceYouFName" name="IntroduceYou[FName]" value="<?php echo $IntroduceYou['FName']; ?>" tabindex="15" />
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="IntroduceYouLName"><?php _e('Last Name :', 'IntroduceYou'); ?></label> </th>
			<td><input type="text" id="IntroduceYouLName" name="IntroduceYou[LName]" value="<?php echo $IntroduceYou['LName']; ?>" tabindex="16" />
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="IntroduceYouDesc"><?php _e('Description :', 'IntroduceYou'); ?></label> </th>
			<td><textarea id="IntroduceYouDesc" name="IntroduceYou[Desc]" tabindex="17" style="width:100%" ><?php echo $IntroduceYou['Desc']; ?></textarea>
			</td>
		</tr>

		<tr valign="top">
			<th scope="row"><label for="IntroduceYouUrl"><?php _e('Website/blog URL :', 'IntroduceYou'); ?></label> </th>
			<td>http://<input type="text" id="IntroduceYouUrl" name="IntroduceYou[Url]" value="<?php echo $IntroduceYou['Url']; ?>" tabindex="18" style="width:90%" />
			</td>
		</tr>

		<tr valign="top">
			<th scope="row">
			<?php if( $post->post_status == 'publish' ) { ?>
			<input type="submit" id="publish" value="<?php _e('Update Page'); ?>" class="button-primary customizeUpdate" name="save" tabindex="16" accesskey="p" />
			<?php } else { ?>
			<input type="submit" id="save-post" value="<?php _e('Save Draft'); ?>" class="button button-highlighted customizeUpdate clear" name="save" tabindex="16" />
			<input type="submit" value="<?php _e('Publish'); ?>" accesskey="p" id="publish" class="button-primary" name="publish" tabindex="17"/>
			<?php } ?>			
			</th>
			<td>&nbsp;
			</td>
		</tr>

	</table>
	</div>

	<?php
}

function introduceYouDisplay()
{
	global $post;

    $introduceYou = unserialize(get_post_meta( $post->ID, 'IntroduceYou', true ));
    
	if ( $introduceYou['isActive'] == "active" ) {
				
		echo '<ul class="IntroduceYou">';
	
		if (!empty($introduceYou['imageId'])) {
			echo '<li class="image"><img src="' . wp_get_attachment_url($introduceYou['imageId']) . '" alt="" />
                  </li>';
		}
	
		if (!empty($introduceYou['FName'])) {
			echo "<li><span>" . __('First Name :', 'IntroduceYou') . " </span>" . $introduceYou['FName']
                 . "</li>";
		}
		if (!empty($introduceYou['LName'])) {
			echo "<li><span>" . __('Last Name :', 'IntroduceYou') . " </span>" . $introduceYou['LName']
                 . "</li>";
		}
		if (!empty($introduceYou['Desc'])) {
			echo "<li><span>" . __('Description :', 'IntroduceYou') . " </span>" . $introduceYou['Desc']
                 . "</li>";
		}
		if (!empty($introduceYou['Url'])) {
			echo "<li><span>" . __('Url :', 'IntroduceYou') . ' <a href="http://' . $introduceYou['Url'] . '"> '
                  . "</span>" . $introduceYou['Url'] . "</a>
                  </li>";
		}
		echo '</ul>';
	}
}

function addStyleSheet()
{
    $cssUrl  = WP_PLUGIN_URL . '/' . PLUGIN_DIRECTORY . '/css/style.css';
    $cssPath = WP_PLUGIN_DIR . '/' . PLUGIN_DIRECTORY . '/css/style.css';
    
    if(file_exists($cssPath)) {
        // Add CSS style sheet
        wp_enqueue_style('introduceYouCss', $cssUrl);
    }
}
add_action('wp_print_styles', 'addStyleSheet');

/**
 * This function adds meta boxes to page
 */
function introduceYouMetaBox()
{
	// Check whether the 2.5 function add_meta_box exists, and if it doesn't use 2.3 functions.
	if ( function_exists('add_meta_box') ) {
		add_meta_box('introduceYou', __('Introduce You', 'IntroduceYou'), 'introduceYouMeta', 'page');
	} else {
		add_action('dbx_page_sidebar', 'IntroduceYou_option');
	}
}
add_action('admin_menu', 'introduceYouMetaBox');

/**
 * This function updates customization parameters when current page is updated
 */
function introduceYou_insert_post($postId)
{
	if ( isset( $_POST['IntroduceYou'] ) ) {
		add_post_meta( $postId, 'IntroduceYou', serialize($_POST['IntroduceYou']), true )
		or update_post_meta( $postId, 'IntroduceYou', serialize($_POST['IntroduceYou']) );
	}
}
add_action('wp_insert_post', 'introduceYou_insert_post');

/**
 * This function displays the the widget
 */
function widget_IntroduceYou_register()
{
	function widget_IntroduceYou($args)
    {
        extract($args);
        introduceYouDisplay();
	}
    register_sidebar_widget('Introduce You Widget', 'widget_IntroduceYou');
}
add_action('init', widget_IntroduceYou_register);


// End if Plugin

// Utilisé lors de l'activation/dédactivation uniquement
//register_activation_hook(__FILE__, 'introduceYou_activation');
//register_deactivation_hook(__FILE__, 'introduceYou_deactivation');

?>