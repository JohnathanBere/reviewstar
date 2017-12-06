<?php
namespace ReviewStar\BookBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('bookAuthor')
            ->add('bookTitle')
            ->add('bookPublisher')
            ->add('bookPublishdate', DateType::class)
            ->add('bookSynopsis', TextareaType::class, [
                'attr' => array('placeholder' => 'Write a synopsis')
            ])
            ->add('bookCover', FileType::class, [
                'label' => 'Book Cover',
                'data_class' => null
            ])
            ->add('submit', SubmitType::class);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ReviewStar\BookBundle\Entity\Book'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'reviewstar_bookbundle_book';
    }


}