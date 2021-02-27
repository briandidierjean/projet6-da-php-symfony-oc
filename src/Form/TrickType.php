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
            ->add('photos', FileType::class, [
                'label' => 'Photos du trick :',
                'mapped' => false,
                'required' => false,
                'multiple' => true,
                /*'constraints' => [
                    new File([
                        'mimeTypes' => [
                            'image/png',
                            'image/jpeg',
                        ],
                        'mimeTypesMessage' => 'Veuillez poster des photos au format .png ou .jpg.',
                    ])
                ],*/
            ])
            ->add('video', TextType::class, [
                'label' => 'Video du trick (lien) :',
                'required' => false,
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