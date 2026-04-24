<?php

namespace App\Application\Utils;

trait LogPrinter
{
    protected function logMessage(string $message): void
    {
        $file = LOG . "messages.log";
        $message = sprintf("[%s] %s\n", date('Y-m-d H:i:s'), $message);
        error_log($message, 3, $file);
    }

    protected function logData(mixed $data): void
    {
        $file = LOG . "messages.log";
        $message = sprintf("[%s] %s\n", date('Y-m-d H:i:s'), var_export($data, true));
        error_log($message, 3, $file);
    }
}