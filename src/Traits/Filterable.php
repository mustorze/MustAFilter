<?php

namespace Mustorze\MustAFilter\Traits;

/**
 * Trait Filterable
 * @package Mustorze\MustAFilter\Traits
 */
trait Filterable
{
    private $filter = null;

    /**
     * Filterable constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        if (!isset($this->filterClass)) {
            throw new \Exception('filterClass private var do not found in ' . self::class);
        }

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