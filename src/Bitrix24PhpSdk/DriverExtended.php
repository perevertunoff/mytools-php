<?php

namespace Perevertunoff\MyToolsPhp\Bitrix24PhpSdk;

class DriverExtended extends DriverBase
{
    /*
     * $data[] = METHOD
     * $data[] = [METHOD]
     * $data[] = [METHOD, [PARAMS]]
     * $data[NAME] = METHOD
     * $data[NAME] = [METHOD]
     * $data[NAME] = [METHOD, [PARAMS]]
     */
    public function batchCall(array $data, bool $halt = false)
    {
        foreach ($data as $key => $value) {
            if (is_array($value) && $value && isset($value[0])) {
                $cmd[$key] = $value[0];
                if (isset($value[1]) && is_array($value[1]) && $value[1]) {
                    $cmd[$key] .= '?'.http_build_query($value[1]);
                }
            } else if (is_string($value)) {
                $cmd[$key] = $value;
            }
        }

        if (isset($cmd) && is_array($cmd) && $cmd) {
            $result = $this->returnResultMethodCall('batch', [
                'halt' => $halt,
                'cmd' => $cmd,
            ]);
        }

        if (isset($result) && $result) return $result;
        return false;
    }

    /*
     * $data[] = METHOD
     * $data[] = [METHOD]
     * $data[] = [METHOD, [PARAMS]]
     * $data[NAME] = METHOD
     * $data[NAME] = [METHOD]
     * $data[NAME] = [METHOD, [PARAMS]]
     */
    public function returnResultBatchCall(array $data, bool $halt = false)
    {
        $result = $this->batchCall($data, $halt);
        if (is_array($result) && isset($result['result'])) return $result['result'];
        return false;
    }

    /*
     * $data[] = METHOD
     * $data[] = [METHOD]
     * $data[] = [METHOD, [PARAMS]]
     * $data[NAME] = METHOD
     * $data[NAME] = [METHOD]
     * $data[NAME] = [METHOD, [PARAMS]]
     */
    public function batchCallsByCount(array $data, bool $halt = false)
    {
        $datas = array_chunk($data, 50, true);

        foreach ($datas as $data_item) {
            $result[] = $this->batchCall($data_item, $halt);
        }

        if (isset($result) && $result) return $result;
        return false;
    }

    /*
     * $data[] = METHOD
     * $data[] = [METHOD]
     * $data[] = [METHOD, [PARAMS]]
     * $data[NAME] = METHOD
     * $data[NAME] = [METHOD]
     * $data[NAME] = [METHOD, [PARAMS]]
     */
    public function returnResultBatchCallsByCount(array $data, bool $halt = false)
    {
        $calls = $this->batchCallsByCount($data, $halt);

        foreach ($calls as $call) {
            if (isset($call['result'])) {
                if (is_array($call['result'])) {
                    $result = $result + $call['result'];
                } else if ($call['result']) {
                    $result[] = $call['result'];
                }
            }
        }

        if (isset($result) && $result) return $result;
        return false;
    }

    public function returnAllResultRaw(string $method, ?array $params = null, ?int $quantity = null, ?int $start = 0)
    {
        if (!$quantity) {
            $call_total = $this->returnResponseMethodCall($method, $params);
            if (isset($call_total['total']) && $call_total['total']) {
                $quantity = $call_total['total'];
            }
        }

        $params['start'] = $start;

        for ($i = 0 ; $i < (int) ceil($quantity / 50) ; $i++) {
            $data[$i] = [$method, $params];
            $params['start'] += 50;
        }

        if (isset($data) && is_array($data) && $data) {
            $result = $this->batchCallsByCount($data, true);
        }

        if (isset($result) && $result) return $result;
        return false;
    }

    public function returnAllResultClear(string $method, ?array $params = null, ?int $quantity = null, ?int $start = 0)
    {
        $raw = $this->returnAllResultRaw($method, $params, $quantity, $start);

        if (is_array($raw) && $raw) {
            foreach ($raw as $call) {
                if (isset($call['result']) && is_array($call['result'])) {
                    foreach ($call['result'] as $raw_item) {
                        if (is_array($raw_item)) {
                            $result = array_merge($result, $raw_item);
                        } else {
                            $result[] = $raw_item;
                        }
                    }
                }
            }
        }

        if (isset($result) && $result) return $result;
        return false;
    }
}
