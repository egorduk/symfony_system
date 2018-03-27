<?php

namespace UserBundle\Form;

use SecureBundle\Entity\UserBid;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BidForm extends AbstractType
{
    private $formName = 'bidForm';

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('sum', TextType::class, [
                'required' => true,
                'label' => 'Сумма',
                'attr' => [
                    'class' => 'form-control',
                    'maxlength' => 7,
                    'placeholder' => 'Введите сумму',
                    'data-toggle' => 'tooltip',
                    'data-placement' => 'top',
                    'data-trigger'=> 'manual',
                    'title' => 'Только числа',
                ],
            ])
            ->add('day', TextType::class, [
                'required' => false,
                'label' => 'Количество дней',
                'attr' => [
                    'class' => 'form-control',
                    'maxlength' => 3,
                    'placeholder' => 'Введите количество дней',
                    'data-toggle' => 'tooltip',
                    'data-placement' => 'top',
                    'data-trigger'=> 'manual',
                    'title' => 'Только числа'
                ],
            ])
            ->add('comment', TextareaType::class, [
                'required' => false,
                'label' => 'Комментарий',
                'attr' => [
                    'class' => 'form-control',
                    'title' => 'Введите комментарий для заказчика',
                    'size' => 30,
                    'maxlength' => 150,
                    'placeholder' => 'Введите комментарий'
                ],
            ])
            ->add('isClientDate', CheckboxType::class, [
                'label' => 'В срок заказчика',/* 'data' => filter_var($options['data']->is_client_date, FILTER_VALIDATE_BOOLEAN),*/
                'required' => false,
                'attr' => [
                    'class' => '',
                    'title' => 'Установите флажок, если Вы согласны выполнить заказ в срок, указанный заказчиком'
                ],
            ]);

        /* $builder->addEventListener(FormEvents::POST_BIND, function(FormEvent $event) {
             $form = $event->getForm();
             $fieldIsClientDate = $form->get('isClientDate')->getData();
             $fieldDay = $form->get('fieldDay')->getData();
             if (isset($fieldIsClientDate) && $fieldIsClientDate != null && $fieldDay != null && is_numeric($fieldDay)) {
                 $form->get('fieldDay')->addError(new FormError('Ошибка!'));
             }
         });*/
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