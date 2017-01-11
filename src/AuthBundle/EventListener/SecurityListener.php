<?php

namespace AuthBundle\EventListener;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class SecurityListener
{
    protected $security;
    protected $session;

    /**
     * Constructs a new instance of SecurityListener.
     *
     * @param SecurityContext $security The security context
     * @param Session $session The session
     */
    public function __construct(TokenStorageInterface $security, Session $session)
    {
        //You can bring whatever you need here, but for a start this should be useful to you
        $this->security = $security;
        $this->session = $session;
    }

    /**
     * Invoked after a successful login.
     *
     * @param InteractiveLoginEvent $event The event
     */
    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        //Your logic needs to go here
        //You can addRole
        //Even persist if you want but bring the right tools to your constructor
        $security = $this->security;
        //$security = $this->get('security.token_storage')->getToken()->getUser();
        //

        //if ($security->getToken()->getUser()->getId() == 3) {
            //$security->getToken()->getUser()->setRoles(['ROLE_ADMIN', 'ROLE_USER', 'ROLE_TEST']);
            //dump($security->getToken()->getUser());die;
        //}

    }
}