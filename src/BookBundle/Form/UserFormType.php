<?php

namespace BookBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserFormType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [ 'label' => 'Username' ])
            ->add('roles', ChoiceType::class, [
                'label' => 'Gain additional privileges',
                'choices' => [
                    'Moderator' => 'ROLE_MOD',
                    'Book Admin' => 'ROLE_BOOK_ADMIN',
                    'User Admin' => 'ROLE_USER_ADMIN',
                    'Site Admin' => 'ROLE_SITE_ADMIN'
                ],
                'expanded' => true,
                'multiple' => true,
                'required' => false
            ])
            ->add('submit', SubmitType::class);
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ReviewStar\BookBundle\Entity\User'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'reviewstar_bookbundle_user';
    }
}