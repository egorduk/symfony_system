<?php

namespace SecureBundle\Controller;

use SecureBundle\Entity\User;
use SecureBundle\Form\Manager\ProfileForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class ManagerController extends Controller
{
    /**
     * @Template()
     *
     * @param Request $request
     *
     * @return array
     */
    public function homeAction(Request $request)
    {
        /* @var User $user */
        $user = $this->getUser();

        $session = $request->getSession();
        $sessionCreatedTimestamp = $session->getMetadataBag()->getCreated();
        $sessionLifeTimestamp = $session->getMetadataBag()->getLifetime();

        $dateTimeHelper = $this->get('secure.service.date_time');
        $userService = $this->get('secure.service.user');

        $whenLoginDate = $dateTimeHelper->getDateFromTimestamp($sessionCreatedTimestamp, 'd/m/Y H:i');
        $sessionRemainingTimestamp = $dateTimeHelper->getRemainingTimestamp($sessionCreatedTimestamp, $sessionLifeTimestamp, '+');
        $nowTimestamp = $dateTimeHelper->getCurrentTimestamp();
        $sessionRemainingTimestamp = $dateTimeHelper->getRemainingTimestamp($sessionRemainingTimestamp, $nowTimestamp, '-');
        $remainingTime = $dateTimeHelper->getDateFromTimestamp($sessionRemainingTimestamp, 'i:s');

        return [
            'user' => $user,
            'whenLoginDate' => $whenLoginDate,
            'remainingTime' => $remainingTime,
            'userRole' => $userService->getRoleName($user),
        ];
    }

    /**
     * @Template()
     *
     * @param Request $request
     *
     * @return array
     */
    public function profileAction(Request $request)
    {
        $user = $this->getUser();

        $userHelper = $this->get('secure.service.user');

        //$user = $userHelper->setRawUserAvatar($user);

        $formProfile = $this->createForm(ProfileForm::class, $user->getUserInfo()/*, [
            'entity_manager' => $this->getDoctrine()->getManager()
        ]*/);

        $formProfile->handleRequest($request);

        if ($formProfile->isSubmitted() && $formProfile->isValid()) {
            $formData = $formProfile->getData();
            $userHelper->updateProfile($formData);

            $this->addFlash(
                'success',
                'Profile was updated'
            );
        }

        $templateData = [
            'user' => $user,
            'userRole' => $userHelper->getRoleName($user),
            'formProfile' => $formProfile->createView(),
        ];

        //$helper = $this->get('oneup_uploader.templating.uploader_helper');
        //$endpoint = $helper->endpoint('gallery');
        //dump($endpoint);die;

        return $templateData;
    }
}
