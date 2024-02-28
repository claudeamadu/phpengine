<?php

class Http
{
    /**
     * Sends HTTP Request
     *
     * @param string $url
     * @param string $method
     * @param array $data
     * @param array $headers
     * @return mixed | bool | string | object
     */
    public function sendRequest(string $url, $method = 'GET', $data = [], $headers = []) : mixed
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        if ($method === 'POST' || $method === 'PUT') {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }

        try {
            $result = curl_exec($ch);

            if ($result === FALSE) {
                throw new Exception('Failed to send request: ' . curl_error($ch));
            }

            return $result;
        } catch (Exception $e) {
            // Handle the exception (e.g., log, rethrow, etc.)
            error_log('Error in sendRequest: ' . $e->getMessage());
            return false;
        } finally {
            curl_close($ch);
        }
        return false;
    }
}