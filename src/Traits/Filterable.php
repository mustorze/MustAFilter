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
     * @param $data
     * @return mixed
     * @throws \Exception
     */
    public static function getFilterArgs($data)
    {
        return self::getFilter()->getFilterArgs($data);
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public static function getFilter()
    {
        $class = self::class;
        return (new $class)->filter;
    }

    /**
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