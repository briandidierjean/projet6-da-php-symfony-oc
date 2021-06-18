<?php


namespace App\Form;


use App\Entity\Trick;
use App\Entity\TrickGroup;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Form\VideoType;
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
            ->add('mainPhoto', FileType::class, [
                'label' => 'Photo de couverture du trick :',
                'required' => false,
                'mapped' => false,
            ])
            ->add('photos', FileType::class, [
                'label' => 'Photos du trick :',
                'mapped' => false,
                'required' => false,
                'multiple' => true,
            ])
            ->add('videos', CollectionType::class, [
                'entry_type' => VideoType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'label'=> false,
                'mapped' => false,
            ])
            ->add('trickGroup',EntityType::class, [
                'class' => TrickGroup::class,
                'choice_label'=> 'name',
                'label'=> 'Groupe du trick',
                'required' => true,
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