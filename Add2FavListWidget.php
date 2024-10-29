<?php
require_once("WPP_Widget.php");
require_once("Add2Fav.php");
class Add2favListWidget extends WPP_Widget {
	//
	// your regular constructor
	public function __construct() {
		parent::__construct(
			'add2fav_list_widget',
			'Add2Fav List Favorites Widget',
			array( 'description' => $this->_TT(
				"List the user URLs added to favorites."))
		);
	}
	//
	// define your fields:
	//
	public function getFields(){
		 return array(
			array(                            	
				'key' => 'add2fav_list_title',
				'label'	=> 'Widget Title',
				'defvalue' => '',
				'opts' => '',
				'title'=>'A title for this widget'
			),
			array(                            	
				'key' => 'add2fav_list_height',
				'label'	=> 'Widget Max. Height',
				'defvalue' => '',
				'opts' => '',
				'title'=>'The max height in pixels for this widget, optional. Beyond this size the widget will scroll. Leave empty for no-scroll.'
			),
			array(
				'key' => 'add2fav_list_icon',
				'label' => 'Icon',
				'defvalue' => 'star',
				'opts' => array(
					'star'=>"Star",
					'heart' => "Heart",
				),
				'title'=>'The icon to be shown',
			),
			array(                            	
				'key' => 'add2fav_css_name_list',
				'label'	=> 'CSS Class Name (opt)',
				'defvalue' => '',
				'opts' => '',
				'title'=>'An extra css class name to be attached to this widget'
			),
		 );
	}
	//
	// build your widget here: 
	//
	public function widget( $args, $instance ) {
		extract( $args );
		$w = new Add2Fav();
		$iconname = $this->getSafeValue($instance,'add2fav_list_icon','star');
		$cssname = $this->getSafeValue($instance,'add2fav_css_name_list','');
		$height = $this->getSafeValue($instance,'add2fav_list_height','');
		$title = apply_filters( 'widget_title',
				$this->getSafeValue($instance,'add2fav_list_title'));
		echo $before_widget;
		if ( ! empty( $title ) )
			echo $before_title . $title . $after_title;
		echo $w->buildTagList($iconname, $cssname, $height);
		echo $after_widget;
	}
}
