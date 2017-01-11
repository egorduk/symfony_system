<?php

namespace AuthBundle\Service\Helper;

use AuthBundle\Entity\User;
use Doctrine\ORM\EntityManager;
//use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;

class UserHelper
{
    private $em;
    private $options;
    private $mailer;
    private $passwordEncoder;

    /**
     * @param EntityManager $em
     * @param array $options
     * @param \Swift_Mailer $mailer
     */
    public function __construct(EntityManager $em, $options, \Swift_Mailer $mailer, UserPasswordEncoder $passwordEncoder)
    {
        $this->em = $em;
        $this->options = $options;
        $this->mailer = $mailer;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param int $size
     *
     * @return string
     */
    public function getRandomValue($size)
    {
        return bin2hex(random_bytes($size));
    }

    /**
     * @param User $user
     * @param string $password
     *
     * @return string
     */
    public function getEncodePassword(User $user, $password)
    {
        return $this->passwordEncoder->encodePassword($user, $password);
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
     * @param string $hashCode
     * @param int $userId
     *
     * @return bool
     */
    public function isCorrectConfirmUrl($hashCode, $userId)
    {
        return (iconv_strlen($hashCode) == 60) && ($userId > 0);
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