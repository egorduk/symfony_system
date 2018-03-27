<?php

namespace UserBundle\Form;

use SecureBundle\Entity\Setting;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SettingItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'disabled' => true,
            ])
            ->add('value', ChoiceType::class, [
                'choices' => [
                    'on' => '1',
                    'off' => '0',
                ],
                'choices_as_values' => true,
                'multiple' => false,
                'expanded' => true,
                'label' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Setting::class,
        ));
    }

    public function getName()
    {
        return '';
    }
}
