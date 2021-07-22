<?php

namespace Perevertunoff\MyToolsPhp\WebhookPhpSdk;

class Driver
{
    protected $webhook;

    public function __construct(string $webhook)
    {
        $this->webhook = preg_replace('#/$#', '', $webhook);
    }

    public function methodCall(?string $method = null, ?array $params = null, bool $json_decode = true)
    {
        $webhook = $this->webhook;
        $url = ($method ? "$webhook/$method" : $webhook);
        $curl = Curl::execute($url, $params);

        $result = [
            'method' => $method,
            'params' => $params,
            'response' => $curl['response'],
            'webhook' => $webhook,
            'url' => $url,
            'curl' => $curl,
        ];

        if ($json_decode && is_string($return['response']) && $return['response']) {
            $return['response'] = json_decode($return['response'], true);
        }

        return $return;
    }

    public function returnResponseMethodCall(string $method, ?array $params = null, bool $json_decode = true)
    {
        $result = $this->methodCall($method, $params, $json_decode);
        if (is_array($result) && isset($result['response'])) return $result['response'];
        return false;
    }
}
