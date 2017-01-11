<?php

namespace SecureBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class AuthorController extends Controller
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
        $metaDateHelper = $this->get('secure.meta_data_helper');

        $whenLoginDate = $dateTimeHelper->getDateFromTimestamp($sessionCreatedTimestamp, "d/m/Y H:i");
        $sessionRemainingTimestamp = $dateTimeHelper->getRemainingTimestamp($sessionCreatedTimestamp, $sessionLifeTimestamp, '+');
        $nowTimestamp = $dateTimeHelper->getCurrentTimestamp();
        $sessionRemainingTimestamp = $dateTimeHelper->getRemainingTimestamp($sessionRemainingTimestamp, $nowTimestamp, '-');
        $remainingTime = $dateTimeHelper->getDateFromTimestamp($sessionRemainingTimestamp, "i:s");
        $user = $metaDateHelper->setUserAvatar($user);

        $templateData = [
            'user' => $user,
            'whenLoginDate' => $whenLoginDate,
            'remainingTime' => $remainingTime,
            'userRole' => $metaDateHelper->getRoleName($user->getRoles()[0]),
        ];

        return $templateData;
    }
}
