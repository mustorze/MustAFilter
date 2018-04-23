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
class UserFilter extends Mustorze\MustAFilter\Contracts\Filter
{
    /**
     * Where we declare all filters can be used in the model
     */
    protected $filters = [
        'email'
    ];

    /**
    * If your using GraphQL, here we declare the type and description of this filter
    */
    protected $filtersSpec = [
        'email' => [
            'type' => Type::string(),
            'description' => 'Filter like by email of user'
      ]
    ];

    /**
     * Filter will be applied to the constructor, as you can see with the same name as declared in $filters;
     *
     * @param $value
     * @return mixed
     */
    protected function email($value)
    {
        return $this->builder->where('email', 'LIKE', "%$value%");
    }
}
```
##### All ready

### How to use

## GraphQL
This is a default query in GraphQL
```
class UsersQuery extends Query
{
    /**
    * To makes things easy, i`ve create a const for the filter can i use in this query
    */
    const FILTER = UserFilter::class; // it`s the same was created before

    /**
    * Query default configuration
    */
    protected $attributes = [
        'name' => 'Admin users query',
        'description' => 'The pagination of users'
    ];

    /**
    * Query default type
    */
    public function type()
    {
        return GraphQL::paginate('user');
    }

    /**
     * Here is the first place we can modify, in this moment we need to use a `getFilterArgs` method to Get all the
     * filters we created in the Filter.
     * When you use `Filterable` trait, your model own the `getFilterArgs` automatic.
     * 1st param - The filter, you can create a infinites filters to use in your queries
     * 2nd param - The defaults args, pass in array the default args can you always do to the query
     */
    public function args()
    {
        return User::getFilterArgs($this::FILTER, [
            'page' => [
                'name' => 'page',
                'type' => Type::nonNull(Type::int()),
                'description' => 'The page'
            ],
            'limit' => [
                'name' => 'limit',
                'type' => Type::nonNull(Type::int()),
                'description' => 'The limit'
            ]
        ]);
    }

    /**
    * The default resolve
    */
    public function resolve($root, $args, SelectFields $fields, ResolveInfo $info)
    {
        $select = $fields->getSelect();
        $with = $fields->getRelations();

        /**
        * The second place to modify we found here, we need to pass filter scope to the builder, and then he will validate 
        * and apply your filters in the query.
        * 1st param - The filter, you can create a infinites filters to use in your queries
        * 2nd param - This param is for determine your are using in the GraphQL querie, just pass `true`, for detection.
        * 3rd param - There we pass the args of query, it`s simple, we need to get the passed values from query to makes things working.
        */
        return User::select($select)
            ->with($with)
            ->filter($this::FILTER, true, $args) // The filter
            ->paginate($args['limit'], $select, 'page', $args['page']);
    }
}
```
If your followed all the steps well, you can easily test your query passing the filter your want in args of your query
#### Now we know how to use in GraphQL

### REST