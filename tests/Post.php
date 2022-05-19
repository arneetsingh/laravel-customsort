<?php

namespace ArneetSingh\CustomSort\Tests;


use ArneetSingh\CustomSort\Tests\factories\PostFactory;
use ArneetSingh\CustomSort\Traits\CanCustomSort;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $guarded = [];

    use CanCustomSort, HasFactory;

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return PostFactory::new();
    }
}
