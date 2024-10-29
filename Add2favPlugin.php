<?php
/*
Plugin Name: Add to Favorites Plugin (Add2Fav)
Plugin URI: http://ascinformatix.com/www/plugins/#add2fav
Description: Add urls to users favorites. Setting up it via Settings/Add2Fav menu in your wordpress admin side bar.
Author: Christian Salazar <christiansalazarh@gmail.com>
Version: 1.0
Author URI: http://goo.gl/RDWjG
*/
include_once("Add2favWidget.php");
include_once("Add2FavListWidget.php");
function add2fav_menu() {
	add_options_page(
		'Add to Favorites, Plugin Options', 
		'Add2Fav', 
		'manage_options', 
		'add2fav_uid', 
		'add2fav_plugin_options' 
	);
}
function add2fav_plugin_options() {
    if (!current_user_can('manage_options'))
      wp_die( __('You do not have sufficient permissions to access this page.') );
	$w = new Add2Fav();

    $hidden_field_name = 'add2fav_hidden';

    $data_field_name1 = $w->key_name_add;
    $opt_val1 = $w->getOptionAdd();
    $data_field_name2 = $w->key_name_rem;
    $opt_val2 = $w->getOptionRem();
    $data_field_name3 = $w->key_name_reg;
    $opt_val3 = $w->getOptionReg();
    $data_field_name4 = $w->key_name_off;
    $opt_val4 = $w->getOptionOff();

    if( isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' ) {
        $opt_val1 = $_POST[ $data_field_name1 ];
        $opt_val2 = $_POST[ $data_field_name2 ];
        $opt_val3 = $_POST[ $data_field_name3 ];
        $opt_val4 = $_POST[ $data_field_name4 ];
        $w->saveOptionAdd($opt_val1);
        $w->saveOptionRem($opt_val2);
        $w->saveOptionReg($opt_val3);
        $w->saveOptionOff($opt_val4);
		?><div class="updated"><p><strong>
		<?php _e('settings saved.', 'menu-test' ); ?></strong></p></div><?php
    }

    echo '<div class="wrap">';
    echo "<h1>" . __( 'Add2Fav Plugin', 'menu-test' ) . "</h1>";
    ?>
	
	<p>
	<b>Add2Fav plugin allow you to handle Favorites URL's in your pages and posts.</b>
	<p>You can either add this components into your pages/blogs/sidebars and so on via widgets or using a shortcode</p>
	<ul>
		<li style='background-color: #eee; padding: 3px;'><pre>[add2fav-link]</pre>
			This shortcode can be inserted into your content 
			(page/post/sidebar/etc), it will display a link for 
			'Add to Favorites', this link is automatically updated 
			depending on the current user state.
		</li>
	</ul>
	</p>
	
	<hr/>

	<h2>Add2Fav Settings</h2>

	<form name="form1" method="post" action="">
	<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">

	<p><?php _e("Add to Favorites Label:", 'menu-test' ); ?> 
	<br/><input type="text" name="<?php echo $data_field_name1; ?>" 
	value="<?php echo $opt_val1; ?>" size="30" maxlength="50">
	</p>

	<p><?php _e("Remove from Favorites Label:", 'menu-test' ); ?> 
   	<br/><input type="text" name="<?php echo $data_field_name2; ?>" 
	value="<?php echo $opt_val2; ?>" size="30" maxlength="50">
   	</p>

	<div style='padding: 5px; background-color: #eee; border: 1px dotted #aaa;'>

	<p><?php _e("Label when user is logged off:", 'menu-test' ); ?> 
   	<br/><input type="text" name="<?php echo $data_field_name3; ?>" 
	value="<?php echo $opt_val3; ?>" size="30" maxlength="50">
	<i>When empty it hides the widget when user is logged off</i>
   	</p>

	<p><?php _e("URL when user is logged off:", 'menu-test' ); ?> 
   	<br/><input type="text" name="<?php echo $data_field_name4; ?>" 
	value="<?php echo $opt_val4; ?>" size="30" maxlength="50">
   	</p>

	</div>

	<hr />
	<p class="submit">
	<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
	</p>
	</form>
	</div>
<?php
}
function add2fav_ajax_query(){
	header("Content-type: application/json");
	$w = new Add2Fav();
	$w->prepare($_POST['url']);
	echo $w->exportJs();
	exit();
}
function add2fav_ajax_action(){
	header("Content-type: application/json");
	$w = new Add2Fav();
	$w->prepare($_POST['url']);
	$w->toggle();
	echo $w->exportJs();
	exit();
}
function add2fav_scripts(){
	$pdir = untrailingslashit(plugins_url( '', __FILE__ ));
	wp_enqueue_script("add2fav_code",$pdir."/add2fav.js",array("jquery"));
	wp_register_style("add2fav_styles",$pdir."/add2fav.css",array(),"20130101","all");
	wp_enqueue_style("add2fav_styles");
}
function add2fav_head_scripts(){
	$pdir = untrailingslashit(plugins_url( '', __FILE__ ));
	echo "
		<script>
		var add2fav_ajax_object = "
			.json_encode(array(
				'ajaxurl'=>admin_url('admin-ajax.php'),
				'plugin_dir'=>$pdir,
			)).";
		</script>
	";
}
//[add2fav-link]
function add2fav_shortcode($atts, $content=null){
    extract( shortcode_atts( array(
		'icon' => 'star',
		'label_add' => 'Add to Favorites',
		'label_rem' => 'Remove from Favorites',
		'cssname' => 'add2fav',
    ), $atts ) );
	$w = new Add2Fav();
	return $w->buildTag($icon, $cssname);
}
//[add2fav-list]  options: title, icon, height, cssname
function add2fav_list_shortcode($atts, $content=null){
	 extract( shortcode_atts( array(
		'icon' => '@',
		'title' => '@',
		'height' => '@',
		'cssname' => '@',
	 ), $atts ) );
	$instance = "";
	if($icon != '@')
		$instance .= 'add2fav_list_icon='.$icon;
	if($cssname != '@')
		$instance .= '&add2fav_css_name_list='.$cssname;
	if($height != '@')
		$instance .= '&add2fav_list_height='.$height;
	if($title != '@')
		$instance .= '&add2fav_list_title='.$title;
	$instance = trim($instance," &");
	the_widget("Add2FavListWidget",$instance);
}
function add2fav_widget_loader(){
	register_widget('Add2favWidget');
	register_widget('Add2FavListWidget');
}
add_action( 'admin_menu', 'add2fav_menu' );
add_action('wp_enqueue_scripts', 'add2fav_scripts');
add_shortcode('add2fav-link','add2fav_shortcode');
add_shortcode('add2fav-list','add2fav_list_shortcode');
add_filter('widget_text', 'do_shortcode');
add_action('widgets_init', 'add2fav_widget_loader');
add_filter('wp_head', 'add2fav_head_scripts');
add_action('wp_ajax_add2fav_query','add2fav_ajax_query');
add_action('wp_ajax_nopriv_add2fav_query','add2fav_ajax_query');
add_action('wp_ajax_add2fav_action','add2fav_ajax_action');
add_action('wp_ajax_nopriv_add2fav_action','add2fav_ajax_action');
