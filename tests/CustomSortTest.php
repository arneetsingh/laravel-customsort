<?php

namespace ArneetSingh\CustomSort\Tests;

use ArneetSingh\CustomSort\Models\CustomSort;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

class CustomSortTest extends TestCase
{
    use LazilyRefreshDatabase;

    /** @test */
    public function it_sets_custom_sort_order_on_a_model()
    {
        $posts = Post::factory()->count(5)->create();

        Post::setNewOrder([5, 3, 4]);

        $this->assertEquals(3, CustomSort::count());
    }

    /** @test */
    public function orderByCustomTest()
    {
        $posts = Post::factory()->count(5)->create();

        Post::setNewOrder([5, 3, 4]);

        $this->assertEquals([5, 3, 4, 1, 2], Post::orderByCustom()->pluck('id')->toArray());

        Post::setNewOrder([3, 4, 1, 5, 2]);

        $this->assertEquals([3, 4, 1, 5, 2], Post::orderByCustom()->pluck('id')->toArray());
    }

    /** @test */
    public function setPriorityTest()
    {
        $posts = Post::factory()->count(5)->create();

        Post::setNewOrder([5, 3, 4]);

        $this->assertEquals([5, 3, 4, 1, 2], Post::orderByCustom()->pluck('id')->toArray());

        $post = Post::find(1);

        $post->setOrderPriority(10);

        $this->assertEquals([1, 5, 3, 4, 2], Post::orderByCustom()->pluck('id')->toArray());
    }
}
