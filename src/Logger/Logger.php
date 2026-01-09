<?php 

namespace App\Logger;

use Psr\Log\LoggerInterface;

class Logger
{
    private LoggerInterface $appLogger;

    public function __construct(
        LoggerInterface $appLogger
    ) {
        $this->appLogger = $appLogger;
    }

    // === GENERAL LOGGING ===
    public function debug(string $message, array $context = []): void
    {
        $this->appLogger->debug($message, $context);
    }

    public function info(string $message, array $context = []): void
    {
        $this->appLogger->info($message, $context);
    }

    public function error(string $message): void
    {
        $this->appLogger->error($message);
    }

}