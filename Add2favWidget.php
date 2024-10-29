<?php
require_once("WPP_Widget.php");
require_once("Add2Fav.php");
class Add2favWidget extends WPP_Widget {
	//
	// your regular constructor
	public function __construct() {
		parent::__construct(
			'add2favwidget',
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
				'key' => 'add2fav_title',
				'label'	=> 'Widget Title',
				'defvalue' => '',
				'opts' => '',
				'title'=>'A title for this widget'
			),
			array(
				'key' => 'add2fav_icon',
				'label' => 'Icon',
				'defvalue' => 'star',
				'opts' => array(
					'star'=>"Star",
					'heart' => "Heart",
				),
				'title'=>'The icon to be shown',
			),
			array(                            	
				'key' => 'add2fav_css_name',
				'label'	=> 'CSS Class Name (opt)',
				'defvalue' => 'add2fav',
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
		$iconname = $this->getSafeValue($instance,'add2fav_icon','star');
		$cssname = $this->getSafeValue($instance,'add2fav_css_name','add2fav');

		$title = apply_filters( 'widget_title',
				$this->getSafeValue($instance,'add2fav_title'));		
		echo $before_widget;
		if ( ! empty( $title ) )
			echo $before_title . $title . $after_title;

		echo $w->buildTag($iconname, $cssname);

		echo $after_widget;
	}
}
