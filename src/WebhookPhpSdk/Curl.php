<?php

namespace Perevertunoff\MyToolsPhp\WebhookPhpSdk;

class Curl
{
    public static function execute(string $url, ?array $data = null)
    {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        if ($data) {
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        }

        $result = [
            'response' => curl_exec($curl),
            'error' => curl_errno($curl),
            'info' => curl_getinfo($curl),
        ];

        curl_close($curl);

        return $result;
    }
}
