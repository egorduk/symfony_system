<?php

namespace SecureBundle\Service\ListenerEventListener;

use Oneup\UploaderBundle\Event\ValidationEvent;
use Oneup\UploaderBundle\Uploader\Exception\ValidationException;
use SecureBundle\Service\FileService;
use SecureBundle\Service\Helper\FileHelper;

class ValidateFileListener
{
    private $fh;
    private $error;
    private $options;
    private $config;
    private $server;

    public function __construct(FileService $fh)
    {
        $this->fh = $fh;

        $this->options = [
            'acceptFileTypes' => '/.+$/i',
            'maxFileSize' => null,  //get from config.xml
            'minFileSize' => 1,
            'maxNumberOfFiles' => 1,
        ];
    }

    public function onValidate(ValidationEvent $event)
    {
        $this->config = $event->getConfig();
        $this->server = $event->getRequest()->server;
        $file = $event->getFile();

        $isValid = $this->validateFileType($file->getClientOriginalName()) &
            $this->validateMaxNumberFiles($file->getPathname());

        if (!$isValid) {
            throw new ValidationException($this->error);
        }
    }

    private function validateFileSize($fileSize, $uploadedFileName = null)
    {
        $contentLength = $this->fh->fixIntegerOverflow(
            (int)$this->fh->getServerVar($this->server, 'CONTENT_LENGTH')
        );

        $postMaxSize = $this->fh->getConfigBytes(ini_get('post_max_size'));

        if ($postMaxSize && ($contentLength > $postMaxSize)) {
            $this->error = $this->fh->getErrorMessage('postMaxSize');

            return false;
        }

        if ($uploadedFileName && is_uploaded_file($uploadedFileName)) {
            $uploadedFileSize = $this->fh->getFileSize($uploadedFileName);
        } else {
            $uploadedFileSize = $contentLength;
        }

        if ($this->config['max_size'] && (
                $fileSize > $this->config['max_size'] ||
                $uploadedFileSize > $this->config['max_size'])
        ) {
            $this->error = $this->fh->getErrorMessage('maxFileSize');

            return false;
        }

        if ($this->options['minFileSize'] &&
            $fileSize < $this->options['minFileSize'] ||
            $uploadedFileSize < $this->options['minFileSize']
        ) {
            $this->error = $this->fh->getErrorMessage('minFileSize');

            return false;
        }

        return true;
    }

    private function validateFileType($fileName)
    {
        if (!preg_match($this->options['acceptFileTypes'], $fileName)) {
            $this->error = $this->fh->getErrorMessage('acceptFileTypes');

            return false;
        }

        return true;
    }

    private function validateMaxNumberFiles($fileName)
    {
        /*if (is_int($this->options['maxNumberOfFiles']) &&
            ($this->fh->getCountFiles() >= $this->options['maxNumberOfFiles']) &&
            // Ignore additional chunks of existing files:
            !is_file($this->fh->getUploadPath($fileName))
        ) {
            $this->fh->error = $this->fh->getErrorMessage('maxNumberOfFiles');

            return false;
        }*/
        return true;
    }

    private function validateFileMimeType($fileMimeType)
    {
        $allowedMimeTypes = $this->config['allowed_mimetypes'];

        if (in_array($fileMimeType, $allowedMimeTypes)) {
            return true;
        }

        $this->error = $this->fh->getErrorMessage('invalidMimeType');

        return false;
    }
}