<?php
/*
Plugin Name: Sina Form
Description: Simple Contact Form
Version: 1.0
Text Domain: sina-form
*/

function sina_form_html() {
	?>
	<form action="<?php echo esc_url( $_SERVER['REQUEST_URI'] ); ?>" method="post">
	<p>
	 <?php _e( '*Your Name', 'sina-form' ); ?>
	 <br>
	<input type="text" name="cf-name" pattern="[a-zA-Z0-9 ]+" value="<?php echo isset( $_POST['cf-name'] ) ? esc_attr( $_POST['cf-name'] ) : ''; ?>" size="40" />
	</p>
	<p>
	<?php _e( '*Your Email', 'sina-form' ); ?>
	<br/>
	<input type="email" name="cf-email" value="<?php echo isset( $_POST['cf-email'] ) ? esc_attr( $_POST['cf-email'] ) : ''; ?>" size="40" />
	</p>
	<p>
	<?php _e( '*Subject', 'sina-form' ); ?>
	<br>
	<input type="text" name="cf-subject" pattern="[a-zA-Z ]+" value="<?php echo isset( $_POST['cf-subject'] ) ? esc_attr( $_POST['cf-subject'] ) : ''; ?>" size="40" />
	</p>
	<p>
	<?php _e( '*Your Message', 'sina-form' ); ?>
	<br/>
	<textarea rows="10" cols="35" name="cf-message"><?php echo isset( $_POST['cf-message'] ) ? esc_attr( $_POST['cf-message'] ) : ''; ?></textarea>
	</p>
	<p><input type="submit" name="cf-submitted" value="Send"></p>
	</form>
	<?php
}

function sina_form_deliver_mail() {

	// if the submit button is clicked, send the email
	if ( isset( $_POST['cf-submitted'] ) ) {

		// sanitize form values
		$name    = sanitize_text_field( $_POST['cf-name'] );
		$email   = sanitize_email( $_POST['cf-email'] );
		$subject = sanitize_text_field( $_POST['cf-subject'] );
		$message = esc_textarea( $_POST['cf-message'] );

		// get the blog administrator's email address
		$to = get_option( 'admin_email' );

		$headers = "From: $name <$email>" . "\r\n";

		// If email has been process for sending, display a success message
		if ( wp_mail( $to, $subject, $message, $headers ) ) {
			?>
			<div>
			<p>Thanks for contacting me, expect a response soon.</p>
			</div>
			<?php
		} else {
			_e( 'An unexpected error occurred', 'sina-form' );
		}
	}
}

function sina_form_shortcode() {
	ob_start();
	sina_form_html();
	sina_form_deliver_mail();

	return ob_get_clean();
}

add_shortcode( 'sina_form', 'sina_form_shortcode' );
?>