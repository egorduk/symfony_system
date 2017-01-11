<?php

namespace SecureBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;


class SecureController extends Controller
{
    public function indexAction()
    {
        $user = $this->getUser();

        $role = $user->getRoles()[0];

        if ($role == 'ROLE_MANAGER') {
            return new RedirectResponse($this->generateUrl('secure_manager_homepage'));
        } elseif ($role == 'ROLE_AUTHOR') {
            return new RedirectResponse($this->generateUrl('secure_author_homepage'));
        }

        return new RedirectResponse($this->generateUrl('login'));
    }
}
