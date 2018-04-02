<?php

namespace SecureBundle\Response;

use Oneup\UploaderBundle\Uploader\Response\AbstractResponse;

class FileUploaderResponse extends AbstractResponse
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
        // explicitly overwrite success and error key
        // as these keys are used internaly by the
        // frontend uploader
        $data = $this->data;
        $data['success'] = $this->success;

        if ($this->success)
            unset($data['error']);

        if (!$this->success)
            $data['error'] = $this->error;

        return $data;
    }

    // ... snip, setters/getters
}