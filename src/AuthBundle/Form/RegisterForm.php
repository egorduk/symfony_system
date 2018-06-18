<?php

namespace AuthBundle\Form;

use SecureBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;

class RegisterForm extends AbstractType
{
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
            ]);

            /* ->add('selectorCountry', 'genemu_jqueryselect2_entity', array(
                'mapped'   => false,
                'class' => 'Acme\AuthBundle\Entity\Country',
                'property' => 'code'
            ))
            ->add('selectorCountry', 'choice', array(
                'mapped'   => false,
                'choices' => $this->buildChoices(),
            ));*/

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function(FormEvent $event)
        {
            $form = $event->getForm();
            $data = $event->getData();

            if ($data->getLogin() !== null)
            {
                $newLogin = $form->get('fieldLogin')->getData();

                /*if (user name exists)
                {
                    $form->get('username')->addError(new FormError('Такой логин уже используется!'));
                }*/
            }

            if ($data->getEmail() !== null)
            {
                $newEmail = $form->get('fieldEmail')->getData();

               /* if (user email exists)
                {
                    $form->get('email')->addError(new FormError('Такой Email уже используется!'));
                }*/
            }
        });
    }

   /* protected function buildChoices()
    {
        $container = Helper::getContainer();
        $choices = [];
        $table2Repository = $container->get('doctrine')->getRepository('Acme\AuthBundle\Entity\Country');
        $table2Objects = $table2Repository->findAll();

        foreach ($table2Objects as $table2Obj) {
            $choices[$table2Obj->getCode()] = $table2Obj->getName();
        }

        return $choices;
    }*/

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'user' => null,
        ]);
    }

    public function getName()
    {
        return '';
    }
}
