<?php
/**
 * Password field.
 *
 * @package    WPForms
 * @author     WPForms
 * @since      1.0.0
 * @license    GPL-2.0+
 * @copyright  Copyright (c) 2016, WPForms LLC
*/
class WPForms_Field_Password extends WPForms_Field {

	/**
	 * Primary class constructor.
	 *
	 * @since 1.0.0
	 */
	public function init() {

		// Define field type information
		$this->name  = __( 'Password', 'wpforms' );
		$this->type  = 'password';
		$this->icon  = 'fa-lock';
		$this->order = 9;
		$this->group = 'fancy';

		// Set confirmation status to option wrapper class
		add_filter( 'wpforms_builder_field_option_class', array( $this, 'field_option_class' ), 10, 2 );
	}

	/**
	 * Field options panel inside the builder.
	 *
	 * @since 1.0.0
	 * @param array $field
	 */
	public function field_options( $field ) {

		//--------------------------------------------------------------------//
		// Basic field options
		//--------------------------------------------------------------------//
		
		// Options open markup
		$this->field_option( 'basic-options', $field, array( 'markup' => 'open' ) );
		
		// Label
		$this->field_option( 'label', $field );
		
		// Description
		$this->field_option( 'description', $field );
		
		// Required toggle
		$this->field_option( 'required', $field );

		// Confirmation toggle
		$confirm_check = $this->field_element( 
			'checkbox', 
			$field, 
			array( 
				'slug'    => 'confirmation',
				'value'   => isset( $field['confirmation'] ) ? '1' : '0',
				'desc'    => __( 'Enable Password Confirmation', 'wpforms' ),
				'tooltip' => __( 'Check this option ask the user to provide their password twice.', 'wpforms' ),
			), 
			false
		);
		$this->field_element( 'row', $field, array( 'slug' => 'confirmation', 'content' => $confirm_check ) );

		// Options close markup
		$this->field_option( 'basic-options', $field, array( 'markup' => 'close' ) );

		//--------------------------------------------------------------------//
		// Advanced field options
		//--------------------------------------------------------------------//

		// Options open markup
		$this->field_option( 'advanced-options', $field, array( 'markup' => 'open' ) );
		
		// Size
		$this->field_option( 'size', $field );
		
		// Placeholder
		$this->field_option( 'placeholder', $field );

				// Confirmation Placeholder
		$confirm_placehlder_label = $this->field_element(
			'label', 
			$field, 
			array( 
				'slug'    => 'confirmation_placeholder', 
				'value'   => __( 'Confirmation Placeholder Text', 'wpforms' ), 
				'tooltip' => __( 'Enter text for the confirmation field placeholder.', 'wpforms' )
			), 
			false
		);
		$confirm_placehlder_text = $this->field_element( 
			'text',  
			$field, 
			array( 
				'slug' => 'confirmation_placeholder',
				'value' => !empty( $field['confirmation_placeholder'] ) ? esc_attr( $field['confirmation_placeholder'] ) : '',
			), 
			false
		);
		$this->field_element( 'row', $field, array( 'slug' => 'confirmation_placeholder', 'content' => $confirm_placehlder_label . $confirm_placehlder_text ) );
		
		// Hide Label
		$this->field_option( 'label_hide', $field );

		// Hide Sub-labels
		$this->field_option( 'sublabel_hide', $field );
		
		// Default value
		$this->field_option( 'default_value', $field );
		
		// Custom CSS classes
		$this->field_option( 'css', $field );
		
		// Options close markup
		$this->field_option( 'advanced-options', $field, array( 'markup' => 'close' ) );
	}

	/**
	 * Add class to field options wrapper to indicate if field confirmation is enabled.
	 *
	 * @since 1.3.0
	 * @param string $class
	 * @param array $field
	 * @return string
	 */
	function field_option_class( $class, $field ) {

		if ( 'password' == $field['type'] ) {
			if ( isset( $field['confirmation'] ) ) {
				$class = 'wpforms-confirm-enabled';
			} else {
				$class = 'wpforms-confirm-disabled';
			}
		}
		return $class;
	}

