<?php

namespace SecureBundle\Controller;

use SecureBundle\Entity\User;
use SecureBundle\Entity\UserInfo;
use SecureBundle\Form\Manager\ProfileForm;
use SecureBundle\Service\DateTimeService;
use SecureBundle\Service\UserService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
        $user = $this->getUser();
        $session = $request->getSession();

        $dateTimeService = $this->get(DateTimeService::class);
        $lastLoginDateTime = $dateTimeService->getLastLoginDateTime($session);
        $remainingTime = $dateTimeService->getSessionRemainingTimeInSystem($session);

        return compact('user', 'lastLoginDateTime', 'remainingTime');
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

        $userService = $this->get(UserService::class);

        $formProfile = $this->createForm(ProfileForm::class, $user->getUserInfo(), [
            'user' => $user,
        ]);

        $formProfile->handleRequest($request);

        if ($formProfile->isSubmitted() && $formProfile->isValid()) {
            /* @var UserInfo $formData */
            $formData = $formProfile->getData();

            if ($formData->getAvatar() === User::DEFAULT_WOMAN_AVATAR) {
                $formData->getUser()->setAvatar($this->getParameter('default_woman_user_avatar'));
            } elseif ($formData->getAvatar() === User::DEFAULT_MAN_AVATAR) {
                $formData->getUser()->setAvatar($this->getParameter('default_man_user_avatar'));
            } elseif ($formData->getAvatar() === User::DEFAULT_AVATAR) {
                $formData->getUser()->setAvatar($this->getParameter('default_user_avatar'));
            } else {

            }

            $userService->updateProfile($formData);

            $this->addFlash(
                'success',
                'Profile was updated'
            );
        }

        $formProfile = $formProfile->createView();

        //$helper = $this->get('oneup_uploader.templating.uploader_helper');
        //$endpoint = $helper->endpoint('gallery');
        //dump($endpoint);die;

        return compact('user', 'formProfile');
    }
}
