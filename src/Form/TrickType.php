<?php


namespace App\Form;


use App\Entity\Trick;
use App\Entity\TrickGroup;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
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
            ->add('description', TextareaType::class, [
                'label' => 'Description du trick :',
                'required' => true,
            ])
            ->add('photos', FileType::class, [
                'label' => 'Photos du trick :',
                'mapped' => false,
                'required' => false,
                'multiple' => true,
            ])
            ->add('videos', TextType::class, [
                'label' => 'Video du trick (lien) :',
                'required' => false,
            ])
            ->add('trickGroup',EntityType::class, [
                'class' => TrickGroup::class,
                'choice_label'=> 'name',
                'label'=> 'Groupe du trick',
                'required'=> true,
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
        ]);
    }
}