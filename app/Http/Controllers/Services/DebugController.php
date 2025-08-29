<?php

namespace TheFramework\Http\Controllers\Services;

use TheFramework\App\{Config, Database, View, CacheManager};
use TheFramework\Helpers\Helper;
use Exception;

class DebugController
{
    public static function showException(\Throwable $e)
    {
        if (ob_get_length())
            ob_end_clean();
        http_response_code(500);
        View::render('debug.exception', [
            'class' => get_class($e),
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString(),
        ]);
        exit;
    }

    public static function showFatal(array $error)
    {
        if (ob_get_length())
            ob_end_clean();
        http_response_code(500);
        View::render('debug.fatal', [
            'message' => $error['message'] ?? 'Fatal error',
            'file' => $error['file'] ?? 'unknown',
            'line' => $error['line'] ?? 'unknown',
        ]);
        exit;
    }

    public static function showWarning(array $error)
    {
        if (ob_get_length())
            ob_end_clean();
        http_response_code(500);

        $file = $error['file'] ?? 'unknown';
        $originalFile = $file;
        if (strpos($file, 'app\Storage\cache\views') !== false && file_exists($file)) {
            $content = file_get_contents($file);
            if (preg_match('/\/\*\*PATH\s+(.+?)\s+ENDPATH\*\*\//', $content, $matches)) {
                $originalFile = $matches[1];
            }
        }

        View::render('debug.warning', [
            'message' => $error['message'] ?? 'Unknown warning',
            'file' => $originalFile,
            'line' => $error['line'] ?? 'unknown',
        ]);
        exit;
    }
}
