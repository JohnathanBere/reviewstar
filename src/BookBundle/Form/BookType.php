<?php
namespace BookBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
            ->add('bookTitle', TextType::class, [ 'label' => 'Title'])
            ->add('bookAuthor', TextType::class, [ 'label' => 'Author'])
            ->add('bookPublisher', TextType::class, [ 'label' => 'Publisher'])
            ->add('bookPublishdate', DateType::class, [
                'label' => 'Publication Date',
                'widget' => 'single_text',
                'html5' => false,
                'attr' => ['class' => 'bs-datepicker']
            ])
            ->add('bookSynopsis', TextareaType::class, [
                'label' => 'Synopsis',
                'attr' => [ 'placeholder' => 'Write a synopsis' ]
            ])
            ->add('submit', SubmitType::class);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BookBundle\Entity\Book',
            'csrf_protection' => false
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