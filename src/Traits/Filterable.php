<?php

namespace Mustorze\MustAFilter\Traits;

/**
 * Trait Filterable
 * @package Mustorze\MustAFilter\Traits
 */
trait Filterable
{
    protected $filterClass = null;
    private $filter = null;

    public function __construct()
    {
        $this->filter = new $this->filterClass;
    }

    /**
     * @param $builder
     * @param bool $graphQL
     * @return mixed
     */
    public function scopeFilter($builder, $graphQL = false)
    {
        return $this->filter->apply($builder, $graphQL);
    }
}