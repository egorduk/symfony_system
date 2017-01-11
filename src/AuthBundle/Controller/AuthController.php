<?php

namespace AuthBundle\Controller;

use AuthBundle\Entity\User;
use AuthBundle\Form\AuthorRegForm;
use AuthBundle\Form\RecoveryPasswordForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class AuthController extends Controller
{
    /**
     * @Template()
     *
     * @return array
     */
    public function loginAction()
    {
        $helper = $this->get('security.authentication_utils');

        return [
            'lastEmail' => $helper->getLastUsername(),
            'error' => $helper->getLastAuthenticationError(),
        ];
    }

    /**
     * @throws \Exception
     */
    public function logoutAction()
    {
        throw new \Exception('This should never be reached!');
    }

    /**
     * @Template()
     *
     * @param Request $request
     *
     * @return array
     */
    public function registerAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(AuthorRegForm::class, $user);
        $form->handleRequest($request);

        $captchaService = $this->get('auth.captcha_service');

        $publicKeyCaptcha = $this->getParameter('publicKeyCaptcha');
        $captchaForm = $captchaService->captchaGetHtml($publicKeyCaptcha);
        $captchaError = "";

        if ($form->isSubmitted() && $form->isValid()) {
            $privateKeyCaptcha = $this->getParameter('privateKeyCaptcha');

            $captchaCheckAnswer = $captchaService->captchaCheckAnswer(
                $privateKeyCaptcha,
                $request
            );

            if ($captchaCheckAnswer->getIsValid()) {
                $password = $this->get('security.password_encoder')
                    ->encodePassword($user, $user->getPlainPassword());
                $user->setPassword($password);
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

                $userHelper = $this->get('auth.user_helper');
                $userHelper->sendConfirmRegistrationMail($user);

                return $this->redirectToRoute('login');
            } else {
                $captchaError = $captchaCheckAnswer->getErrorMessage();
            }
        }

        return $templateData = [
            'form' => $form->createView(),
            'captcha' => [
                'captchaForm' => $captchaForm,
                'captchaError' => $captchaError,
            ],
        ];
    }

    /**
     * @Template()
     *
     * @return array
     */
    public function recoveryAction(Request $request)
    {
        $form = $this->createForm(RecoveryPasswordForm::class);
        $form->handleRequest($request);
        $showWindow = false;

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->getData()['email'];

            $em = $this->getDoctrine()->getManager();
            /**@var User $user**/
            $user = $em->getRepository('AuthBundle:User')
                ->findOneBy(['email' => $email]);

            if (!is_null($user)) {
                $userHelper = $this->get('auth.user_helper');
                $hashCode = $userHelper->getRandomValue(60);
                $newPassword = $userHelper->getRandomValue(8);
                $encodePassword = $userHelper->getEncodePassword($user, $newPassword);
                $user->setHashCode($hashCode);
                $user->setPassword($encodePassword);
                $em->flush();
                $userHelper->sendRecoveryPasswordMail($user, $newPassword);
                $showWindow = true;
            }
        }

        return $templateData = [
            'form' => $form->createView(),
            'showWindow' => $showWindow,
        ];
    }

    /**
     * Confirm register and recovery password by Email
     *
     * @Template()
     *
     * @param string $typeConfirm
     * @param string $hashCode
     * @param int $userId
     *
     * @return array
     */
    public function confirmAction($typeConfirm, $hashCode, $userId)
    {
        $userHelper = $this->get('auth.user_helper');

        $isCorrectUrl = $userHelper->isCorrectConfirmUrl($hashCode, $userId);
        $isSuccess = false;

        if ($isCorrectUrl) {
            if ($typeConfirm === 'recovery' || $typeConfirm === 'register') {
                $em = $this->getDoctrine()->getManager();
                /**@var User $user * */
                $user = $em->getRepository('AuthBundle:User')
                    ->findOneBy([
                        'id' => $userId,
                        'hashCode' => $hashCode,
                        'isConfirm' => ($typeConfirm === 'recovery') ?: 0,
                    ]);

                if (!is_null($user)) {
                    ($typeConfirm === 'register') ? $user->setRegisterConfirm() : $user->setRecoveryPasswordConfirm();

                    $em->flush();

                    $isSuccess = true;
                }
            }
        }

        return array('isSuccess' => $isSuccess);
    }
}