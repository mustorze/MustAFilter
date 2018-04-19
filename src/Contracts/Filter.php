<?php

namespace Mustorze\MustAFilter\Contracts;

use GraphQL\Type\Definition\Type;

/**
 * Class Filter
 * @package Mustorze\MustAFilter\Contracts
 */
abstract class Filter
{
    /**
     * @var The query builder
     */
    protected $builder;

    /**
     * @var bool The request is a GraphQL
     */
    protected $graphQL = false;

    /**
     * @var array The args of GraphQL
     */
    protected $args = [];

    /**
     * @var array List of Filters
     */
    protected $filters = [];

    /**
     * @var array Specification of Filters for GraphQL Schema
     */
    protected $filtersSpec = [];

    /**
     * Generate Args for GraphQL
     *
     * @param $data
     * @return array
     */
    public function getFilterArgs($data)
    {
        foreach ($this->filters as $filter) {
            $filtered = [
                $filter => [
                    'name' => $filter,
                    'type' => isset($this->filtersSpec[$filter]['type']) ? $this->filtersSpec[$filter]['type'] : Type::string(),
                    'description' => isset($this->filtersSpec[$filter]['description']) ? $this->filtersSpec[$filter]['description'] : "A $filter"
                ]
            ];

            $data = array_merge($data, $filtered);
        }

        return $data;
    }

    /**
     * Apply the filters in Query
     *
     * @param $builder
     * @param bool $graphQL
     * @param $args
     * @return mixed
     */
    public function apply($builder, bool $graphQL, array $args = [])
    {
        $this->builder = $builder;
        $this->graphQL = $graphQL;
        $this->args = $args;

        foreach ($this->getFilters() as $filter => $value) {
            if (method_exists($this, $filter)) {
                $this->$filter($value);
            }
        }

        return $this->builder;
    }

    /**
     * Get the list filters
     *
     * @return array
     */
    private function getFilters()
    {
        if (!$this->graphQL) {
            return array_filter(request()->only($this->filters));
        }

        return $this->args;
    }
}