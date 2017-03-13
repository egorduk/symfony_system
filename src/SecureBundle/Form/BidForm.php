<?php

namespace SecureBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class BidForm extends AbstractType
{
    private $formName = 'bidForm';

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fieldSum', TextType::class, [
                'required' => true,
                'label' => 'Сумма'/*, 'data' => $options['data']->fieldSum*/,
                'constraints' => [
                    new NotBlank(),
                    new Length(array('max' => 7)),
                    new Type('digit'),
                ],
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
            ->add('fieldDay', TextType::class, [
                'required' => false,
                'label'=>'Количество дней',/* 'data' => $options['data']->fieldDay, */
                'constraints' => [
                    new Length(array('max' => 3)),
                    new Type('digit'),
                ],
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
            ->add('fieldComment', TextareaType::class, [
                'required' => false,
                'label'=>'Комментарий',/* 'data' => $options['data']->fieldComment,*/
                'constraints' => [
                    new Length(array('max' => 150)),
                ],
                'attr' => [
                    'class' => 'form-control',
                    'title' => 'Введите комментарий для заказчика',
                    'size' => 30,
                    'maxlength' => 150,
                    'placeholder' => 'Введите комментарий'
                ],
            ])
            ->add('isClientDate', CheckboxType::class, [
                'label'=>'В срок заказчика',/* 'data' => filter_var($options['data']->is_client_date, FILTER_VALIDATE_BOOLEAN),*/
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

    public function getName()
    {
        return $this->formName;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
    }
}