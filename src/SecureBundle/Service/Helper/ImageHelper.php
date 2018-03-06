<?php

namespace SecureBundle\Service\Helper;

use AuthBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use SecureBundle\Entity\OrderFile;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Config\Definition\Exception\InvalidTypeException;

class ImageHelper
{
    private $width = 0;
    private $height = 0;
    private $uh = null;

    /**
     * @param EntityManager $em
     * @param Router $router
     */
    public function __construct(EntityManager $em, Router $router, UserHelper $uh)
    {
        $this->em = $em;
        $this->router = $router;
        $this->uh = $uh;
    }

    public function createThumbnail($file, $newWidth, $newHeight, $user, $destination)
    {
        $this->width = $newWidth;
        $this->height = $newHeight;

        $sourceImagePath = $file->getLinkTarget();
        $sourceImageMimeType = $file->getMimeType();
        $fileName = basename($sourceImagePath);

        list($width, $height) = getimagesize($sourceImagePath);
        $ratioOriginal = $width / $height;

        if ($newWidth / $newHeight > $ratioOriginal) {
            $newWidth = $newHeight * $ratioOriginal;
        } else {
            $newHeight = $newWidth / $ratioOriginal;
        }

        $thumbnail = imagecreatetruecolor($newWidth, $newHeight);

        if ($sourceImageMimeType === 'image/jpeg') {
            header('Content-Type: image/jpeg');

            $source = imagecreatefromjpeg($sourceImagePath);
        } elseif ($sourceImageMimeType === 'image/png') {
            header('Content-Type: image/png');

            $source = imagecreatefrompng($sourceImagePath);
        } elseif ($sourceImageMimeType === 'image/gif') {
            header('Content-Type: image/gif');

            $source = imagecreatefromgif($sourceImagePath);
        } elseif ($sourceImageMimeType === 'image/bmp') {
            header('Content-Type: image/bmp');

            $source = imagecreatefrombmp($sourceImagePath);
        } else {
            throw new InvalidTypeException();
        }

        $response = false;

        try {
            $isResampled = imagecopyresampled($thumbnail, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

            /**
             * @var User $user
             */
            $userRole = $this->uh->getRoleName($user->getRoles()[0], true);
            $userFolder = $userRole . DIRECTORY_SEPARATOR . $user->getId();

            //var_dump('123');die;

            if (!file_exists($destination . DIRECTORY_SEPARATOR . $userFolder)) {
                mkdir($destination . DIRECTORY_SEPARATOR . $userFolder, 0755);
            }

            if ($isResampled) {
                $response = imagejpeg($thumbnail,  $destination . DIRECTORY_SEPARATOR . $userFolder . DIRECTORY_SEPARATOR . $fileName, 100);
            }
        } catch (Exception $ex) {
            var_dump($ex->getMessage());die;
        }

        return $response;
    }
}