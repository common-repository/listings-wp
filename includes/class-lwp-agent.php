<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Listings_Wp_Agent Class
 *
 * @since 1.0.0
 */
class Listings_Wp_Agent {

	public function __construct() {
		add_action( 'show_user_profile', array( $this, 'agent_meta_fields' ), 1 );
		add_action( 'edit_user_profile', array( $this, 'agent_meta_fields' ), 1 );
		add_action( 'personal_options_update', array( $this, 'save_agent_meta_fields' ), 1 );
		add_action( 'edit_user_profile_update', array( $this, 'save_agent_meta_fields' ), 1 );

		// image fields
		add_action( 'show_user_profile', array( $this, 'agent_img_fields' ), 2 );
		add_action( 'edit_user_profile', array( $this, 'agent_img_fields' ), 2 );
		add_action( 'personal_options_update', array( $this, 'save_img_meta' ), 2 );
		add_action( 'edit_user_profile_update', array( $this, 'save_img_meta' ), 2 );
		
	}

	/**
	 * Get Address Fields for the edit user pages.
	 *
	 * @return array Fields to display which are filtered through listings-wp_customer_meta_fields before being returned
	 */
	public function get_customer_meta_fields() {
		$show_fields = apply_filters('listings_wp_agent_meta_fields', array(
			'agent_profile' => array(
				'title' => __( 'Agent Profile Information', 'listings-wp' ),
				'fields' => array(
					'title_position' => array(
						'label'       => __( 'Title/Position', 'listings-wp' ),
						'description' => ''
					),
					'phone' => array(
						'label'       => __( 'Office Phone', 'listings-wp' ),
						'description' => ''
					),
					'mobile' => array(
						'label'       => __( 'Mobile/Cell Phone', 'listings-wp' ),
						'description' => ''
					),
					'facebook' => array(
						'label'       => __( 'Facebook', 'listings-wp' ),
						'description' => ''
					),
					'twitter' => array(
						'label'       => __( 'Twitter', 'listings-wp' ),
						'description' => ''
					),
					'google' => array(
						'label'       => __( 'Google Plus', 'listings-wp' ),
						'description' => ''
					),
					'linkedin' => array(
						'label'       => __( 'LinkedIn', 'listings-wp' ),
						'description' => ''
					),
					'youtube' => array(
						'label'       => __( 'YouTube', 'listings-wp' ),
						'description' => ''
					),
					'specialties' => array(
						'label'       => __( 'Specialties', 'listings-wp' ),
						'description' => '',
						'type'        => 'textarea',
					),
					'awards' => array(
						'label'       => __( 'Awards', 'listings-wp' ),
						'description' => '',
						'type'        => 'textarea',
					),
				)
			),

		) );
		return $show_fields;
	}

	/**
	 * Show Address Fields on edit user pages.
	 *
	 * @param WP_User $user
	 */
	public function agent_meta_fields( $user ) {


		$show_fields = $this->get_customer_meta_fields();

		foreach ( $show_fields as $fieldset ) :
			?>
			<h3><?php echo $fieldset['title']; ?></h3>
			<table class="form-table">
				<?php
				foreach ( $fieldset['fields'] as $key => $field ) :
					?>
					<tr>
						<th><label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $field['label'] ); ?></label></th>
						<td>
							<?php if ( ! empty( $field['type'] ) && 'select' == $field['type'] ) : ?>

								<select name="<?php echo esc_attr( $key ); ?>" id="<?php echo esc_attr( $key ); ?>" class="<?php echo ( ! empty( $field['class'] ) ? $field['class'] : '' ); ?>" style="width: 25em;">
									<?php
										$selected = esc_attr( get_user_meta( $user->ID, $key, true ) );
										foreach ( $field['options'] as $option_key => $option_value ) : ?>
										<option value="<?php echo esc_attr( $option_key ); ?>" <?php selected( $selected, $option_key, true ); ?>><?php echo esc_attr( $option_value ); ?></option>
									<?php endforeach; ?>
								</select>

							<?php elseif ( ! empty( $field['type'] ) && 'textarea' == $field['type'] ) : ?>

									<textarea name="<?php echo esc_attr( $key ); ?>" id="<?php echo esc_attr( $key ); ?>" rows="5" cols="30"><?php echo esc_textarea( get_user_meta( $user->ID, $key, true ) ); ?></textarea>

							<?php else : ?>

								<input type="text" name="<?php echo esc_attr( $key ); ?>" id="<?php echo esc_attr( $key ); ?>" value="<?php echo esc_attr( get_user_meta( $user->ID, $key, true ) ); ?>" class="<?php echo ( ! empty( $field['class'] ) ? $field['class'] : 'regular-text' ); ?>" />

							<?php endif; ?>

