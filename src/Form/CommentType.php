<?php

namespace App\Form;

use App\Entity\Comment;
use App\Entity\User;
use App\Entity\Artilce;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('content')
            ->add('date')
            ->add('state')
            ->add('user', EntityType::class, [
                'class'=> UserType::class, 
                'choice_label'=>'lastName',
                'choice_value'=>'id',
            ])
            ->add('article', EntityType::class, [
                'class'=>ArticleType::class, 
                'choice_label'=>'title',
                'choice_value'=>'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
