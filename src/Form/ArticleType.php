<?php

namespace App\Form;

use DateTime;
use App\Entity\Article;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('creationDate', DateTimeType::class)
            ->add('title')
            ->add('subtitle')
            ->add('content')
            ->add('state', ChoiceType::class, [
                'choices' => [
                    'Public' => 1,
                    'Personnel' => 2,
                ],
            ])
            ->add('picturePath')
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'choice_value' => 'id',
                /*'choice_attr' => function ($choice, $key, $value) {
                    return ['data-price' => $choice->getPrice()];
                }*/
            ])
            ->add('save', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
