<?php

namespace App\Form;

use App\Entity\Recette;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class RecetteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',TextType::class)
            ->add('category', null, [
                'choice_label' => 'name',
            ])
            ->add('diet', null, [
                'choice_label' => 'name',
                'expanded' => 'true', 
            ])
            ->add('description', TextareaType::class)
            ->add('online',null,[
                'label' => 'En ligne',
            ])
        ;

        // Si nouvelle utlisateur, on affiche le champ password
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $form = $event->getForm();
            $recette = $event->getData();

            if(!$recette || null === $recette->getId())
            {
                $form->add('photo',FileType::class);

            }
        });

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Recette::class,
        ]);
    }
}