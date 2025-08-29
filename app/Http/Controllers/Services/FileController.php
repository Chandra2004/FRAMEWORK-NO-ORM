<?php

namespace TheFramework\Http\Controllers\Services;

use TheFramework\App\Config;

class FileController
{
    private string $baseDir;
    private array $allowedFolders = ['dummy', 'public', 'user-pictures', 'css', 'js'];
    private array $allowedExtensions = ['ico', 'jpg', 'jpeg', 'png', 'webp', 'css', 'js', 'txt', 'html', 'htm'];

    public function __construct()
    {
        Config::loadEnv();
        $this->baseDir = realpath(ROOT_DIR . '/private-uploads') . DIRECTORY_SEPARATOR;
    }

    public function Serve($params = [])
    {
        $requested = '';
        if (is_array($params) && isset($params[0])) {
            $requested = $params[0];
        } elseif (is_string($params) && $params !== '') {
            $requested = $params;
        } elseif (isset($_GET['file'])) {
            $requested = $_GET['file'];
        }


        if (empty($requested)) {
            if (Config::get('APP_ENV') === 'local') {
                $error = [
                    'message' => 'Access denied: no file specified. check url : /file/(your allow folder)/(name of file.extension)',
                    'file' => '/private-uploads/',
                    'line' => '0'
                ];

                DebugController::showWarning($error);
            } else if (Config::get('APP_ENV') === 'production') {
                ErrorController::error403();
            }
        }

        // Validasi folder teratas
        $topFolder = strtok($requested, '/');
        if (!in_array($topFolder, $this->allowedFolders, true)) {
            if (Config::get('APP_ENV') === 'local') {
                $error = [
                    'message' => 'Access denied: invalid folder. check url : /file/(your allow folder)/(name of file.extension)',
                    'file' => '/private-uploads/',
                    'line' => '0'
                ];

                DebugController::showWarning($error);
            } else if (Config::get('APP_ENV') === 'production') {
                ErrorController::error403();
            }
        }

        // Path absolut file
        $fullPath = realpath($this->baseDir . DIRECTORY_SEPARATOR . $requested);
        $realBase = $this->baseDir;

        // Debugging sementara (hapus kalau sudah jalan)
        // echo "<pre>"; var_dump($requested, $this->baseDir, $fullPath); exit;

        // Cegah path traversal
        if ($fullPath === false || strncmp($fullPath, $realBase, strlen($realBase)) !== 0) {
            if (Config::get('APP_ENV') === 'local') {
                $error = [
                    'message' => 'Access denied: path traversal or file not found. check url : /file/(your allow folder)/(name of file.extension)',
                    'file' => '/private-uploads/',
                    'line' => '0'
                ];

                DebugController::showWarning($error);
            } else if (Config::get('APP_ENV') === 'production') {
                ErrorController::error403();
            }
        }

        // Validasi ekstensi
        $ext = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
        if (!in_array($ext, $this->allowedExtensions, true)) {
            http_response_code(403);
            exit('Invalid file extension.');
        }

        // Kalau file tidak ada → fallback
        if (!is_file($fullPath)) {
            $fullPath = $this->getFallbackFile($ext);
            if (!$fullPath) {
                if (Config::get('APP_ENV') === 'local') {
                    $error = [
                        'message' => 'File not found. check url : /file/(your allow folder)/(name of file.extension)',
                        'file' => '/private-uploads/',
                        'line' => '0'
                    ];

                    DebugController::showWarning($error);
                } else if (Config::get('APP_ENV') === 'production') {
                    ErrorController::error404();
                }
            }
        }

        // Tentukan MIME type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime  = finfo_file($finfo, $fullPath) ?: 'application/octet-stream';
        finfo_close($finfo);

        // Validasi MIME type
        $allowedMime = [
            'image/jpeg',
            'image/png',
            'image/webp',
            'image/x-icon',
            'image/vnd.microsoft.icon',
            'text/css',
            'application/javascript',
            'text/javascript',
            'text/plain',
            'text/html',
        ];
        if (!in_array($mime, $allowedMime, true)) {
            if (Config::get('APP_ENV') === 'local') {
                $error = [
                    'message' => 'Invalid file type. check url : /file/(your allow folder)/(name of file.extension)',
                    'file' => '/private-uploads/',
                    'line' => '0'
                ];

                DebugController::showWarning($error);
            } else if (Config::get('APP_ENV') === 'production') {
                ErrorController::error404();
            }
        }

        // Kirim file
        header('Content-Type: ' . $mime);
        header('Content-Length: ' . filesize($fullPath));
        readfile($fullPath);
        exit;
    }

    private function getFallbackFile(string $ext): ?string
    {
        $map = [
            'jpg'  => 'dummy/dummy.jpg',
            'jpeg' => 'dummy/dummy.jpg',
            'png'  => 'dummy/dummy.jpg',
            'webp' => 'dummy/dummy.jpg',
            'css'  => ROOT_DIR . '/resources/css/empty.css',
            'js'   => ROOT_DIR . '/resources/js/empty.js',
        ];

        $target = $map[$ext] ?? null;
        if (!$target) {
            return null;
        }

        if (str_starts_with($target, ROOT_DIR)) {
            return is_file($target) ? $target : null;
        }

        $path = realpath($this->baseDir . DIRECTORY_SEPARATOR . $target);
        return $path && is_file($path) ? $path : null;
    }
}
