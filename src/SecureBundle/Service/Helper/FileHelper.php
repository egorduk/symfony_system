<?php

namespace SecureBundle\Service\Helper;

use Doctrine\ORM\EntityManager;
use SecureBundle\Entity\OrderFile;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

class FileHelper
{
    /**
     * @param EntityManager $em
     * @param Router $router
     */
    public function __construct(EntityManager $em, Router $router)
    {
        $this->em = $em;
        $this->router = $router;
    }

    /**
     * @param int $fileId
     *
     * @return OrderFile|null
     */
    public function getFileById($fileId)
    {
        return $this->em
            ->createQueryBuilder()
            ->select('f')
            ->from(OrderFile::class, 'f')
            ->where('f.id = :fileId')
            ->andWhere('f.isDelete = :isDelete')
            ->setParameter('fileId', $fileId)
            ->setParameter('isDelete', 0)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param string $filename
     *
     * @return string
     */
    public function getFileExtension($filename)
    {
        return pathinfo($filename, PATHINFO_EXTENSION);
    }

    public function getFileIcon($fileExtension)
    {
        $uploadsOrdersDir = $this->getParameter('uploads_attachments_orders_dir');
        //$filePath = $uploadsOrdersDir . '/' . $orderId . '/' . $filename;
    }

    /**
     * @param int $fileId
     * @param string $type
     *
     * @return string
     */
    public function getFileUrl($fileId, $type)
    {
        return $this->router->generate('secure_download_file', [
            'type' => $type,
            'fileId' => $fileId,
        ]);
    }
}