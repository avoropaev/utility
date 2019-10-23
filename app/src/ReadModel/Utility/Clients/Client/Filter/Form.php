<?php

declare(strict_types=1);

namespace App\ReadModel\Utility\Clients\Client\Filter;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Form extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('id', Type\IntegerType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'ID',
                    'onchange' => 'this.form.submit()'
                ]
            ])
            ->add('name', Type\TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Name',
                    'onchange' => 'this.form.submit()'
                ]
            ])
            ->add('secret_key', Type\TextType::class, [
                'property_path' => 'secretKey',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Secret Key',
                    'onchange' => 'this.form.submit()'
                ]
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Filter::class,
            'method' => 'GET',
            'csrf_protection' => false
        ]);
    }
}
