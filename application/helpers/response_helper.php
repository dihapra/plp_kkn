<?php
defined('BASEPATH') or exit('No direct script access allowed');

if (!function_exists('response_json')) {
    /**
     * Kirim response JSON standar
     *
     * @param array|string $message
     * @param mixed $data
     * @param int $status
     * @return void
     */
    function response_json($message, $data = null, $status = 200)
    {
        $CI = &get_instance();

        $output = [
            'message' => $message,
            'data' => $data
        ];

        $CI->output
            ->set_status_header($status)
            ->set_content_type('application/json')
            ->set_output(json_encode($output));
    }
}

if (!function_exists('response_error')) {
    /**
     * Kirim response error dengan log sederhana
     *
     * @param string $message
     * @param mixed $error Throwable|string|array|null
     * @param int $status
     * @return void
     */
    function response_error($message = 'Terjadi kesalahan', $error = null, $status = 500)
    {
        $CI = &get_instance();

        if ($error instanceof Throwable) {
            log_message('error', $message . ' | ' . $error->getMessage() . $error->getLine() . $error->getFile());
        } else {
            log_message('error', $message . ' | ' . json_encode($error));
        }

        $output = [
            'message' => $message,
            'data' => is_array($error) ? $error : null
        ];

        $CI->output
            ->set_status_header($status)
            ->set_content_type('application/json')
            ->set_output(json_encode($output));
    }
}
