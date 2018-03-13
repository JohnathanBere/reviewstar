<?php
namespace BookBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReviewType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('reviewTitle', TextType::class, [ 'label' => 'Title' ])
            ->add('reviewRating', ChoiceType::class, [
                'label' => 'Rate this book',
                'choices' => [
                    '0' => 0.0,
                    '1' => 1.0,
                    '2' => 2.0,
                    '3' => 3.0,
                    '4' => 4.0,
                    '5' => 5.0
                ],
                'expanded' => true,
                'multiple' => false,
                'preferred_choices' => '0'
            ])
            ->add('reviewContent', TextareaType::class, [
                'label' => 'Message',
                'attr' => [ 'placeholder' => 'Write a review' ]
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
            'data_class' => 'ReviewStar\BookBundle\Entity\Review'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'reviewstar_bookbundle_review';
    }
}