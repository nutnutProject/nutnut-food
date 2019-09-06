<?php

namespace App\Form;

use App\Entity\Recette;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecetteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',TextType::class)
            ->add('photo',FileType::class, array('data_class' => null))
            ->add('category', null, [
                'choice_label' => 'name'
            ])
            ->add('diet', null, [
                'choice_label' => 'name',
                'expanded' => 'true', 
            ])
            ->add('description', TextareaType::class)
            ->add('online');

        $builder->add('ingredients', CollectionType::class, [
            'entry_type' => IngredientType::class,
            'entry_option' => ['label' => false],
            'allow_add' => true,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Recette::class,
        ]);
    }
}
// tu sens mauvais