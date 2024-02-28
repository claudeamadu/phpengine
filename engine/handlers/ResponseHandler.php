<?php
class ResponseHandler
{
    /**
     * Returs an HTTP Status code with data
     *
     * @param string $status
     * @param mixed $data
     * @return mixed
     */
    public static function deliver(string $status, mixed $data): mixed
    {
        $status_message = self::getStatusMessage($status);
        header("HTTP/1.1 $status $status_message");
        $response['status'] = $status;
        $response['message'] = $status_message;
        $response['data'] = $data;
        $json_response = json_encode($response);
        echo $json_response;
    }
    /**
     * Returs an HTTP Status code
     *
     * @param string $status
     * @return mixed
     */
    private static function getStatusMessage($statusCode)
    {
        switch ($statusCode) {
            case 100:
                return strtoupper('Continue');
            case 101:
                return strtoupper('Switching Protocols');
            case 200:
                return strtoupper('OK');
            case 201:
                return strtoupper('Created');
            case 204:
                return strtoupper('No Content');
            case 400:
                return strtoupper('Bad Request');
            case 401:
                return strtoupper('Unauthorized');
            case 403:
                return strtoupper('Forbidden');
            case 404:
                return strtoupper('Not Found');
            case 500:
                return strtoupper('Internal Server Error');
            case 503:
                return strtoupper('Service Unavailable');
            default:
                return strtoupper('Unknown Status');
        }
    }
}