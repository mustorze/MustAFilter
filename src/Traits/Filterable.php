<?php

namespace Mustorze\MustAFilter\Traits;

use Mustorze\MustAFilter\Contracts\Filter;

/**
 * Trait Filterable
 * @package Mustorze\MustAFilter\Traits
 */
trait Filterable
{
    /**
     * Generate the data for GraphQL Args
     *
     * @param Filter $filter
     * @param $data
     * @return mixed
     */
    public static function getFilterArgs(Filter $filter, $data)
    {
        return (new $filter)->getFilterArgs($data);
    }

    /**
     * Apply the filters in current builder
     *
     * @param $builder
     * @param Filter $filter
     * @param array $args
     * @return mixed
     */
    public function scopeFilter($builder, Filter $filter, array $args = [])
    {
        return (new $filter)->apply($builder, $args);
    }
}