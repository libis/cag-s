<?php

namespace Search\Form\Element;

use Zend\Form\Element\Select;

class SearchPageSelect extends Select
{
    protected $apiManager;

    public function getValueOptions()
    {
        $response = $this->getApiManager()->search('search_pages');
        $searchPages = $response->getContent();

        $options = [];
        foreach ($searchPages as $searchPage) {
            $options[$searchPage->id()] = $searchPage->name();
        }

        return $options;
    }

    public function setApiManager($apiManager)
    {
        $this->apiManager = $apiManager;
        return $this;
    }

    public function getApiManager()
    {
        return $this->apiManager;
    }
}
