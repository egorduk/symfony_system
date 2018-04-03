<?php

namespace SecureBundle\Controller;

use AuthBundle\Entity\User;
use SecureBundle\Entity\OrderFile;
use SecureBundle\Form\ProfileForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SecureController extends Controller
{
    public function indexAction()
    {
        $user = $this->getUser();

        $role = $user->getRoles()[0];

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
            $file = $this->get('secure.service.file')->getFileById($fileId);
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

    /*public function profileAction(Request $request)
    {
        $user = $this->getUser();

        $userHelper = $this->get('secure.user_helper');

        $user = $userHelper->setRawUserAvatar($user);

        $formProfile = $this->createForm(ProfileForm::class, $user->getUserInfo(), [
            'entity_manager' => $this->getDoctrine()->getManager()
        ]);

        $formProfile->handleRequest($request);

        if ($formProfile->isSubmitted() && $formProfile->isValid()) {
            $formData = $formProfile->getData();
            $userHelper->updateProfile($formData);

            $this->addFlash(
                'success',
                'Profile was updated!'
            );
        }

        $templateData = [
            'user' => $user,
            'userRole' => $userHelper->getRoleName($user->getRoles()[0]),
            'formProfile' => $formProfile->createView(),
        ];

        //$helper = $this->get('oneup_uploader.templating.uploader_helper');
        //$endpoint = $helper->endpoint('gallery');
        //dump($endpoint);die;

        return $templateData;
    }*/
}
