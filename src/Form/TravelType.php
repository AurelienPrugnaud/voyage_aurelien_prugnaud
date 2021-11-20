<?php

namespace App\Form;

use App\Entity\Categories;
use App\Entity\Tag;
use App\Entity\Travel;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TravelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('hook')
            ->add('description')
            ->add('price')
            ->add('duration')
            ->add('image1')
            ->add('image2')
            ->add('image3')
            ->add('pdf')
			->add('category', EntityType::class, [
				'class' => Categories::class,
				'choice_label'=>'name',
				'required' => true
			])
			->add('tag', EntityType::class, [
				'class' => Tag::class,
				'multiple' => true,
				'choice_label' => 'name',
				'expanded' => true,
				'required' => true
			]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Travel::class,
        ]);
    }
}
