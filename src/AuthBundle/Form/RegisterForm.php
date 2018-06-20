<?php

namespace AuthBundle\Form;

use SecureBundle\Entity\User;
use SecureBundle\Service\UserService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;

class RegisterForm extends AbstractType
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class)
            ->add('username', TextType::class)
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options'  => ['label' => 'Password'],
                'second_options' => ['label' => 'Repeat Password'],
            ])
            ->add('termsAccepted', CheckboxType::class, [
                'mapped' => false,
                'constraints' => new IsTrue(),
            ])
            ->add('userInfo', UserInfoForm::class)
            ->addEventListener(FormEvents::POST_SUBMIT, function(FormEvent $event) {
                $form = $event->getForm();
                $data = $event->getData();

                if ($newUsername = $data->getLogin()) {
                    if ($this->userService->isExistsUsername($newUsername)) {
                        $form->get('username')->addError(new FormError('Такой логин уже используется'));
                    }
                }

                if ($newEmail = $data->getEmail()) {
                    if ($this->userService->isExistsEmail($newEmail)) {
                        $form->get('email')->addError(new FormError('Такой Email уже используется'));
                    }
                }
            });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'error_bubbling' => false,
        ]);
    }

    public function getName()
    {
        return '';
    }
}
