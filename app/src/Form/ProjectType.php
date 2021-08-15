<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Member;
use App\Entity\Project;
use App\Entity\Vico;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class)
            ->add('vico', EntityType::class, [
                'class' => Vico::class
            ])
            ->add('member', EntityType::class, [
                'class' => Member::class
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'        => Project::class,
            'csrf_protection'   => false
        ]);
    }
}
