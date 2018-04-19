<?php

namespace Mustorze\MustAFilter\Contracts;

/**
 * Class Filter
 * @package Mustorze\MustAFilter\Contracts
 */
abstract class Filter
{
    protected $builder;
    protected $graphQL;
    protected $filters = [];


    /**
     * @param $builder
     * @param bool $graphQL
     * @return mixed
     */
    public function apply($builder, bool $graphQL)
    {
        $this->builder = $builder;
        $this->graphQL = $graphQL;

        dd($builder);

        foreach ($this->getFilters() as $filter => $value) {
            if (method_exists($this, $filter)) {
                $this->$filter($value);
            }
        }

        return $this->builder;
    }

    /**
     * @return array
     */
    private function getFilters()
    {
        return array_filter($this->request->only($this->filters));
    }
}