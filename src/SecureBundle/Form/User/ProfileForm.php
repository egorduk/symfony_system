<?php

namespace SecureBundle\Form\User;

use SecureBundle\Entity\Country;
use SecureBundle\Entity\User;
use SecureBundle\Entity\UserInfo;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProfileForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('avatar', ChoiceType::class, [
                'label' => 'Аватар',
                'required' => false,
                'choices' => $this->getAvatarChoices(),
                'placeholder' => false,
                'data' => $this->setCurrentAvatar($options['user']),
            ])
            ->add('userName', TextType::class, [
                'label' => 'Имя',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'title' => 'Имя должно состоять только из русских букв',
                    'maxlength' => 30,
                    'placeholder' => 'Введите имя',
                ],
            ])
            ->add('surName', TextType::class, [
                'label' => 'Фамилия',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'title' => 'Фамилия должна состоять только из русских букв',
                    'maxlength' => 30,
                    'placeholder' => 'Введите фамилию',
                ],
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Отчество',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'title' => 'Отчество должно состоять только из русских букв',
                    'maxlength' => 30,
                    'placeholder' => 'Введите отчество',
                ],
            ])
            ->add('mobilePhone', TextType::class, [
                'label' => 'Мобильный телефон',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'title' => 'Введите номер мобильного телефона',
                    'maxlength' => 30,
                    'placeholder' => 'Введите номер',
                ],
            ])
            ->add('staticPhone', TextType::class, [
                'label' => 'Стационарный телефон',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'title' => 'Введите номер стационарного телефона',
                    'maxlength' => 30,
                    'placeholder' => 'Введите номер',
                ],
            ])
            ->add('skype', TextType::class, [
                'label' => 'Skype',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'title' => 'Введите Skype',
                    'maxlength' => 30,
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
                    'maxlength' => 20,
                    'placeholder' => 'Введите БИК',
                ],
            ])
            ->add('dateBirthday', DateType::class, [
                'label' => 'Дата рождения',
                'required' => false,
                //'input' => 'string',
                'years' => range(date('Y') - 100, date('Y') - 18),
                'months' => range(1, 12),
                'attr' => [
                    'class' => 'form-control',
                    'title' => 'Введите дату рождения',
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

        $builder->get('dateBirthday')->addModelTransformer(new CallbackTransformer(
            function ($value) {
                if (!$value) {
                    return new \DateTime('now -18 years');
                }

                return $value;
            },
            function ($value) {
                return $value;
            }
        ));
    }

    protected function getAvatarChoices()
    {
        return [
            'По умолчанию' => User::DEFAULT_AVATAR,
            'Мужской' => User::DEFAULT_MAN_AVATAR,
            'Женский' => User::DEFAULT_WOMAN_AVATAR,
            'Загрузить свой' => User::CUSTOM_AVATAR,
        ];
    }

    protected function setCurrentAvatar(User $user)
    {
        $avatar = $user->getAvatar();

        if ($avatar === 'default_m.jpg') {
            return User::DEFAULT_MAN_AVATAR;
        } elseif ($avatar === 'default_w.jpg') {
            return User::DEFAULT_WOMAN_AVATAR;
        } elseif ($avatar === 'default.png') {
            return User::DEFAULT_AVATAR;
        }

        return User::CUSTOM_AVATAR;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserInfo::class,
            'user' => null,
        ]);
    }

    public function getName()
    {
        return '';
    }
}