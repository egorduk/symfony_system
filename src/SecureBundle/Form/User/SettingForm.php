<?php

namespace SecureBundle\Form\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use UserBundle\Model\SettingsModel;

class SettingForm extends AbstractType
{
    private $formName = 'settingForm';

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('settings', CollectionType::class, [
                'entry_type' => SettingItemType::class,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SettingsModel::class,
            'entry_options' => ['label' => false],
        ]);
    }

    public function getName()
    {
        return $this->formName;
    }
}