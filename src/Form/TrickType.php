<?php


namespace App\Form;


use App\Entity\Trick;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrickType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du trick : ',
                'required' => true,
            ])
            ->add('description', TextType::class, [
                'label' => 'Description du trick :',
                'required' => true,
            ])
            ->add('photo', FileType::class, [
                'label' => 'Photo n°1 du trick :',
                'required' => true,
            ])
            ->add('photo', FileType::class, [
                'label' => 'Photo n°2 du trick :',
                'required' => true,
            ])
            ->add('photo', FileType::class, [
                'label' => 'Photo n°3 du trick :',
                'required' => true,
            ])
            ->add('video', TextType::class, [
                'label' => 'Video du trick (lien) :',
                'required' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
        ]);
    }
}