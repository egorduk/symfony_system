<?php

namespace SecureBundle\Event;

use SecureBundle\Entity\User;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;

final class UserActivityEvent extends Event
{
    const STANDARD_LOGIN = 'standard_login';
    const OPEN_ID_LOGIN = 'open_id_login';
    const LOGOUT = 'logout';
    const SET_BID = 'set_bid';
    const UPDATE_SETTINGS = 'update_settings';
    const CHANGE_ORDER_STATUS = 'change_order_status';
    const CHANGE_STAGE_STATUS = 'change_stage_status';
    const UPLOAD_FILE = 'upload_file';

    private $user;
    private $request;
    private $params;

    public function __construct(User $user, Request $request, $params = null)
    {
        $this->user = $user;
        $this->request = $request;
        $this->params = $params;
    }

    public function getClientIp()
    {
        return $this->request->getClientIp();
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getParams()
    {
        return $this->params;
    }
}