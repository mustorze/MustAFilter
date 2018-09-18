<?php

namespace Mustorze\MustAFilter\Traits;

/**
 * Trait Filterable
 * @package Mustorze\MustAFilter\Traits
 */
trait Filterable
{
    /**
     * Generate the data for GraphQL Args
     *
     * @param $filter
     * @param $data
     * @return mixed
     */
    public static function getFilterArgs($filter, $data)
    {
        return (new $filter)->getFilterArgs($data);
    }

    /**
     * Apply the filters in current builder
     *
     * @param $builder
     * @param $filter
     * @param array $args
     * @return mixed
     */
    public function scopeFilter($builder, $filter, array $args = [])
    {
        return (new $filter)->apply($builder, $args);
    }
}