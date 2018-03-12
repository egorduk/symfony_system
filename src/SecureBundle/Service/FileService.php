<?php

namespace SecureBundle\Service;

use Doctrine\ORM\EntityManager;
use SecureBundle\Entity\OrderFile;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

class FileService
{
    protected $errorMessages = [
        1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
        2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
        3 => 'The uploaded file was only partially uploaded',
        4 => 'No file was uploaded',
        6 => 'Missing a temporary folder',
        7 => 'Failed to write file to disk',
        8 => 'A PHP extension stopped the file upload',
        'postMaxSize' => 'The uploaded file exceeds the post_max_size directive in php.ini',
        'maxFileSize' => 'File is too big',
        'error.maxsize' => 'File is too big',
        'minFileSize' => 'File is too small',
        'acceptFileTypes' => 'Filetype not allowed',
        'maxNumberOfFiles' => 'Maximum number of files exceeded',
        'maxWidth' => 'Image exceeds maximum width',
        'minWidth' => 'Image requires a minimum width',
        'maxHeight' => 'Image exceeds maximum height',
        'minHeight' => 'Image requires a minimum height',
        'abort' => 'File upload aborted',
        'imageResize' => 'Failed to resize image',
        'invalidMimeType' => 'Invalid file mime type',
        'error.whitelist' => 'Invalid file mime type',
        'error.blacklist' => 'Invalid file mime type',
    ];

    /**
     * @param EntityManager $em
     * @param Router $router
     */
    public function __construct(EntityManager $em, Router $router)
    {
        $this->em = $em;
        $this->router = $router;
    }

    /**
     * @param int $fileId
     *
     * @return OrderFile|null
     */
    public function getFileById($fileId)
    {
        return $this->em
            ->createQueryBuilder()
            ->select('f')
            ->from(OrderFile::class, 'f')
            ->where('f.id = :fileId')
            ->andWhere('f.isDelete = :isDelete')
            ->setParameters([
                'fileId' => $fileId,
                'isDelete' => 0,
            ])
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param string $filename
     *
     * @return string
     */
    public function getFileExtension($filename)
    {
        return pathinfo($filename, PATHINFO_EXTENSION);
    }

    public function getFileIcon($fileExtension)
    {
        //$uploadsOrdersDir = $this->getParameter('uploads_attachments_orders_dir');
        //$filePath = $uploadsOrdersDir . '/' . $orderId . '/' . $filename;
    }

    /**
     * @param int $fileId
     * @param string $type
     *
     * @return string
     */
    public function getFileUrl($fileId, $type)
    {
        return $this->router->generate('secure_download_file', [
            'type' => $type,
            'fileId' => $fileId,
        ]);
    }

    public function getErrorMessage($error) {
        return isset($this->errorMessages[$error]) ? $this->errorMessages[$error] : $error;
    }

    public function getServerVar($server, $id) {
        return @$server->get($id);
    }

    public function fixIntegerOverflow($size) {
        if ($size < 0) {
            $size += 2.0 * (PHP_INT_MAX + 1);
        }

        return $size;
    }

    public function getConfigBytes($val) {
        $val = trim($val);
        $last = strtolower($val[strlen($val)-1]);
        $val = (int)$val;

        switch ($last) {
            case 'g':
                $val *= 1024 * 1024 * 1024;
                break;
            case 'm':
                $val *= 1024 * 1024;
                break;
            case 'k':
                $val *= 1024;
                break;
            default:
                break;
        }

        return $this->fixIntegerOverflow($val);
    }

    public function getFileSize($filePath, $clearStatCache = false) {
        if ($clearStatCache) {
            if (version_compare(PHP_VERSION, '5.3.0') >= 0) {
                clearstatcache(true, $filePath);
            } else {
                clearstatcache();
            }
        }

        return $this->fixIntegerOverflow(filesize($filePath));
    }

    public function getCountFiles() {
        return count($this->getFileObjects('is_valid_file_object'));
    }

    private function getFileObjects($iterationMethod = 'get_file_object') {
        $uploadDir = $this->getUploadPath();

        if (!is_dir($uploadDir)) {
            return [];
        }

        return array_values(array_filter(array_map(
            array($this, $iterationMethod),
            scandir($uploadDir)
        )));
    }

    public function getUploadPath($fileName = null, $version = null, $uploadDir = null) {
        /*$fileName = $fileName ? $fileName : '';

        if (empty($version)) {
            $versionPath = '';
        } else {
            $versionDir = @$this->options['image_versions'][$version]['upload_dir'];

            if ($versionDir) {
                return $versionDir.$this->get_user_path().$fileName;
            }

            $versionPath = $version . '/';
        }

        return $uploadDir . $this->get_user_path() . $versionPath . $fileName;*/
        return '';
    }
}