	/**
	 * Field preview inside the builder.
	 *
	 * @since 1.0.0
	 * @param array $field
	 */
	public function field_preview( $field ) {

		$placeholder         = !empty( $field['placeholder'] ) ? esc_attr( $field['placeholder'] ) : '';
		$confirm             = !empty( $field['confirmation'] ) ? 'enabled' : 'disabled';
		$confirm_placeholder = !empty( $field['confirmation_placeholder'] ) ? esc_attr( $field['confirmation_placeholder'] ) : '';
		
		// Label
		$this->field_preview_option( 'label', $field );

		printf( '<div class="wpforms-confirm wpforms-confirm-%s">', $confirm );
			
			echo '<div class="wpforms-confirm-primary">';
				printf( '<input type="password" placeholder="%s" class="primary-input" disabled>', $placeholder );
				printf( '<label class="wpforms-sub-label">%s</label>', __( 'Password' , 'wpforms') );
			echo '</div>';
			
			echo '<div class="wpforms-confirm-confirmation">';
				printf( '<input type="password" placeholder="%s" class="secondary-input" disabled>', $confirm_placeholder );
				printf( '<label class="wpforms-sub-label">%s</label>', __( 'Confirm Password' , 'wpforms') );
			echo '</div>';

		echo '</div>';

		// Description
		$this->field_preview_option( 'description', $field );
	}

	/**
	 * Field display on the form front-end.
	 *
	 * @since 1.0.0
	 * @param array $field
	 * @param array $form_data
	 */
	public function field_display( $field, $field_atts, $form_data ) {

		// Setup and sanitize the necessary data
		$field                    = apply_filters( 'wpforms_' . $this->type . '_field_display', $field, $field_atts, $form_data );
		$field_placeholder        = !empty( $field['placeholder']) ? esc_attr( $field['placeholder'] ) : '';
		$field_required           = !empty( $field['required'] ) ? ' required' : '';
		$field_class              = implode( ' ', array_map( 'sanitize_html_class', $field_atts['input_class'] ) );
		$field_id                 = implode( ' ', array_map( 'sanitize_html_class', $field_atts['input_id'] ) );
		$field_value              = !empty( $field['default_value'] ) ? esc_attr( apply_filters( 'wpforms_process_smart_tags', $field['default_value'], $form_data ) ) : '';
		$field_sublabel           = !empty( $field['sublabel_hide'] ) ? 'wpforms-sublabel-hide' : '';
		$field_data               = '';
		$confirmation             = !empty( $field['confirmation'] ) ? true : false;
		$confirmation_placeholder = !empty( $field['confirmation_placeholder']) ? esc_attr( $field['confirmation_placeholder'] ) : '';
		$form_id                  = $form_data['id'];

		if ( !empty( $field_atts['input_data'] ) ) {
			foreach ( $field_atts['input_data'] as $key => $val ) {
			  $field_data .= ' data-' . $key . '="' . $val . '"';
			}
		}

		// Normal field
		if ( ! $confirmation ) :

			// Primary password field
			printf(
				'<input type="password" name="wpforms[fields][%d]" id="%s" class="%s" value="%s" placeholder="%s" %s %s>',
				$field['id'],
				$field_id,
				$field_class,
				$field_value,
				$field_placeholder,
				$field_required,
				$field_data
			);

		// Field confirmation enabled
		else:

			printf( '<div class="wpforms-field-row %s">', $field_class );

				// Primary password field
				echo '<div class="wpforms-field-row-block wpforms-one-half wpforms-first">';

					$primary_class  = 'wpforms-field-password-primary';
					$primary_class .= !empty( $field_required ) ? ' wpforms-field-required' : '';
					$primary_class .= !empty( wpforms()->process->errors[$form_id][$field['id']]['primary'] ) ? ' wpforms-error' : '';

					printf( 
						'<input type="password" name="wpforms[fields][%d][primary]" id="%s" class="%s" value="" placeholder="%s" %s>',
						$field['id'],
						$field_id,
						$primary_class,
						$field_placeholder,
						$field_required
					);

					if ( !empty( wpforms()->process->errors[$form_id][$field['id']]['primary'] ) ) {
						printf( '<label id="wpforms-%d-field_%d-error" class="wpforms-error" for="wpforms-field_%d">%s</label>', $form_id, $field['id'], $field['id'], esc_html( wpforms()->process->errors[$form_id][$field['id']]['primary'] ) );
					}

					printf( '<label for="wpforms-%d-field_%d" class="wpforms-field-sublabel %s">%s</label>', $form_id, $field['id'], $field_sublabel, __( 'Password', 'wpforms' ) );

				echo '</div>';

				// Secondary password field for confirmation
				echo '<div class="wpforms-field-row-block wpforms-one-half">';

					$confirmation_class  = 'wpforms-field-password-confirmation';
					$confirmation_class .= !empty( $field_required ) ? ' wpforms-field-required' : '';
					$confirmation_class .= !empty( wpforms()->process->errors[$form_id][$field['id']]['confirmation'] ) ? ' wpforms-error' : '';

					printf( 
						'<input type="password" name="wpforms[fields][%d][confirmation]" id="%s" class="%s" value="" placeholder="%s" data-rule-confirm-msg="%s" data-rule-confirm="#%s" %s>',
						$field['id'],
						"wpforms-{$form_id}-field_{$field['id']}-confirmation",
						$confirmation_class,
						$confirmation_placeholder,
						esc_attr( apply_filters( 'wpforms_password_confirmation_msg', __( 'Password does not match.', 'wpforms') ) ),
						$field_id,
						$field_required
					);

					if ( !empty( wpforms()->process->errors[$form_id][$field['id']]['confirmation'] ) ) {
						printf( '<label id="wpforms-%d-field_%d-confirmation-error" class="wpforms-error" for="wpforms-field_%d-confirmation">%s</label>', $form_id, $field['id'], $field['id'], esc_html( wpforms()->process->errors[$form_id][$field['id']]['confirmation'] ) );
					}

					printf( '<label for="wpforms-%d-field_%d-confirmation" class="wpforms-field-sublabel %s">%s</label>', $form_id, $field['id'], $field_sublabel, __( 'Confirm Password', 'wpforms' ) );

				echo '</div>';

			echo '</div>';

		endif;
	}

