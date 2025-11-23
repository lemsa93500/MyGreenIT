<?php

namespace App\Form;

use App\Entity\Action;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', null, ['label' => 'Titre'])
            ->add('description', TextareaType::class, ['label' => 'Description'])
            ->add('basePoints', IntegerType::class, ['label' => 'Points de base'])
            ->add('category', ChoiceType::class, [
                'label' => 'Catégorie',
                'choices' => [
                    'PC' => 'PC',
                    'Réseau' => 'Réseau',
                    'Mail' => 'Mail',
                    'Cloud' => 'Cloud',
                    'Dev' => 'Dev',
                    'Matériel' => 'Matériel',
                    'Bureau' => 'Bureau',
                    'Mobile' => 'Mobile',
                ],
            ])
            ->add('difficulty', ChoiceType::class, [
                'label' => 'Difficulté',
                'choices' => [
                    'Très facile (1)' => 1,
                    'Facile (2)' => 2,
                    'Moyen (3)' => 3,
                    'Difficile (4)' => 4,
                    'Expert (5)' => 5,
                ],
            ])
            ->add('isActive', CheckboxType::class, [
                'label' => 'Active',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Action::class]);
    }
}
