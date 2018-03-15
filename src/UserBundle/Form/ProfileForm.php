<?php

namespace UserBundle\Form;

use AuthBundle\Entity\Country;
use AuthBundle\Entity\UserInfo;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProfileForm extends AbstractType
{
    private $formName = 'profileForm';
    private $em;
    private $countryCodes = [];

    public function buildForm(FormBuilderInterface $builder, array $options) {
        //$this->em = $options['entity_manager'];
        //$currentCountryId = $options['data']->getCountry()->getId();

        $builder
            ->add('selectorAvatarOptions', TextType::class, [
                'label' => 'Аватар',
                'mapped' => false,
                'required' => false,
                //'error_bubbling' => true,
                //'data' => $options['data']->selectorAvatarOptions,
                //'expanded' => true,
                //'multiple' => false,
                //'invalid_message' => 'Ошибка!',
                //'constraints' => new NotBlank([
                //'message' => 'Ошибка!',
                //]),
                //'attr' => array('class' => 'radio-inline'),
                //'choices' => $this->buildAvatarChoices()
            ])
            ->add('username', TextType::class, [
                'label' => 'Имя',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'title' => 'Имя должно состоять только из русских букв',
                    'maxlength' => 20,
                    'placeholder' => 'Введите имя',
                ],
            ])
            ->add('surname', TextType::class, [
                'label' => 'Фамилия',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'title' => 'Фамилия должна состоять только из русских букв',
                    'maxlength' => 20,
                    'placeholder' => 'Введите фамилию',
                ],
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Отчество',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'title' => 'Отчество должно состоять только из русских букв',
                    'maxlength' => 20,
                    'placeholder' => 'Введите отчество',
                ],
            ])
            ->add('mobilePhone', TextType::class, [
                'label' => 'Мобильный телефон',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'title' => 'Введите номер мобильного телефона',
                    'maxlength' => 20,
                    'placeholder' => 'Введите номер',
                ],
            ])
            ->add('staticPhone', TextType::class, [
                'label' => 'Стационарный телефон',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'title' => 'Введите номер стационарного телефона',
                    'maxlength' => 20,
                    'placeholder' => 'Введите номер',
                ],
            ])
            ->add('skype', TextType::class, [
                'label' => 'Skype',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'title' => 'Введите Skype',
                    'maxlength' => 20,
                    'placeholder' => 'Введите Skype',
                ],
            ])
            ->add('account', TextType::class, [
                'label' => 'Р/С',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'title' => 'Введите расчетный счет',
                    'maxlength' => 30,
                    'placeholder' => 'Введите расчетный счет',
                ],
            ])
            ->add('bic', TextType::class, [
                'label' => 'БИК',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'title' => 'Введите БИК',
                    'maxlength' => 15,
                    'placeholder' => 'Введите БИК',
                ],
            ])
            ->add('dateBirthday', DateType::class, [
                'label' => 'Дата рождения',
                'required' => false,
                //'input' => 'string',
                'attr' => [
                    'class' => 'form-control',
                    'title' => 'Введите дату рождения',
                    //'maxlength' => 15,
                    'placeholder' => 'Введите дату рождения',
                ],
            ])
            ->add('country', EntityType::class, [
                'class' => Country::class,
                'choice_label' => 'nameWithMobileCode',
                'placeholder' => false,
                'required' => false,
                'constraints' => new NotBlank(),
            ]);
    }

    /* protected function getCountryCodes() {
         $countries = $this->em->getRepository('AuthBundle:Country')->findAll();

         foreach ($countries as $country) {
             $this->countryCodes[$country->getName() . ' ' . $country->getMobileCode()] = $country->getId();
         }

         return $this->countryCodes;
     }*/

    protected function buildAvatarChoices() {
        $choices['man'] = 'Мужской';
        $choices['woman'] = 'Женский';
        $choices['custom'] = 'Загрузить свой';

        return $choices;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserInfo::class,
            //'trait_choices' => null,
        ]);

        //$resolver->setRequired('entity_manager');
    }

    public function getName() {
        return $this->formName;
    }
}