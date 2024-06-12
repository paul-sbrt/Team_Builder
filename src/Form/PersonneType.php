<?php

namespace App\Form;

use App\Entity\Personne;
use App\Entity\Team;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PersonneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',null,[ 'attr'=> ['placeholder'=>'Name']])
            ->add('lastname',null,[ 'attr'=> ['placeholder'=>'LastName']])
            ->add('teams', EntityType::class, [
                'class' => Team::class,
'choice_label' => 'name',
'multiple' => false,
                'mapped'=>false,

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Personne::class,
        ]);
    }
}
