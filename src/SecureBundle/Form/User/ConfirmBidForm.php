<?php

namespace SecureBundle\Form\User;

use SecureBundle\Entity\UserBid;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConfirmBidForm extends AbstractType
{
    private $formName = 'confirmBidForm';

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('sum', TextType::class, [
                'label' => 'Сумма',
                'disabled' => true,
            ])
            ->add('day', TextType::class, [
                'label' => 'Количество дней',
                'disabled' => true,
            ])
            ->add('comment', TextareaType::class, [
                'label' => 'Комментарий',
            ])
            ->add('isClientDate', CheckboxType::class, [
                'label' => 'В срок заказчика',
                'disabled' => true,
            ])
            ->add('confirm', SubmitType::class, [
                'label' => 'Принять',
            ])
            ->add('reject', SubmitType::class, [
                'label' => 'Отказаться',
            ]);
            /*->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
                $data = $event->getData();
                dump($data);

                //$event->setData(intval($data->getDay()));
                //$event->setData(intval($data->getSum()));

            })*/
            //->getForm();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
           'data_class' => UserBid::class,
        ]);
    }

    public function getName()
    {
        return $this->formName;
    }
}