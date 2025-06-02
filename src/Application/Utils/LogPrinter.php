<?php

namespace App\Application\Utils;

trait LogPrinter
{
    private const LOG_FILE = "messages.log";
    private const DATE_FORMAT = 'Y-m-d H:i:s';

    /**
     * Logs a message to the designated logging mechanism.
     *
     * @param string $message The message to be logged.
     * @return void
     */
    protected function logMessage(string $message): void
    {
        $this->writeToLog($message);
    }

    /**
     * Logs the provided data to the designated logging mechanism.
     *
     * @param mixed $data The data to be logged.
     * @return void
     */
    protected function logData(mixed $data): void
    {
        $this->writeToLog(var_export($data, true));
    }

    /**
     * Writes a formatted message to the log file.
     *
     * @param string $content The content to be written to the log.
     * @return void
     */
    private function writeToLog(string $content): void
    {
        $logFile = LOG_PATH . self::LOG_FILE;
        $formattedMessage = sprintf("[%s] %s\n", date(self::DATE_FORMAT), $content);
        error_log($formattedMessage, 3, $logFile);
    }
}