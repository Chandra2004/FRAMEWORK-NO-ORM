<?php

namespace TheFramework\Config;

class ImageHandler
{
    /**
     * Daftar pesan error upload image → WebP.
     */
    private static array $errorMessages = [
        'required_image'  => 'Image is required.',
        'failed_upload'   => 'Image upload failed.',
        'failed_extension' => 'File extension not supported.',
        // failed_size dibuat dinamis di getErrorMessage()
        'failed_mime'     => 'Invalid MIME type.',
        'failed_read'     => 'Failed to read image.',
        'failed_convert'  => 'Failed to convert image to WebP.',
        'failed_remove'   => 'Failed remove image in directory',
    ];

    /**
     * Ambil pesan error berdasarkan kode.
     * Bisa diberikan parameter tambahan, misal ukuran maksimum.
     */
    public static function getErrorMessage(string $code, array $params = ['size' => 2]): string
    {
        if ($code === 'failed_size' && isset($params['size'])) {
            return "File size is too large. Maximum {$params['size']}MB.";
        }
        return self::$errorMessages[$code] ?? 'Unknown error.';
    }

    /**
     * Cek apakah hasil handleUploadToWebP adalah error.
     * (Jika tidak diawali "img_", berarti error)
     */
    public static function isError(string $result): bool
    {
        return !str_starts_with($result, 'img_'); // PHP 8+
        // Untuk PHP 7.x gunakan: return substr($result, 0, 4) !== 'img_';
    }

    /**
     * Menangani upload gambar, resize, konversi ke WebP, dan hapus file lama jika perlu.
     *
     * @param array $file           File upload dari $_FILES
     * @param string $uploadDir     Folder tujuan penyimpanan
     * @param int $size             Maksimal size dalam MB (default 2)
     * @param object|null $model    Instance model (opsional)
     * @param int|string|null $id   ID record untuk cari file lama (opsional)
     * @param string $getOldMethod  Nama method di model untuk ambil data lama (opsional)
     * @param string $deleteKey     Key dalam array data lama untuk nama file (opsional)
     * @param int $maxWidth         Lebar maksimal gambar (default: 800)
     * @param int $quality          Kualitas WebP (0-100) (default: 80)
     * @return string               Nama file WebP jika sukses, atau kode error
     */
    public static function handleUploadToWebP(
        array $file,
        string $uploadDir,
        int $size = 2,
        ?object $model = null,
        int|string|null $id = null,
        string $getOldMethod = '',
        string $deleteKey = '',
        int $maxWidth = 800,
        int $quality = 80
    ): string {
        if ($file['error'] === 4) {
            return 'required_image';
        }

        if ($file['error'] !== UPLOAD_ERR_OK) {
            return 'failed_upload';
        }

        $allowedExtensions = ['jpg', 'jpeg', 'png'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowedExtensions)) {
            return 'failed_extension';
        }

        $maxFileSize = $size * 1024 * 1024;
        if ($file['size'] > $maxFileSize) {
            return 'failed_size';
        }

        $mime = mime_content_type($file['tmp_name']);
        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/pjpeg', 'image/jpg'];
        if (!in_array($mime, $allowedMimeTypes)) {
            return 'failed_mime';
        }

        $directory = ROOT_DIR . '/private-uploads' . $uploadDir . '/';
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        $fileName = uniqid('img_') . '.webp';
        $targetPath = rtrim($directory, '/') . '/' . $fileName;

        $source = null;

        try {
            if ($ext === 'jpg' || $ext === 'jpeg') {
                $source = @imagecreatefromjpeg($file['tmp_name']);
            } elseif ($ext === 'png') {
                $source = @imagecreatefrompng($file['tmp_name']);
                imagepalettetotruecolor($source);
                imagealphablending($source, true);
                imagesavealpha($source, true);
            }

            if (!$source) {
                return 'failed_read';
            }

            $width = imagesx($source);
            $height = imagesy($source);

            if ($width > $maxWidth) {
                $newHeight = intval($height * ($maxWidth / $width));
                $resized = imagecreatetruecolor($maxWidth, $newHeight);
                imagecopyresampled($resized, $source, 0, 0, 0, 0, $maxWidth, $newHeight, $width, $height);
                imagedestroy($source);
                $source = $resized;
            }

            if ($ext === 'png') {
                imagealphablending($source, false);
                imagesavealpha($source, true);
            }

            $success = imagewebp($source, $targetPath, $quality);
            imagedestroy($source);

            if (!$success) {
                return 'failed_convert';
            }

            // Hapus file lama jika model & data tersedia
            if ($model && $id !== null && method_exists($model, $getOldMethod)) {
                $data = $model->$getOldMethod($id);
                $oldFile = $data[$deleteKey] ?? null;

                if ($oldFile && file_exists($directory . '/' . $oldFile)) {
                    @unlink($directory . '/' . $oldFile);
                }
            }

            return $fileName;
        } catch (\Exception $e) {
            if ($source) {
                imagedestroy($source);
            }
            return 'error: ' . $e->getMessage();
        }
    }

    public static function delete(string $directory = null, string $fileName = null)
    {
        $mainDir = ROOT_DIR . '/private-uploads' . $directory;
        if ($fileName && file_exists($mainDir . '/' . $fileName)) {
            @unlink($mainDir . '/' . $fileName);
        } else {
            return 'failed_remove';
        }
    }
}
