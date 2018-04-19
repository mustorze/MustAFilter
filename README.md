# Must a Filter
A simple management filters for Laravel, use into REST and GraphQL

### Prerequisites
* To run this project, you must have `php >= 7.1`, `laravel\framework >= 5.4.*` and `webonyx/graphql-php ~0.10.0`
* Yes it`s made for Laravel

### Step 1
* Include in yours `composer.json` in the requirements these `"mustorze/mustafilter": "1.0"` then run `composer update`

* Or just run `composer require mustorze/mustafilter`

### Step 2
* In models what you want do use the filter, do you have to add `Mustorze\MustAFilter\Traits\Filterable` Trait

### Step 3
* Create a new Filter class for using in the Filterable models, needs to extends `Mustorze\MustAFilter\Contracts\Filter` abstract class 

* Example, this is a filter for a `user` model
```
<?php

namespace App\Filters;

use Mustorze\MustAFilter\Contracts\Filter;

/**
 * Class UserFilter
 * @package App\Filters
 */
Class UserFilter extends Filter
{
    protected $filters = [
        'email'
    ];

    protected $filtersSpec = [
        'email' => [
            'description' => 'Filter like by email of user'
      ]
    ];

    /**
     * @param $value
     * @return mixed
     */
    protected function email($value)
    {
        return $this->builder->where('email', 'LIKE', "%$value%");
    }
}
```