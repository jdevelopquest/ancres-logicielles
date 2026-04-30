<?php
declare(strict_types=1);

namespace App\Utils;

class Logger
{
    private const string LOG_FILE = LOG_PATH . "messages.log";
    private const string DATE_FORMAT = "c";

    /**
     * Logs a debug message.
     *
     * @param string $message The debug message to log.
     * @param mixed $context Additional contextual information for the message.
     * @return void
     */
    public function debug(string $message, mixed $context = []): void
    {
        $this->log("DEBUG", $message, $context);
    }

    /**
     * Logs an informational message.
     *
     * @param string $message The message to log.
     * @param mixed $context Additional context for the log entry.
     * @return void
     */
    public function info(string $message, mixed $context = []): void
    {
        $this->log("INFO", $message, $context);
    }

    /**
     * Logs a warning level message with the given context.
     *
     * @param string $message The warning message to log.
     * @param mixed $context Additional information to include with the log entry.
     * @return void
     */
    public function warning(string $message, mixed $context = []): void
    {
        $this->log("WARNING", $message, $context);
    }

    /**
     * Logs an error level message with the provided context.
     *
     * @param string $message The error message to log.
     * @param mixed $context Additional data to associate with the log entry.
     * @return void
     */
    public function error(string $message, mixed $context = []): void
    {
        $this->log("ERROR", $message, $context);
    }

    /**
     * Logs a message at the specified level with the provided context.
     *
     * @param string $level The severity level of the log (e.g., ERROR, INFO).
     * @param string $message The message to log.
     * @param mixed $context Additional data to include with the log entry.
     * @return void
     */
    private function log(string $level, string $message, mixed $context): void
    {
        $logEntry = [
            "timestamp" => date(self::DATE_FORMAT),
            "level" => $level,
            "message" => $message,
            "context" => $context,
        ];

        // TODO: améliorations possibles
        if (!file_exists(self::LOG_FILE)) {
            $directory = dirname(self::LOG_FILE);

            if (!is_dir($directory)) {
                mkdir($directory, 0755, true);
            }

            touch(self::LOG_FILE);
        }

        error_log(
            json_encode(
                $logEntry,
                JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES,
            ) . "\n",
            3,
            self::LOG_FILE,
        );
    }
}
