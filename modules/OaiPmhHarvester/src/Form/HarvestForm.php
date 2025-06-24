<?php
namespace OaiPmhHarvester\Form;

use Zend\Form\Element;
use Omeka\Form\Element\ResourceTemplateSelect;
use Zend\Form\Form;

class HarvestForm extends Form
{
    public function init()
    {
        $this->setAttribute('action', 'oaipmhharvester/sets');

        $this
            ->add([
                'name' => 'endpoint',
                'type' => Element\Text::class,
                'options' => [
                    'label' => 'OAI-PMH endpoint', // @translate
                    'info' => 'The base URL of the OAI-PMH data provider.', // @translate
                ],
                'attributes' => [
                    'id' => 'endpoint',
                    'required' => true,
                    // The protocol requires http, but most of repositories
                    // support it.
                    'placeholder' => 'https://example.org/oai-pmh-repository/request',
                ],
            ])
            ->add([
                'name' => 'harvest_all_records',
                'type' => Element\Checkbox::class,
                'options' => [
                    'label' => 'Skip listing of sets and harvest all records', // @translate
                ],
                'attributes' => [
                    'id' => 'harvest_all_records',
                ],
            ])
            ->add([
                'name' => 'sets',
                'type' => Element\Textarea::class,
                'options' => [
                    'label' => 'Skip listing of sets and harvest only these sets', // @translate
                    'info' => 'Set one set identifier and a metadata prefix by line. Separate the set and the prefix by "=". If no prefix is set, "dcterms" or "oai_dc" will be used.', // @translate
                ],
                'attributes' => [
                    'id' => 'sets',
                    'row' => 10,
                    'placeholder' => 'digital:serie-alpha = mets
humanities:serie-beta',
                ],
            ])
            ->add([
                'name' => 'resource_template',
                'type' => ResourceTemplateSelect::class,
                'options' => [
                    'label' => 'Resource template', // @translate
                    'empty_option' => '',
                ],
                'attributes' => [
                    'class' => 'chosen-select',
                    'data-placeholder' => 'Resource template',
                    'id' => 'resource_template',
                ],

            ])
            ->add([
                'name' => 'from',
                'type' => Element\Text::class,
                'options' => [
                    'label' => 'From', // @translate
                    'info' => 'The start date for the harvest (UTC format f.i. 2021-01-01T00:00:00Z).', // @translate
                ],
                'attributes' => [
                    'id' => 'from',
                    // The protocol requires http, but most of repositories
                    // support it.
                    'placeholder' => '2021-01-01T00:00:00Z',
                ],
            ])
            ->add([
                'name' => 'until',
                'type' => Element\Text::class,
                'options' => [
                    'label' => 'Until', // @translate
                    'info' => 'The end date for the harvest (UTC format f.i. 2021-01-01T00:00:00Z).', // @translate
                ],
                'attributes' => [
                    'id' => 'until',
                    // The protocol requires http, but most of repositories
                    // support it.
                    'placeholder' => '2021-01-01T00:00:00Z',
                ],
            ])
            ->add([
                'name' => 'resource_type',
                'type' => Element\Radio::class,
                'options' => [
                    'label' => 'Import items or items sets', // @translate
                    'value_options' => array(
                        'items' => 'items',
                        'item_sets' => 'item sets',
                    ),
                ],
                'attributes' => [
                    'id' => 'recourd_type',
                ],
            ]);

        $inputFilter = $this->getInputFilter();
        $inputFilter
            ->add([
                'name' => 'endpoint',
                'required' => true,
            ]);
    }
}
