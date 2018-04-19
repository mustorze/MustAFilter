<?php

namespace Mustorze\MustAFilter\Traits;

/**
 * Trait Filterable
 * @package Mustorze\MustAFilter\Traits
 */
trait Filterable
{
    /**
     * @var null Await for filter instanced class
     */
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
     * Generate the data for GraphQL Args
     *
     * @param $data
     * @return mixed
     * @throws \Exception
     */
    public static function getFilterArgs($data)
    {
        return self::getFilter()->getFilterArgs($data);
    }

    /**
     * Get instanced Filter class
     *
     * @return mixed
     * @throws \Exception
     */
    public static function getFilter()
    {
        $class = self::class;
        return (new $class)->filter;
    }

    /**
     * Apply the filters in current builder
     *
     * @param $builder
     * @param bool $graphQL
     * @param array $args
     * @return mixed
     */
    public function scopeFilter($builder, $graphQL = false, array $args = [])
    {
        return $this->filter->apply($builder, $graphQL, $args);
    }
}