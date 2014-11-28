<?php
/*
Plugin Name: WPML ACF Relations
Plugin URI: https://github.com/mcguffin/wpml-acf-relations
Description: Fully customise WordPress edit screens with powerful fields. Boasting a professional interface and a powerful API, it’s a must have for any web developer working with WordPress. Field types include: Wysiwyg, text, textarea, image, file, select, checkbox, page link, post object, date picker, color picker, repeater, flexible content, gallery and more!
Version: 1.0.0
Author: Jörn Lund
Author URI: https://github.com/mcguffin
License: GPL
*/



class WPML_ACF_Relations {
	private static $_instance = null;
	
	/**
	 *	Will hold meta_key names for all relational ACF fields
	 */
	private $watched_meta_keys = array();

	/**
	 *	Will hold meta_key names for all relational ACF fields
	 */
	private $acf_types_to_watch = array( 'gallery' , 'file' , 'image' , 'page_link' , 'post_object' , 'relationship' );
	
	/**
	 *	Singleton pattern
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) )
			self::$_instance = new self;
		return self::$_instance;
	}
	private function __clone() {}
	
	
	/**
	 *	Singleton pattern
	 */
	private function __construct() {
		add_action( 'init' , array( &$this , 'init' ) );
		add_filter( 'icl_make_duplicate' , array( &$this , 'icl_make_duplicate' ) , 10 , 4 );
	}
	
	/**
	 *	Setup relational ACF fields.
	 */
	function init() {
		// Get all ACF fields...
		$all_acf_fields = get_posts(array(
			'post_type' => 'acf-field',
			'posts_per_page' => -1,
		));

		// ... and add the relational ones to our watchlist.
		foreach( $all_acf_fields as $field ) {
			$field_settings = unserialize( $field->post_content );
			if ( in_array($field_settings['type'] , $this->acf_types_to_watch ) )
				$this->watched_meta_keys[] = $field->post_excerpt; // add meta key name
		}
	}
	/**
	 *	Fired after WPML has duplicated an object
	 */
	function icl_make_duplicate( $master_post_id, $lang, $post_array, $translated_id ) {
		global $sitepress;
		$language_details = $sitepress->get_element_language_details($master_post_id);
		
		foreach ( $this->watched_meta_keys as $meta_key ) {
			$old_meta_value = $meta_value = get_post_meta( $translated_id , $meta_key , true );
			
			if ( is_array( $meta_value ) ) {
				foreach ( $meta_value as $k => $v )
					$meta_value[$k] = $this->get_translated_post_id( $v , $lang );
			} else {
				$meta_value = $this->get_translated_post_id( $meta_value , $lang );
			}

			if ( $old_meta_value != $meta_value )
				update_post_meta( $translated_id , $meta_key , $meta_value );
		}
	}
	/**
	 *	Get id of translated post from master post.
	 */
	function get_translated_post_id( $object_id , $lang ) {
		global $sitepress;
		if ( is_numeric( $object_id ) ) {
			$master_post = get_post( $object_id );
			$trid = $sitepress->get_element_trid( $object_id , 'post_' . $master_post->post_type );
			$translations = $sitepress->get_element_translations($trid);
			if ( isset( $translations[$lang] ) )
				$object_id = $translations[$lang]->element_id;
		}
		return $object_id;
	}
	
// 	private function _log( ) {
// 		$args = func_get_args();
// 		$log = fopen( '/www/vhosts/shan-fan.local/data.log' , 'a');
// 		fwrite($log,"---------------");
// 		fwrite($log,"\n");
// 		foreach ( $args as $arg ) {
// 			fwrite($log,var_export($arg,true));
// 			fwrite($log,"\n");
// 		}
// 		fclose($log);
// 	}
}

WPML_ACF_Relations::instance();

		
