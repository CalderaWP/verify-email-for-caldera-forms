<?php
/**
 * Plugin Name: Verify Email for Caldera Forms
 * Plugin URI:
 * Description: Send the submitter an email with a validate link to verify thier email address before sending.
 * Version:     1.0.0
 * Author:      David Cramer
 * Author URI:
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */


// add filters
add_filter('caldera_forms_get_form_processors', 'cf_validate_email_register_processor');

function cf_validate_email_register_processor($pr){
	$pr['validate_email'] = array(
		"name"              =>  __('Validate Email', 'cf-validate-email'),
		"description"       =>  __("Send submitter a link to validate email", 'cf-validate-email'),
		"icon"				=>	plugin_dir_url(__FILE__) . "icon.png",
		"author"            =>  'David Cramer',
		"author_url"        =>  'http://cramer.co.za',
		"pre_processor"		=>  'cf_validate_email_submit',
		"template"          =>  plugin_dir_path(__FILE__) . "config.php",
	);
	return $pr;
}

function cf_validate_email_submit($config, $form){
	global $transdata;

	$fail =  array(
		'type' => 'error',
		'note'	=>	__( 'Could not send verification email', 'cf-validate-email' )
	);

	$recipient = Caldera_Forms::get_field_data( $config['email'], $form );
	if ( is_string( $recipient ) ) {
		$recipient = Caldera_Forms::do_magic_tags( $recipient );
		if ( ! is_string( $recipient ) || ! filter_var( $recipient, FILTER_VALIDATE_EMAIL ) ) {
			return $fail;

		}

	}else{
		return $fail;

	}

	if(isset($transdata[$config['processor_id']]['validated']) && $transdata[$config['processor_id']]['validated'] === $recipient ){
		// already validated
		return;
	}

	if(!empty($_GET['validatetoken'])){
		if($transdata['vkey'] === $_GET['validatetoken']){
			$transdata[$config['processor_id']]['validated'] = Caldera_Forms::do_magic_tags( $config['email'] );
			return;
		}
	}

	$transdata['expire'] = 60 * $config['expire']; // extend expire
	$transdata['vkey'] = sha1(uniqid($transdata['transient']));

	$referer = parse_url( $transdata['data']['_wp_http_referer_true'] );
	if(!empty($referer['query'])){
		parse_str($referer['query'], $referer['query']);
		if(isset($referer['query']['cf_er'])){
			unset($referer['query']['cf_er']);
		}
		if(isset($referer['query']['cf_su'])){
			unset($referer['query']['cf_su']);
		}
	}
	// add transient process
	$referer['query']['validatetoken'] = $transdata['vkey'];
	$referer['query']['cf_tp'] = $transdata['transient'];


	$validate_link = explode('?', $transdata['data']['_wp_http_referer_true']);
	$validate_link = $validate_link[0].'?'.http_build_query($referer['query']);
	$validate_link = '<a href="'.$validate_link.'">'.$validate_link.'</a>';

	$subject = Caldera_Forms::do_magic_tags( $config['subject'] );

	$mail['headers'][] = 'From: ' . $config['from_name'] . ' <' . $config['from_email'] . '>';
	$mail['headers'][] = "Content-type: text/html";

	$config['message'] = str_replace('{validate_link}', $validate_link, $config['message']);
	$message = nl2br( Caldera_Forms::do_magic_tags( $config['message'] ) );

	$headers = implode("\r\n", $mail['headers']);

	$return = array(
		'type' => 'success',
		'note'	=>	Caldera_Forms::do_magic_tags( $config['notice'] )
	);

	// prepare email
	if( wp_mail( '<'.$recipient.'>', $subject, $message, $headers) ){
		return $return;

	}else{
		return $fail;

	}

}
