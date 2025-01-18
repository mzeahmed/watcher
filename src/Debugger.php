<?php

declare(strict_types=1);

namespace Watcher;

class Debugger
{
    /**
     * Writes a log if WP_DEBUG is enabled.
     *
     * @param mixed $log The log to write.
     */
    public static function writeLog(mixed $log): void
    {
        if (\is_array($log) || \is_object($log)) {
            error_log(print_r($log, true));
        } else {
            error_log($log);
        }
    }
}
