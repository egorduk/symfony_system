<?php

namespace SecureBundle\Form;

use AuthBundle\Entity\Country;
use AuthBundle\Entity\UserInfo;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Helper\Helper;
use SecureBundle\Service\Helper\UserHelper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;


class ProfileForm extends AbstractType
{
    private $formName = 'profileForm';
    private $em;
    private $countryCodes = [];

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $this->em = $options['entity_manager'];
        $currentCountryId = $options['data']->getCountry()->getId();

        $builder
            ->add('selectorAvatarOptions', TextType::class,
                [
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
            ->add('username', TextType::class,
                [
                    'label' => 'Имя',
                    'attr' => [
                        'class' => 'form-control',
                        'title' => 'Имя должно состоять только из русских букв',
                        'maxlength' => 20,
                        'placeholder' => 'Введите имя',
                    ],
                    'constraints' => [
                        new NotBlank(),
                        new Length(['max' => 20]),
                    ],
                ])
            ->add('surname', TextType::class,
                [
                    'label' => 'Фамилия',
                    'attr' => [
                        'class' => 'form-control',
                        'title' => 'Фамилия должна состоять только из русских букв',
                        'maxlength' => 20,
                        'placeholder' => 'Введите фамилию',
                    ],
                    'constraints' => [
                        new NotBlank(),
                        new Length(['max' => 20]),
                    ],
                ])
            ->add('lastname', TextType::class,
                [
                    'label' => 'Отчество',
                    'attr' => [
                        'class' => 'form-control',
                        'title' => 'Отчество должно состоять только из русских букв',
                        'maxlength' => 20,
                        'placeholder' => 'Введите отчество',
                    ],
                    'constraints' => [
                        new NotBlank(),
                        new Length(['max' => 20]),
                    ],
                ])
            ->add('mobilePhone', TextType::class,
                [
                    'label' => 'Мобильный телефон',
                    'attr' => [
                        'class' => 'form-control',
                        'title' => 'Введите номер мобильного телефона',
                        'maxlength' => 20,
                        'placeholder' => 'Введите номер',
                    ],
                    'constraints' => [
                        new NotBlank(),
                        new Length(['max' => 20]),
                    ],
                ])
            ->add('staticPhone', TextType::class,
                [
                    'label' => 'Стационарный телефон',
                    'required' => false,
                    'attr' => [
                        'class' => 'form-control',
                        'title' => 'Введите номер стационарного телефона',
                        'maxlength' => 20,
                        'placeholder' => 'Введите номер',
                    ],
                    'constraints' => [
                        new Length(['min' => 0, 'max' => 20]),
                    ],
                ])
            ->add('skype', TextType::class,
                [
                    'label' => 'Skype',
                    'required' => false,
                    'attr' => [
                        'class' => 'form-control',
                        'title' => 'Введите Skype',
                        'maxlength' => 20,
                        'placeholder' => 'Введите Skype',
                    ],
                    'constraints' => [
                        new Length(['min' => 0, 'max' => 20]),
                    ],
                ])
          /*  ->add('country', ChoiceType::class,
                [
                    'data' => $currentCountryId,
                    'placeholder' => false,
                    //'mapped' => false,
                    'choices' => $this->getCountryCodes(),
                    'constraints' => new NotBlank(),
                ]) */
            ->add('country', EntityType::class,
                [
                    'class' => Country::class,
                    'choice_label' => 'nameWithMobileCode',
                    'placeholder' => false,
                    'constraints' => new NotBlank(),
                ]);
    }

    public function getName() {
        return $this->formName;
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

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserInfo::class,
            //'trait_choices' => null,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('entity_manager');
    }
}