<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Question;
use App\Entity\Rating;
use App\Entity\RatingQuestion;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RatingQuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('score', IntegerType::class)
            ->add('rating', EntityType::class, [
                'class' =>  Rating::class,
                'multiple' => false
            ])
            ->add('question', EntityType::class, [
                'class' => Question::class
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'        => RatingQuestion::class,
            'csrf_protection'   => false
        ]);
    }
}