							<br/>
							<span class="description"><?php echo wp_kses_post( $field['description'] ); ?></span>
						</td>
					</tr>
					<?php
				endforeach;
				?>
			</table>
			<?php
		endforeach;
	}

	/**
	 * Save Address Fields on edit user pages.
	 *
	 * @param int $user_id User ID of the user being saved
	 */
	public function save_agent_meta_fields( $user_id ) {
		$save_fields = $this->get_customer_meta_fields();

		foreach ( $save_fields as $fieldset ) {

			foreach ( $fieldset['fields'] as $key => $field ) {

				if ( isset( $_POST[ $key ] ) ) {
					update_user_meta( $user_id, $key, $_POST[ $key ] );
				}
			}
		}
	}




	/**
	 * Show the new image field in the user profile page.
	 *
	 * @param object $user User object.
	 */
	public function agent_img_fields( $user ) {
		if ( ! current_user_can( 'upload_files' ) ) {
			return;
		}

		// vars
		$url             = get_the_author_meta( 'listings_wp_meta', $user->ID );
		$upload_url      = get_the_author_meta( 'listings_wp_upload_meta', $user->ID );
		$upload_edit_url = get_the_author_meta( 'listings_wp_upload_edit_meta', $user->ID );
		$button_text     = $upload_url ? __( 'Change Current Image', 'listings-wp' ) : __( 'Upload New Image', 'listings-wp' );

		if ( $upload_url ) {
			$upload_edit_url = get_site_url() . $upload_edit_url;
		}
		?>

		<div id="listings_wp_container">

			<table class="form-table">
				<tr>
					<th><label for="listings_wp_meta"><?php _e( 'Agent Profile Photo', 'listings-wp' ); ?></label></th>
					<td>
						<!-- Outputs the image after save -->
						<div id="current_img">
							<?php if ( $upload_url ): ?>
								<img class="listings-wp-current-img" src="<?php echo esc_url( $upload_url ); ?>"/>

								<div class="edit_options uploaded">
									<a class="remove_img">
										<span><?php _e( 'Remove', 'listings-wp' ); ?></span>
									</a>

									<a class="edit_img" href="<?php echo esc_url( $upload_edit_url ); ?>" target="_blank">
										<span><?php _e( 'Edit', 'listings-wp' ); ?></span>
									</a>
								</div>
							<?php elseif ( $url ) : ?>
								<img class="listings-wp-current-img" src="<?php echo esc_url( $url ); ?>"/>
								<div class="edit_options single">
									<a class="remove_img">
										<span><?php _e( 'Remove', 'listings-wp' ); ?></span>
									</a>
								</div>
							<?php else : ?>
								<img class="listings-wp-current-img placeholder"
								     src="<?php echo esc_url( LISTINGSWP_PLUGIN_URL . 'assets/images/mystery-man.jpg' ); ?>"/>
							<?php endif; ?>
						</div>

						<!-- Select an option: Upload to WPMU or External URL -->
						<div id="listings_wp_options">
							<input type="radio" id="upload_option" name="img_option" value="upload" class="tog" checked>
							<label for="upload_option"><?php _e( 'Upload New Image', 'listings-wp' ); ?></label><br>

							<input type="radio" id="external_option" name="img_option" value="external" class="tog">
							<label for="external_option"><?php _e( 'Use External URL', 'listings-wp' ); ?></label><br>
						</div>

						<!-- Hold the value here if this is a WPMU image -->
						<div id="listings_wp_upload">
							<input class="hidden" type="hidden" name="listings_wp_placeholder_meta" id="listings_wp_placeholder_meta"
							       value="<?php echo esc_url( LISTINGSWP_PLUGIN_URL . 'assets/images/mystery-man.jpg' ); ?>"/>
							<input class="hidden" type="hidden" name="listings_wp_upload_meta" id="listings_wp_upload_meta"
							       value="<?php echo esc_url_raw( $upload_url ); ?>"/>
							<input class="hidden" type="hidden" name="listings_wp_upload_edit_meta" id="listings_wp_upload_edit_meta"
							       value="<?php echo esc_url_raw( $upload_edit_url ); ?>"/>
							<input id="uploadimage" type='button' class="listings_wp_wpmu_button button"
							       value="<?php _e( esc_attr( $button_text ), 'listings-wp' ); ?>"/>
							<br/>
						</div>

						<!-- Outputs the text field and displays the URL of the image retrieved by the media uploader -->
						<div id="listings_wp_external">
							<input class="regular-text" type="text" name="listings_wp_meta" id="listings_wp_meta"
							       value="<?php echo esc_url_raw( $url ); ?>"/>
						</div>
						<p class="description">
							<?php _e( 'Update Profile to save your changes.', 'listings-wp' ); ?>
						</p>
					</td>
				</tr>
			</table><!-- end form-table -->
		</div> <!-- end #listings_wp_container -->

		<?php
		// Enqueue the WordPress Media Uploader.
		wp_enqueue_media();
	}


	/**
	 * Save the new user CUPP url.
	 *
	 * @param int $user_id ID of the user's profile being saved.
	 */
	function save_img_meta( $user_id ) {
		if ( ! current_user_can( 'upload_files', $user_id ) ) {
			return;
		}

		$values = array(
			// String value. Empty in this case.
			'listings_wp_meta' => filter_input( INPUT_POST, 'listings_wp_meta', FILTER_SANITIZE_STRING ),

			// File path, e.g., http://3five.dev/wp-content/plugins/custom-user-profile-photo/img/placeholder.gif.
			'listings_wp_upload_meta' => filter_input( INPUT_POST, 'listings_wp_upload_meta', FILTER_SANITIZE_URL ),

			// Edit path, e.g., /wp-admin/post.php?post=32&action=edit&image-editor.
			'listings_wp_upload_edit_meta' => filter_input( INPUT_POST, 'listings_wp_upload_edit_meta', FILTER_SANITIZE_URL ),
		);

		foreach ( $values as $key => $value ) {
			update_user_meta( $user_id, $key, $value );
		}
	}


}


return new Listings_Wp_Agent();