<?php

namespace Perevertunoff\MyToolsPhp\Bitrix24PhpSdk;

use Perevertunoff\MyToolsPhp\WebhookPhpSdk\Driver;

class DriverBase extends Driver
{
    public function returnResultMethodCall(string $method, ?array $params = null)
    {
        $result = $this->returnResponseMethodCall($method, $params);
        if (is_array($result) && isset($result['result'])) return $result['result'];
        return false;
    }

    public function methodCallsByNext(string $method, ?array $params = null, int $start = 0)
    {
        $params['start'] = $start;

        do {
            if (isset($call['next']) && $call['next']) $params['start'] = $call['next'];
            $call = $this->returnResponseMethodCall($method, $params);
            if ($call) $result[] = $call;
        }
        while(isset($call['next']) && $call['next']);

        if (isset($result) && $result) return $result;
        return false;
    }

    public function returnResultMethodCallsByNext(string $method, ?array $params = null, int $start = 0)
    {
        $calls = $this->methodCallsByNext($method, $params, $start);

        foreach ($calls as $call) {
            if (isset($call['result'])) {
                if (is_array($call['result'])) {
                    $result = array_merge($result, $call['result']);
                } else if ($call['result']) {
                    $result[] = $call['result'];
                }
            }
        }

        if (isset($result) && $result) return $result;
        return false;
    }
}
