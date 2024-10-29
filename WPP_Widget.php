<?php
/**
 	An extension for WP_Widget

	this class offer a better way to handle widget attributes provided
	by the end-user.

	usage:

	1. create your own class and extend it from WPP_Widget
	2. define your constructor.
	3. define your fields.(override the getFields() function)
	4. optionally, override too the text i18n function: _TT()

--begin sample code--
require_once("WPP_Widget.php");
class Add2favWidget extends WPP_Widget {
	//
	// your regular constructor
	public function __construct() {
		parent::__construct(
			'my_add2fav_widget_id',
			'Add2Favorites Widget',
			array( 'description' => $this->_TT(
				"Add URLs to your users favorites."))
		);
	}
	//
	// define your fields:
	//
	public function getFields(){
		 return array(
			array(                            	
				'key' => 'my_title',
				'label'	=> 'Title',
				'defvalue' => '',
				'opts' => '',
				'title'=>'The widget title'
			),
			array(                            	
				'key' => 'my_fav_color',
				'label'	=> 'Favorite Color',
				'defvalue' => 'yellow',
				'opts' => array('blue'=>'Light Blue','yellow'=>'Yellow 1'),
				'title'=>'select one color'
			),
			
		 );
	}
	//
	// build your widget here: 
	//	
	public function widget( $args, $instance ) {
		echo "<p>Color: ".$this->getSafeValue($instance, 'my_fav_color')."</p>";
		echo "<p>Title:".$this->getStoredValue('my_title')."</p>";
	}
}
--end sample code--

 * @author Christian Salazar H. <christiansalazarh@gmail.com>
 * @license http://opensource.org/licenses/bsd-license.php
 */
abstract class WPP_Widget extends WP_Widget {
	public function __construct($id, $name, $args) {
		parent::__construct($id,$name,$args);
	}
	public function _TT($text){
		return $text;
	}
	/**
		returns a field definition for usage into the form.
		return array(
			array(
				'key' => 'pwh_default_search', 
				'label'	=> 'Search',
				'defvalue' => '',
				'opts' => '',  // this must be '' for text plain inputs or
							   // an array for a select:
							   // ie: array('1'=>'one', '2'=>'two')
				'title'=>'A default search string'
			),	
		);
	 */
	public function getFields(){
		return array();
	}

	public function getSafeValue($instance,$name, $def=''){
		if(!isset($instance[$name]))
			return $def;
		$v = trim($instance[$name]);
		if(empty($v))
			return $def;
		return $v;
	}
	
	public function getStoredValue($name, $def=''){
		$v = trim(get_option($name));
		if(empty($v))
			return $def;
		return $v;
	}

	public function setStoredValue($name, $value){
		update_option($name, trim($value));
	}

	public function widget( $args, $instance ) {}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		foreach($this->getFields() as $field){
			$key = $field['key'];
			$value = trim(strip_tags($new_instance[$key]));
			$instance[$key]=$value;
			$this->setStoredValue($key, $value);
		}
		return $instance;
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		foreach($this->getFields() as $field){
			$key = $field['key'];
			$title = esc_attr($field['title']);
			$value = esc_attr($this->getStoredValue($key, $field['defvalue']));
			$id = $this->get_field_id($key);
			$name = $this->get_field_name($key);
			echo "<p>\r\n<!-- begin field: $key -->\r\n";
			
			echo "<label for='$id'>"
				.$this->_TT($field['label'])."&nbsp;:</label>";
			if($field['opts']=='') {
				// is plain text
				echo "<input class='widefat' id='$id' name='$name'"
					." type='text' title='$title' "
					." value='".$value."'"
					."/>";
			}else{
				$options = "";
				foreach($field['opts'] as $key=>$val){
					$selected_tag=($key == $value) ? 'selected' : '';
					$options .= "<option value='$key' $selected_tag>$val</option>";
				}
				// is a select
				echo "<select class='widefat' id='$id' name='$name'>"
					.$options	
					."</select>"
				;				
			}
			echo "</p>\r\n<!-- end field $key -->\r\n";
		}
	}
} // class WPP_Widget 
