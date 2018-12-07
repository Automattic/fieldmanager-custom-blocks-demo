<?php
/*
Plugin Name: Fieldmanager Custom Blocks Demo
Plugin URI: https://vip.wordpress.com/plugins/fieldmanager/
Description: Examples for using the Fieldmanager plugin
Author: Mikey Arce
Version: 0.1
Author URI: http://vip.wordpress.com
*/

if ( ! defined( 'FM_VERSION' ) ) {
	return;
}

if ( !class_exists( 'FM_Demo_Custom_Blocks' ) ) :

class FM_Demo_Custom_Blocks {

	public static $instance;

	public function __construct() {
		add_action( 'init', array( $this, 'register' ) );
		add_action( 'after_setup_theme' ,array( $this, 'init' ) );
	}

	public function setup() {
		add_action( 'fm_post_demo-blocks', array( $this, 'init' ) );
	}

	public function register() {
		register_post_type( 'demo-blocks',
			array(
				'labels' => array(
					'name' => __( 'Custom Blocks' ),
					'singular_name' => __( 'Custom Block' )
				),
				'public' => true,
				'has_archive' => true,
			)
		);
	}
	public function init() {
		$fm = new Fieldmanager_Group(
			array(
				'name'           => 'block_repeater',
				'limit'          => 0,
				'add_more_label' => 'Add another content block',
				'sortable'       => true,
				'collapsible'    => true,
				'collapsed'      => false,
				'label'          => 'Block',
				'label_macro'    => array( 'Content Block: %s', 'content_type_select' ),
				'group_is_empty' => function( $values ) {
					return empty( $values['content_type_select'] ); },
				'children'       => array(
		
					// Content Type Select
					'content_type_select' => new Fieldmanager_Select(
						'Block Type',
						array(
							'label'   => 'Lead In Type',
							'options' => array(
								'textarea'        		=> 'Text Area',
								'textarea_with_image' 	=> 'Text Area with Image',
								'nested_block' 			=> 'Nested Blocks',
								'blockquote'     		=> 'Blockquote',
							),
						)
					),
		
					// "TextArea" Block
					'textarea'           => new Fieldmanager_Group(
						array(
							'label'       => 'Text Area Block',
							'collapsible' => true,
							'collapsed'   => false,
							'display_if'  => array(
								'src'   => 'content_type_select',
								'value' => 'textarea',
							),
							'children'    => array(
								'body'     => new Fieldmanager_RichTextArea(
									array(
										'attributes' => array( 'style' => 'width:100%' ),
										'editor_settings' => array(
											'media_buttons' => false,
										),
									)
								),
							),
						)
					),
		
					// "TextArea with Image" Block
					'textarea_with_image'    => new Fieldmanager_Group(
						array(
							'label'       => 'Text Area with Image Block',
							'collapsible' => true,
							'collapsed'   => false,
							'display_if'  => array(
								'src'   => 'content_type_select',
								'value' => 'textarea_with_image',
							),
							'children'    => array(
								'body'       => new Fieldmanager_RichTextArea(
									array(
										'label' => 'Text',
										'attributes' => array( 'style' => 'width:100%' ),
										'editor_settings' => array(
											'media_buttons' => false,
										),
									)
								),
								'image'      => new Fieldmanager_Media( 'Image', array( 'description' => 'Make sure your image is at least 500 pixels wide') ),
							),
						)
					),
		
					// Text w/Stacked Media
					'nested_block'    => new Fieldmanager_Group(
						array(
							'label'       => 'Nested Repeated Blocks',
							'collapsible' => true,
							'collapsed'   => false,
							'display_if'  => array(
								'src'   => 'content_type_select',
								'value' => 'nested_block',
							),
							'children'    => array(
								'body'       => new Fieldmanager_RichTextArea(
									array(
										'label' => 'Text',
										'attributes' => array( 'style' => 'width:100%' ),
										'editor_settings' => array(
											'media_buttons' => false,
										),
									)
								),
								'child_block'      => new Fieldmanager_Group(
									array(
										'label'    => 'Child Block',
										'collapsible' => true,
										'collapsed' => false,
										'children' => array(
		
											'grandchild_block' => new Fieldmanager_Group(
												array(
													'label' => 'Grandchild Block',
													'limit' => 5,
													'add_more_label' => 'Add another Media Item',
													'sortable'       => true,
													'collapsible'    => true,
													'collapsed'    => false,
													'children' => array(
		
														'media_conditional' => new Fieldmanager_Select(
															array(
																'label'   => 'Media Type',
																'options' => array(
																	'grandchild_image' => 'Image',
																	'grandchild_text'    => 'Text',
																),
															)
														),
		
														// Image
														'grandchild_image' => new Fieldmanager_Media(
															'Image',
															array(
																'label'        => 'Image',
																'description'  => 'Image Size: 480px wide by any height',
																'preview_size' => 'medium',
																'display_if'   => array( // This works on most, but not all field types
																	'src'   => 'media_conditional', // The name of the field which triggers the hide/show. Must be in the same set of children.
																	'value' => 'grandchild_image', // The value which determines if this field should be shown
																),
															)
														),
		
														// YouTube Video ID
														'grandchild_text'   => new Fieldmanager_Textfield(
															array(
																'label'       => 'Text',
																'attributes'  => array( 'style' => 'width:100%' ),
																'display_if'  => array( // This works on most, but not all field types
																	'src'   => 'media_conditional', // The name of the field which triggers the hide/show. Must be in the same set of children.
																	'value' => 'grandchild_text', // The value which determines if this field should be shown
																),
															)
														),
													),
												)
											),
										),
									)
								),
							),
						)
					),
		
					'blockquote'          => new Fieldmanager_Group(
						array(
							'label'      => 'Blockquote Content Block',
							'display_if' => array(
								'src'   => 'content_type_select',
								'value' => 'blockquote',
							),
							'children'   => array(
		
								// Blockquote
								'blockquote_text' => new Fieldmanager_Textfield(
									array(
										'label' => 'Blockquote Text',
										'attributes' => array( 'style' => 'width:100%' ),
									)
								),
								'blockquote_author' => new Fieldmanager_Textfield(
									array(
										'label' => 'Blockquote Author',
										'attributes' => array( 'style' => 'width:100%' ),
									)
								),
							),
						)
					),
				),
			)
		);
		$fm->add_meta_box( 'Block Repeater', 'demo-blocks' );
	}
}

$var = new FM_Demo_Custom_Blocks();

endif;
