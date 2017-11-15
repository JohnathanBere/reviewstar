<?php
namespace ReviewStar\BookBundle\Form;

use Doctrine\DBAL\Types\DateType;
use Symfony\Component\Form\AbstractType;
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
            ->add('bookAuthor', [ 'placeholder' => 'Enter the name of the author'])
            ->add('bookTitle', [ 'placeholder' => 'Enter the name of the book' ])
            ->add('bookPublisher', [ 'placeholder' => 'Enter the name of the publisher' ])
            ->add('bookPublishdate', DateType::class, [ 'placeholder' => 'Enter the date when the book published' ])
            ->add('bookSynopsis', TextareaType::class, [
                'attr' => array('placeholder' => 'Write a synopsis')
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