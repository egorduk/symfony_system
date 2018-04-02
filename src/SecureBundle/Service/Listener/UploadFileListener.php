<?php

namespace SecureBundle\Service\Listener;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Oneup\UploaderBundle\Event\PostPersistEvent;
use SecureBundle\Service\FileService;
use SecureBundle\Service\ImageService;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Config\Definition\Exception\InvalidTypeException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class UploadFileListener
{
    private $em;
    private $fileService;
    private $imageService;
    private $tokenStorage;

    public function __construct(EntityManager $em, FileService $fileService, ImageService $imageService, TokenStorage $tokenStorage)
    {
        $this->em = $em;
        $this->fileService = $fileService;
        $this->imageService = $imageService;
        $this->tokenStorage = $tokenStorage;

        $this->response = [];
        $this->options = [
            'maxWidth' => 100,
            'maxHeight' => 100,
        ];
    }

    public function onUpload(PostPersistEvent $event)
    {
        $response = $event->getResponse();
        $file = $event->getFile();
        $config = $event->getConfig();
        $request = $event->getRequest();
        $orderId = $request->request->get('orderId');

        $destination = $config['storage']['directory'];
        //var_dump($destination);die;

        $newWidth = $this->options['maxWidth'];
        $newHeight = $this->options['maxHeight'];

        $user = $this->tokenStorage->getToken()->getUser();

        //$response['response'] = $this->imageService->createThumbnail($file, $newWidth, $newHeight, $user, $destination);

        return $response;
    }
}