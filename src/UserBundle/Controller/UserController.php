<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    /**
     * @Template()
     *
     * @return array
     */
    public function homepageAction(Request $request)
    {
        $user = $this->getUser();

        $session = $request->getSession();
        $sessionCreatedTimestamp = $session->getMetadataBag()->getCreated();
        $sessionLifeTimestamp = $session->getMetadataBag()->getLifetime();

        $dateTimeHelper = $this->get('secure.date_time_helper');
        $userHelper = $this->get('secure.user_helper');

        $whenLoginDate = $dateTimeHelper->getDateFromTimestamp($sessionCreatedTimestamp, "d/m/Y H:i");
        $sessionRemainingTimestamp = $dateTimeHelper->getRemainingTimestamp($sessionCreatedTimestamp, $sessionLifeTimestamp, '+');
        $nowTimestamp = $dateTimeHelper->getCurrentTimestamp();
        $sessionRemainingTimestamp = $dateTimeHelper->getRemainingTimestamp($sessionRemainingTimestamp, $nowTimestamp, '-');
        $remainingTime = $dateTimeHelper->getDateFromTimestamp($sessionRemainingTimestamp, "i:s");
        $user = $userHelper->setRawUserAvatar($user);

        $templateData = [
            'user' => $user,
            'whenLoginDate' => $whenLoginDate,
            'remainingTime' => $remainingTime,
            'userRole' => $userHelper->getRoleName($user->getRoles()[0]),
        ];

        return $templateData;
    }

    public function bidsAction(Request $request)
    {
        $bidHelper = $this->get('secure.bid_helper');

        $bidsData = $bidHelper->getBidsWithOrdersByUser($this->getUser());
        $bidsData = $bidHelper->setRemainingTime($bidsData);

        return $this->render(
            'SecureBundle:Author:bidsPage.html.twig',
            [
                'bidsData' => $bidsData,
            ]
        );
    }
}
