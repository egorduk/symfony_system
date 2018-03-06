<?php

namespace SecureBundle\Service\Handler;

use Exception;
use Oneup\UploaderBundle\Uploader\ErrorHandler\ErrorHandlerInterface;
use Oneup\UploaderBundle\Uploader\Response\AbstractResponse;
use SecureBundle\Service\Helper\FileHelper;

class UploadFileErrorHandler implements ErrorHandlerInterface
{
    /**
     * @var FileHelper
     */
    private $fh;

    public function __construct(FileHelper $fh)
    {
        $this->fh = $fh;
    }

    public function addException(AbstractResponse $response, Exception $exception)
    {
        $message = $exception->getMessage();

        $response['error'] = $this->fh->getErrorMessage($message);
    }
}