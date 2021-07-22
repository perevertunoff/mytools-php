<?php

namespace Perevertunoff\MyToolsPhp\TelegramPhpSdk;

use Perevertunoff\MyToolsPhp\WebhookPhpSdk\Driver;

class DriverBase extends Driver
{
    public function __construct(string $token)
    {
        parent::__construct("https://api.telegram.org/bot$token");
    }
}
