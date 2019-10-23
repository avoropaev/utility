<?php

declare(strict_types=1);

namespace App\ReadModel\Utility\Clients\ProductGroup\Filter;

use App\ReadModel\Utility\Clients\Client\ClientFetcher;
use App\ReadModel\Utility\Clients\Site\SiteFetcher;
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
     * @var SiteFetcher
     */
    private $sites;

    /**
     * Form constructor.
     * @param ClientFetcher $clients
     * @param SiteFetcher $sites
     */
    public function __construct(ClientFetcher $clients, SiteFetcher $sites)
    {
        $this->clients = $clients;
        $this->sites = $sites;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var Filter $filter */
        $filter = $options['data'];

        $allSites = $filter->client !== null
            ? $this->sites->listByClient($filter->client)
            : $this->sites->allList();

        $sites = [];
        foreach ($allSites as $item) {
            $sites[$item->name] = $item->id;
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
            ->add('guid', Type\TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'GUID',
                    'onchange' => 'this.form.submit()'
                ]
            ])
            ->add('sites', Type\ChoiceType::class, [
                'choices' => $sites,
                'required' => false,
                'multiple' => true,
                'placeholder' => 'All sites',
                'attr' => [
                    'class' => 'selectpicker',
                    'title' => 'All sites',
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
