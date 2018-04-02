<?php

namespace SecureBundle\Service\Handler;

use Exception;
use Oneup\UploaderBundle\Uploader\ErrorHandler\ErrorHandlerInterface;
use Oneup\UploaderBundle\Uploader\Response\AbstractResponse;
use SecureBundle\Service\FileService;

class UploadFileErrorHandler implements ErrorHandlerInterface
{
    private $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    public function addException(AbstractResponse $response, Exception $exception)
    {
        $message = $exception->getMessage();

        $response['error'] = $this->fileService->getErrorMessage($message);
    }
}