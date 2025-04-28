<?php

namespace App\Application\Utils;

trait LogPrinter
{
    protected function logMessage(string $message): void
    {
        $file = LOG . "messages.log";
        $message = sprintf("%s\n", $message);
        error_log($message, 3, $file);
    }

    protected function logData(mixed $data): void
    {
        $file = LOG . "messages.log";
        $message = sprintf("%s\n", var_export($data, true));
        error_log($message, 3, $file);
    }
}