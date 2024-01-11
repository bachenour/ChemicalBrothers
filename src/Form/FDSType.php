<?php

namespace App\Form;

use App\Entity\FDS;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FDSType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('filePath')
            ->add('chemicalName')
            ->add('practice')
            ->add('dangerWarnings')
            ->add('cautionaryAdvice')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FDS::class,
        ]);
    }
}
