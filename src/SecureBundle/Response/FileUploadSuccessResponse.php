<?php

namespace SecureBundle\Response;

use Oneup\UploaderBundle\Uploader\Response\AbstractResponse;

class FileUploadSuccessResponse extends AbstractResponse
{
    protected $success;
    protected $error;

    public function __construct()
    {
        $this->success = true;
        $this->error = null;

        parent::__construct();
    }

    public function assemble()
    {
        $data = $this->data;
        $data['success'] = $this->success;

        if ($this->success) {
            unset($data['error']);
        }

        if (!$this->success) {
            $data['error'] = $this->error;
        }

        return $data;
    }
}