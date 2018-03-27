<?php

namespace SecureBundle\EventListener;

use Doctrine\Common\Persistence\ObjectManager;
use Oneup\UploaderBundle\Event\PostPersistEvent;
use SecureBundle\Service\FileService;
use SecureBundle\Service\Helper\FileHelper;
use SecureBundle\Service\Helper\ImageHelper;
use SecureBundle\Service\ImageService;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Config\Definition\Exception\InvalidTypeException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class UploadFileListener
{
    /**
     * @var ObjectManager
     */
    private $om;

    /**
     * @var FileHelper
     */
    private $fh;

    /**
     * @var ImageHelper $ih
     */
    private $ih;

    /**
     * @var TokenStorage $ts
     */
    private $ts;

    public function __construct(ObjectManager $om, FileService $fh, ImageService $ih, TokenStorage $ts)
    {
        $this->om = $om;
        $this->fh = $fh;
        $this->ih = $ih;
        $this->ts = $ts;

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

        $destination = $config['storage']['directory'];
        //var_dump($destination);die;

        $newWidth = $this->options['maxWidth'];
        $newHeight = $this->options['maxHeight'];

        $user = $this->ts->getToken()->getUser();

        $response['response'] = $this->ih->createThumbnail($file, $newWidth, $newHeight, $user, $destination);

        return $response;
    }
}