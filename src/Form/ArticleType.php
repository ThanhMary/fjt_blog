<?php

namespace App\Form;

use DateTime;
use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;


class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('creationDate', DateTimeType::class)
            ->add('title')
            ->add('subtitle')
            ->add('content')
            ->add('state')
            ->add('picturePath')
            // ->add('date')
            // ->add('category_id')
            // ->add('category_id', EntityType::class, [
            //     'class' => Category::class,
            //     'choice_label' => 'name',
            //     'choice_value' => 'id',
            //     'expanded' => true,
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
