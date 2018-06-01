<?php

class CF_Verify_Email_Validate_With_Pro extends \calderawp\calderaforms\pro\api\api {


    /**
     * Send the verification message via CF Pro
     *
     * @since 1.2.0
     *
     * @param string $recipient
     * @param string $subject
     * @param string $message
     * @param string $fromName
     * @param string $fromEmail
     * @return bool
     */
    public function sendVerification(
        $recipient,
        $subject,
        $message,
        $fromName,
        $fromEmail
    ) {
        $data = [
            'recipient' => $recipient,
            'subject' => $subject,
            'message' => $message,
            'fromName' => $fromName,
            'fromEmail' => $fromEmail
        ];

        $response = $this->request('/cf-verify-email', $data, 'POST');
        if (!is_wp_error($response) && 201 == wp_remote_retrieve_response_code($response)) {
            return true;
        }
        do_action( 'cf_verify_email_send_failed', $data );
        \calderawp\calderaforms\pro\container::get_instance()
            ->get_logger()
            ->send(
                'cf_verify_email_send_failed',
                $data,
                \Monolog\Logger::ALERT
            );
    }

    /** @inheritdoc */
    protected function get_url_root()
    {
        return caldera_forms_pro_app_url();
    }

}