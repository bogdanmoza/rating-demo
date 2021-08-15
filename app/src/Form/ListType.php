<?php

declare(strict_types=1);

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ListType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('limit', IntegerType::class, [
                'empty_data' => $options['limit']
            ])
            ->add('offset', IntegerType::class, [
                'empty_data' => $options['offset']
            ])
            ->add('orderBy', TextType::class, [
                'empty_data' => $options['orderBy']
            ])
            ->add('direction', ChoiceType::class, [
                'choices' => [
                    'ASC',
                    'DESC'
                ],
                'empty_data' => $options['direction']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefined([
                'limit',
                'offset',
                'orderBy',
                'direction'
            ])
            ->setAllowedTypes('limit', ['null', 'string', 'int'])
            ->setAllowedTypes('offset', ['null', 'string', 'int'])
            ->setAllowedTypes('offset', ['null', 'string'])
            ->setAllowedTypes('direction', ['null', 'string'])
            ->setAllowedValues('direction', ['ASC', 'DESC'])
            ->setDefaults([
                'required'          => false,
                'limit'             => '10',
                'offset'            => '0',
                'orderBy'           => 'created',
                'direction'         => 'DESC',
                'csrf_protection'   => false
            ])
        ;
    }
}
