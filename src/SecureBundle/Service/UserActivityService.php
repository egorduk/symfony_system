<?php

namespace SecureBundle\Service;

use AuthBundle\Entity\User;
use SecureBundle\Entity\UserActivity;
use SecureBundle\Repository\UserActivityRepository;

class UserActivityService
{
    private $userActivityRepository;

    public function __construct(UserActivityRepository $userActivityRepository)
    {
        $this->userActivityRepository = $userActivityRepository;
    }

    /**
     * @param User $user
     * @param string $eventAction
     * @param string $ip
     * @param array $additionalInfo
     */
    public function logActivity(User $user, $eventAction, $ip, $additionalInfo = null)
    {
        $activity = new UserActivity();
        $activity->setUser($user);
        $activity->setAction($eventAction);
        $activity->setIpAddress(ip2long($ip));
        $activity->setAdditionalInfo(json_encode($additionalInfo));

        $this->userActivityRepository->save($activity, true);
    }

    public function getUserActivities(User $user)
    {
        return $this->userActivityRepository->findBy(['user' => $user]);
    }
}
