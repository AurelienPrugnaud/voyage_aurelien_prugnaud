<?php

namespace App\Form;

use App\Entity\Admin;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username')
			->add('roles', ChoiceType::class, array(
				'attr'  =>  array('class' => 'form-control'),
				'choices' =>
					array
					(
						'ROLE_ADMIN' => 'ROLE_ADMIN',
						'ROLE_USER' => 'ROLE_USER',
					)
			,
				'multiple' => true,
				'expanded' => true,
				'required' => true,
			))
			->add('password')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Admin::class,
        ]);
    }
}
