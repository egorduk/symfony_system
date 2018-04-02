<?php

namespace SecureBundle\Controller;

use Oneup\UploaderBundle\Controller\FineUploaderController;
use Oneup\UploaderBundle\Uploader\File\FileInterface;
use Oneup\UploaderBundle\Uploader\File\FilesystemFile;
use Oneup\UploaderBundle\Uploader\Response\EmptyResponse;
use Oneup\UploaderBundle\Uploader\Response\ResponseInterface;
use SecureBundle\Entity\OrderFile;
use SecureBundle\Entity\StatusOrder;
use SecureBundle\Entity\UserOrder;
use Symfony\Component\HttpFoundation\File\Exception\UploadException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;

class FileUploaderController extends FineUploaderController
{
    private $orderId = 0;

    public function upload()
    {
        $request = $this->getRequest();
        $files = $this->getFiles($request->files);

        $response = new EmptyResponse();
        $isChunks = null !== $request->get('chunks');

        $uploadedFiles = [];

        $this->setOrderId($request->get('orderId'));

        foreach ($files as $file) {
            try {
                $isChunks ?
                    $this->handleChunkedUpload($file, $response, $request) :
                    $uploadedFiles[] = $this->handleUpload($file, $response, $request)
                ;
            } catch (UploadException $e) {
                $this->errorHandler->addException($response, $e);
            }
        }

        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $orderFileRepository = $this->container->get('secure.repository.order_file');
        /* @var UserOrder $order */
        $order = $this->container->get('secure.service.order')->getOneById($this->getOrderId());

        /* @var File $file */
        foreach ($uploadedFiles as $file) {
            $orderFile = new OrderFile();
            $orderFile->setName($file->getFilename());
            $orderFile->setSize($file->getSize());
            $orderFile->setUser($user);
            $orderFile->setOrder($order);

            $orderFileRepository->save($orderFile);
        }

        $orderFileRepository->flush();

        if ($request->get('isReady')) {
            $order->getStatus()->setCode(StatusOrder::STATUS_ORDER_GUARANTEE_CODE);
            $orderFileRepository->save($order, true);
        }

        return $this->createSupportedJsonResponse($response->assemble());
    }

    protected function handleUpload($file, ResponseInterface $response, Request $request)
    {
        if (!($file instanceof FileInterface)) {
            $file = new FilesystemFile($file);
        }

        $this->validate($file, $request, $response);
        $this->dispatchPreUploadEvent($file, $response, $request);

        $namer = $this->container->get($this->config['namer']);
        $name = $namer->name($file);

        /*$destination = $this->config['storage']['directory'] . DIRECTORY_SEPARATOR . $this->getOrderId();

        $filesystem = new Filesystem();

        if (!$filesystem->exists($destination)) {
            $filesystem->mkdir($destination);
        }*/

        $uploaded = $this->storage->upload($file, $name, $this->getOrderId());

        $this->dispatchPostEvents($uploaded, $response, $request);

        return $uploaded;
    }

    /**
     * @return int
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * @param int $orderId
     */
    public function setOrderId($orderId)
    {
        $this->orderId = $orderId;
    }
}
