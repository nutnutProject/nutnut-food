<?php

namespace App\Form;

use App\Entity\Recette;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecetteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('slug')
            ->add('photo')
            ->add('category', null, [
                'choice_label' => 'name'
            ])
            ->add('diet', null, [
                'choice_label' => 'name',
                'expended' => 'true', 
            ])
            ->add('description')
            ->add('online')
            ->add('validate')
            ->add('creation_date')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Recette::class,
        ]);
    }
}
// tu sens mauvais