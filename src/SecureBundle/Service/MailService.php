<?php

namespace SecureBundle\Service;

use SecureBundle\Entity\User;
use Doctrine\ORM\EntityManager;

class MailService
{
    private $em;
    private $options;
    private $mailer;

    /**
     * @param EntityManager $em
     * @param array $options
     * @param \Swift_Mailer $mailer
     */
    public function __construct(EntityManager $em, $options, \Swift_Mailer $mailer)
    {
        $this->em = $em;
        $this->options = $options;
        $this->mailer = $mailer;
    }

    /**
     * @param User $user
     * @param string $newPassword
     *
     * @return bool
     */
    public function sendRecoveryPasswordMail(User $user, $newPassword)
    {
        $mailSender = $this->options['mail_sender'];
        $mailTitle = $this->options['mail_title'];
        $confirmAuthPath = $this->options['confirm_auth_path'];

        $hashCode = $user->getHashCode();
        $userId = $user->getId();
        $userEmail = $user->getEmail();

        $messageBody = sprintf('Ваш новый пароль %s. Для подтверждения смены пароля нажмите <a href="%s?type=%s&hash_code=%s&id=%d">сюда</a>',
            $newPassword,
            $confirmAuthPath,
            'recovery',
            $hashCode,
            $userId
        );

        $message = \Swift_Message::newInstance()
            ->setSubject('Recovering password')
            ->setFrom($mailSender, $mailTitle)
            ->setTo($userEmail)
            //->setTo('a_1300@mail.ru')
            ->setBody(
                '<html>' .
                '<head></head>' .
                '<body>' .
                '<p>' . $messageBody . '</p>' .
                '</body>' .
                '</html>',
                'text/html'
            );
        //->setBody($this->renderView('HelloBundle:Hello:email', array('name' => $name)));
        $this->mailer->send($message);

        return true;
    }

    /**
     * @param User $user
     *
     * @return bool
     */
    public function sendConfirmRegistrationMail(User $user)
    {
        $mailSender = $this->options['mail_sender'];
        $mailTitle = $this->options['mail_title'];
        $confirmAuthPath = $this->options['confirm_auth_path'];

        $userId = $user->getId();
        $userEmail = $user->getEmail();
        $hashCode = $user->getHashCode();

        $messageBody = sprintf('Для подтверждения регистрации нажмите <a href="%s?type=%s&hash_code=%s&id=%d">сюда</a>',
            $confirmAuthPath,
            'register',
            $hashCode,
            $userId
        );

        $message = \Swift_Message::newInstance()
            ->setSubject('Confirm registration')
            ->setFrom($mailSender, $mailTitle)
            ->setTo($userEmail)
            //->setTo("a_1300@mail.ru")
            ->setBody(
                '<html>' .
                '<head></head>' .
                '<body>' .
                '<p>' . $messageBody . '</p>' .
                '</body>' .
                '</html>',
                'text/html'
            );
        $this->mailer->send($message);

        return true;
    }
}