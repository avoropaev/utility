<?php

declare(strict_types=1);

namespace App\ReadModel\Utility\Clients\Site\Filter;

use App\ReadModel\Utility\Clients\Client\ClientFetcher;
use App\ReadModel\Utility\Clients\ProductGroup\ProductGroupFetcher;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Form extends AbstractType
{
    /**
     * @var ClientFetcher
     */
    private $clients;

    /**
     * @var ProductGroupFetcher
     */
    private $productGroups;

    /**
     * Form constructor.
     * @param ClientFetcher $clients
     * @param ProductGroupFetcher $productGroups
     */
    public function __construct(ClientFetcher $clients, ProductGroupFetcher  $productGroups)
    {
        $this->clients = $clients;
        $this->productGroups = $productGroups;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var Filter $filter */
        $filter = $options['data'];

        $allProductGroups = $filter->client !== null
            ? $this->productGroups->listByClient($filter->client)
            : $this->productGroups->allList();

        $productGroups = [];
        foreach ($allProductGroups as $item) {
            $productGroups[$item->name] = $item->id;
        }

        $clients = [];
        foreach ($this->clients->allList() as $item) {
            $clients[$item->name] = $item->id;
        }

        $builder
            ->add('id', Type\IntegerType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'ID',
                    'onchange' => 'this.form.submit()'
                ]
            ])
            ->add('client', Type\ChoiceType::class, [
                'choices' => $clients,
                'required' => false,
                'placeholder' => 'All clients',
                'attr' => [
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
            ->add('product_groups', Type\ChoiceType::class, [
                'property_path' => 'productGroups',
                'choices' => $productGroups,
                'required' => false,
                'multiple' => true,
                'placeholder' => 'All Product Groups',
                'attr' => [
                    'class' => 'selectpicker',
                    'title' => 'All Product Groups',
                    'data-size' => '10',
                    'data-live-search' => 'true',
                    'data-selected-text-format' => 'count > 3',
                    'data-actions-box' => 'true',
                    'data-style' => 'btn-outline-input'
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