	/**
	 * Validates field on form submit.
	 *
	 * @since 1.3.0
	 * @param int $field_id
	 * @param array $field_submit
	 * @param array $form_data
	 */
	public function validate( $field_id, $field_submit, $form_data ) {

		$form_id = $form_data['id'];

		// Confirmation Disabled
		if ( empty( $form_data['fields'][$field_id]['confirmation'] ) ) {
			
			// Basic required check - If field is marked as required, check for entry data
			if ( !empty( $form_data['fields'][$field_id]['required'] ) && empty( $field_submit ) ) {
				wpforms()->process->errors[$form_id][$field_id] = apply_filters( 'wpforms_required_label', __( 'This field is required', 'wpforms' ) );
			}
		
		// Confirmation enabled
		} else {

			// Password required check - If field is marked as required, check for entry data
			if ( !empty( $form_data['fields'][$field_id]['required'] ) && empty( $field_submit['primary'] ) ) {
				wpforms()->process->errors[$form_id][$field_id]['primary'] = apply_filters( 'wpforms_required_label', __( 'This field is required', 'wpforms' ) );
			}

			// Confirmation password required check
			if ( !empty( $form_data['fields'][$field_id]['required'] ) && empty( $field_submit['confirmation'] ) ) {
				wpforms()->process->errors[$form_id][$field_id]['confirmation'] = apply_filters( 'wpforms_required_label', __( 'This field is required', 'wpforms' ) );
			}

			// Make sure fields match
			if ( !empty( $field_submit['confirmation'] ) && !empty( $field_submit['primary'] ) && $field_submit['primary'] != $field_submit['confirmation']  ) {
				wpforms()->process->errors[$form_id][$field_id]['confirmation'] = __( 'Email does not match', 'wpforms' );
			}
		}
	}

	/**
	 * Formats and sanitizes field.
	 *
	 * @since 1.3.0
	 * @param int $field_id
	 * @param array $field_submit
	 * @param array $form_data
	 */
	public function format( $field_id, $field_submit, $form_data ) {

		if ( empty( $form_data['fields'][$field_id]['confirmation'] ) ) {
			$value = sanitize_text_field( $field_submit );
		} else {
			$value = sanitize_text_field( $field_submit['primary'] );
		}

		$name  = !empty( $form_data['fields'][$field_id]['label'] ) ? sanitize_text_field( $form_data['fields'][$field_id]['label'] ) : '';

		wpforms()->process->fields[$field_id] = array(
			'name'  => $name,
			'value' => $value,
			'id'    => absint( $field_id ),
			'type'  => $this->type,
		);
	}
}
new WPForms_Field_Password;