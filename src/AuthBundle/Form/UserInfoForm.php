<?php

namespace AuthBundle\Form;

use SecureBundle\Entity\Country;
use SecureBundle\Entity\UserInfo;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserInfoForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('mobilePhone', TextType::class, [
                'label' => 'Мобильный телефон',
                'attr' => [
                    'class' => 'form-control',
                    'title' => 'Введите номер мобильного телефона',
                    'maxlength' => 30,
                    'placeholder' => 'Введите номер',
                ],
            ])
            ->add('country', EntityType::class, [
                'class' => Country::class,
                'choice_label' => 'nameWithMobileCode',
                'placeholder' => false,
                //'constraints' => [new NotBlank()],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserInfo::class,
            'allow_extra_fields' => true,
        ]);
    }

    public function getName()
    {
        return '';
    }
}