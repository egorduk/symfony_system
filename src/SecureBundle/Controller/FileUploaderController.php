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
use SecureBundle\Response\OrderFileUploadSuccessResponse;
use Symfony\Component\HttpFoundation\File\Exception\UploadException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;

class FileUploaderController extends FineUploaderController
{
    private $orderId = 0;
    private $stageOrderId = 0;

    public function upload()
    {
        $request = $this->getRequest();
        $file = $this->getFiles($request->files)[0];

        $response = new OrderFileUploadSuccessResponse();

        $isChunks = null !== $request->get('chunks');

        $this->setOrderId($request->get('orderId'));
        $this->setStageOrderId($request->get('stageOrderId'));

        $uploadedFile = null;

        try {
            $isChunks ?
                $this->handleChunkedUpload($file, $response, $request) :
                $uploadedFile = $this->handleUpload($file, $response, $request);
        } catch (UploadException $e) {
            $this->errorHandler->addException($response, $e);
        }

        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $orderFileRepository = $this->container->get('secure.repository.order_file');
        $orderService = $this->container->get('secure.service.order');
        $order = $orderService->getOneById($this->getOrderId());

        $fileService = $this->container->get('secure.service.file');
        $dateTimeService = $this->container->get('secure.service.date_time');

        $orderFile = new OrderFile();
        $orderFile->setName($uploadedFile->getFilename());
        $orderFile->setSize($uploadedFile->getSize());
        $orderFile->setUser($user);
        $orderFile->setOrder($order);

        $orderFile = $orderFileRepository->save($orderFile, true);

        if ($request->get('isReady') === "true" && $order->isWork()) {
            $status = $this->container->get('secure.service.status_order')->getGuaranteeStatus();
            $order->setStatus($status);
            $orderService->save($order);
        }

        $stageOrderService = $this->container->get('secure.service.stage_order');
        $stageOrder = $stageOrderService->getOneById($this->getStageOrderId());

        if ($stageOrder->isWork()) {
            $stageOrder->setCompleted();
            $stageOrder = $stageOrderService->save($stageOrder);
        }

        $response->offsetSet(0, [
            'fileOrder' => [
                'name' => $orderFile->getName(),
                'dateUpload' => $dateTimeService->getDatetimeFormatted($orderFile->getDateUpload()),
                'size' => $fileService->getSizeFile($orderFile->getSize()),
                'url' => $fileService->getFileUrl($orderFile->getId(), OrderFile::ATTACHMENTS_TYPE),
                'extension' => $fileService->getFileExtension($orderFile->getName()),
            ],
            'stageOrder' => [
                'id' => $stageOrder->getId(),
                'status' => $stageOrder->getStatus(),
            ],
        ]);

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

    /**
     * @return int
     */
    public function getStageOrderId()
    {
        return $this->stageOrderId;
    }

    /**
     * @param int $stageOrderId
     */
    public function setStageOrderId($stageOrderId)
    {
        $this->stageOrderId = $stageOrderId;
    }
}
