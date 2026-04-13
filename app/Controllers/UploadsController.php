<?php

namespace App\Controllers;

use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\ResponseInterface;

class UploadsController extends BaseController
{
    /**
     * Sajikan cover artikel dari writable (aman untuk Docker: volume writable bisa ditulis www-data).
     */
    public function cover(string $filename): ResponseInterface
    {
        $safe = basename($filename);
        if ($safe === '' || $safe !== $filename) {
            throw PageNotFoundException::forPageNotFound();
        }

        $path = WRITEPATH . 'uploads' . DIRECTORY_SEPARATOR . 'covers' . DIRECTORY_SEPARATOR . $safe;
        if (! is_file($path)) {
            throw PageNotFoundException::forPageNotFound();
        }

        $mime = mime_content_type($path);
        if ($mime === false) {
            $mime = 'application/octet-stream';
        }

        return $this->response
            ->setContentType($mime)
            ->setBody((string) file_get_contents($path))
            ->setHeader('Cache-Control', 'public, max-age=86400');
    }
}
