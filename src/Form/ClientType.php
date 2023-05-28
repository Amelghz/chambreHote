<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\ChambreHote;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('nbrPersonne')
            ->add('email')
            ->add('dateArr')
            ->add('dateDep')
            // Commenting out the Image field
            //->add('image', FileType::class, array('data_class' => null))
            ->add('ChambreHote',EntityType::class,[
                'class'=> ChambreHote::class,
                'choice_label'=> 'nomChambre'
                ])
            ->add('Valider', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
