<?php

namespace SecureBundle\Controller;

use SecureBundle\Entity\User;
use SecureBundle\Entity\OrderFile;
use SecureBundle\Service\FileService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SecureController extends Controller
{
    public function indexAction()
    {
        $user = $this->getUser();
        $role = $user->getRole();

        if ($role === User::ROLE_MANAGER) {
            return new RedirectResponse($this->generateUrl('secure_manager_homepage'));
        } elseif ($role === User::ROLE_USER) {
            return new RedirectResponse($this->generateUrl('secure_user_homepage'));
        } elseif ($role === User::ROLE_ADMIN) {
            return new RedirectResponse($this->generateUrl('secure_admin_homepage'));
        }

        return new RedirectResponse($this->generateUrl('login'));
    }

    /**
     * @param int $fileId
     * @param string $type
     *
     * @return BinaryFileResponse
     */
    public function downloadFileAction($fileId, $type)
    {
        $filePath = '';
        $filename = '';

        if ($type === OrderFile::ATTACHMENTS_TYPE) {
            $file = $this->get(FileService::class)->getFileById($fileId);
            $filename = $file->getName();
            $uploadsOrdersDir = $this->getParameter('file_upload_dir_order_attachments');
            $orderId = $file->getOrder()->getId();
            $filePath = $uploadsOrdersDir . DIRECTORY_SEPARATOR . $orderId . DIRECTORY_SEPARATOR . $filename;
            //$filePath = $uploadsOrdersDir . DIRECTORY_SEPARATOR . $filename;
        }

        if (!file_exists($filePath)) {
            throw new NotFoundHttpException();
        }

        $response = new BinaryFileResponse($filePath);
        $response->trustXSendfileTypeHeader();
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $filename,
            iconv('UTF-8', 'ASCII//TRANSLIT', $filename)
        );

        return $response;
    }
}
