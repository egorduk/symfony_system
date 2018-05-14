<?php

namespace SecureBundle\Form\User;

use SecureBundle\Entity\StageOrder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StageOrderType extends AbstractType
{
    private $stages;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->stages = $options['stages'];

        $builder
            ->add('name', EntityType::class, [
                'class' => StageOrder::class,
                'choices' => $this->stages,
                'choice_label' => 'name',
                'placeholder' => false,
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'stages' => null,
        ]);
    }

    public function getName()
    {
        return '';
    }
}
