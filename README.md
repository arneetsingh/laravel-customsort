# Manually sort records of a eloquent model in custom order

[![Latest Version on Packagist](https://img.shields.io/packagist/v/arneetsingh/laravel-customsort.svg?style=flat-square)](https://packagist.org/packages/arneetsingh/laravel-customsort)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/arneetsingh/laravel-customsort/run-tests?label=tests)](https://github.com/arneetsingh/laravel-customsort/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/arneetsingh/laravel-customsort/Check%20&%20fix%20styling?label=code%20style)](https://github.com/arneetsingh/laravel-customsort/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/arneetsingh/laravel-customsort.svg?style=flat-square)](https://packagist.org/packages/arneetsingh/laravel-customsort)

#### Problem it solves:
Say there is a need to show certain records of posts, eg. featured posts on top in your listing page.
Just a custom order set by user to show specific posts in a specific order on top.
## Installation

You can install the package via composer:

```bash
composer require arneetsingh/laravel-customsort
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="laravel-customsort-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-customsort-config"
```

This is the contents of the published config file:

```php
return [
];
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="laravel-customsort-views"
```

## Usage

#### Model
Use the `HasCustomSortTrait` trait in your model you want to have the ability of sorting manually.
```php
<?php

namespace App\Models;

use ArneetSingh\CustomSort\Traits\CanCustomSort;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use CanCustomSort;
}

```

#### Setting the order
Lets say we have 5 posts in our Post model.
and we want to set them in order of ids - 2,4,1,5,3

```php
Post::setNewOrder([2, 4, 1, 5, 3]);
```

Do note you don't need to pass all of your posts, you can though. Just the ones passed will be returned on top in that order.

#### Fetching in Custom Order
```php
Post::orderByCustom()->get();
```
Will return post in order set first, and then rest of posts.

#### Set Priority per model basis
```php
$post->setOrderPriority(10);
```
If the priority no is higher than other posts, it will be moved to top in results.

#### Example
Lets say we have 5 posts in our Post model.
and we want to set them in order of ids - 2,4,1,5,3
```php
Request: POST
Endpoint: /posts/customSort
Payload:
{
	"custom_sort":[
		{
			"id":2,
			"priority":5			
		},
		{
			"id":4,
			"priority":4			
		},
		{
			"id":1,
			"priority":3			
		},
		{
			"id":5,
			"priority":2			
		},
		{
			"id":3,
			"priority":1			
		}
	]
}
```
Controller code could look like
```php

    public function store(Request $request)
    {
        $request->validate([
            'custom_sort' => 'required',
            'custom_sort.*.id' => "required|exists:posts,id",
            'custom_sort.*.priority' => 'required|integer'
        ]);
        
        $morphClass = (new Post())->customSort()->getMorphClass();
        collect($request->custom_sort)->transform(function ($item) use($morphClass) {
            CustomSort::create([
                'sortable_id' => $item['id'],
                'sortable_type' => $morphClass,
                'priority' => $item['priority']
            ]);
        });
    }

```
### Frontend Tips
I used [SortabelJS](https://github.com/SortableJS/Sortable) for having the ability to drag and drop to set manual order.
And here is snippet to javascript code.
```javascript
let posts = []

// SortableJS onEnd handler would look like this
onEnd: function ({ oldIndex, newIndex }){
  const movedItem = posts.splice(oldIndex, 1)[0]
  posts.splice(newIndex, 0, movedItem)
}

// prepare payload
preparePayload: function() {
  const order = posts.map((item, key) => {
    return {
      id: item.id,
      priority: posts.length - key
    }
  })
  return { custom_sort: order }
}
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Arneet Singh](https://github.com/arneetsingh)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
