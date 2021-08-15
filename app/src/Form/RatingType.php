<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Project;
use App\Entity\Rating;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RatingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('score', NumberType::class)
            ->add('comment', TextType::class)
            ->add('project', EntityType::class, [
                'class' => Project::class
            ])
            ->add('ratingQuestions', CollectionType::class, [
                'entry_type'    => RatingQuestionType::class,
                'allow_add'     => true,
                'allow_delete'  => true,
                'by_reference'  => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'            => Rating::class,
            'csrf_protection'       => false
        ]);
    }
}
