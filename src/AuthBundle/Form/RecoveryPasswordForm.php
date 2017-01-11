<?php

namespace AuthBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RecoveryPasswordForm extends AbstractType
{
    private $formName = 'recoveryPasswordForm';

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'constraints' => [
                    new Email(),
                    new NotBlank(),
                    new Length(array('max' => 80)),
                ],
                'attr' => [
                    'class' => 'form-control',
                    'title' => 'Input your email',
                    'size' => 20,
                    'maxlength' => 80,
                    'placeholder' => 'Email'
                ],
            ]);
    }

    public function getName()
    {
        return $this->formName;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
    }
}