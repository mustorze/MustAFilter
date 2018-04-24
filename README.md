# Must a Filter
A simple management filters for Laravel, use into REST and GraphQL

### Prerequisites
* To run this project, you must have `php >= 7.1`, `laravel\framework >= 5.4.*` and `webonyx/graphql-php ~0.10.0`
* Yes it`s made for Laravel

### Step 1
* Include in yours `composer.json` in the requirements these `"mustorze/mustafilter": "1.0"` then run `composer update`

* Or just run `composer require mustorze/mustafilter`

### Step 2
* Add `Mustorze\MustAFilter\Traits\Filterable` trait to models you want to filter.

### Step 3
* Extend your Filter class from from `Mustorze\MustAFilter\Contracts\Filter` Abstract one

* This is a example filter for a `user` model
```
class UserFilter extends Mustorze\MustAFilter\Contracts\Filter
{
    /**
     * Declare here all the filters that can be used in the model
     */
    protected $filters = [
        'email'
    ];

    /**
    * If you're using GraphQL declare here the type and description of the filter
    */
    protected $filtersSpec = [
        'email' => [
            'type' => Type::string(),
            'description' => 'like filter by user email'
      ]
    ];

    /**
     * The filter will be applied to the constructor with the name declared in $filters
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
    * To makes things easy, i've create a const for the filter i will use in this query
    */
    const FILTER = UserFilter::class; // it's the same class that was created before

    /**
    * Query default configuration
    */
    protected $attributes = [
        'name' => 'Admin users query',
        'description' => 'The query pagination of users'
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
        * The second place to modify we found here, we need to pass filter scope to the builder, and then he will
        * validate and apply your filters in the query.
        * 1st param - The filter, you can create a infinites filters to use in your queries
        * 2nd param - This param is for determine your are using in the GraphQL querie, just pass `true`, for
        * detection.
        * 3rd param - There we pass the args of query, it`s simple, we need to get the passed values from query to
        * makes things working.
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
In REST we usually make a query with some arguments we needs to use and we return the results of this query for the requesters

Example:
```
public function fetchAllUsers()
{
    return User::where('status', 1) // a default query settings
        ->get(); 
}
```
With the Filter, you need to add the Filter scope to the constructor. the Filter Scope automatically detects the arguments
in the request and apply in the query
```
public function fetchAllUsers()
{
    return User::where('status', 1) // a default query settings
        ->filter(UserFilter::class) // do not need to pass the further parameters
        ->get(); 
}
```
Now if this request is a POST or GET, and have a `email` param in the request, the `email` filter its applied to the builder
`localhost/users/?email=example.com`
The filter we was created are applying a `where like` in query, all the results than have `example.com` in email column 
will be returned