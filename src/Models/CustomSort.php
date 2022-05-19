<?php

namespace ArneetSingh\CustomSort\Models;

use Illuminate\Database\Eloquent\Model;

class CustomSort extends Model
{
    protected $guarded = [];

    /**
     * Get all of the owning sortable models.
     */
    public function sortable()
    {
        return $this->morphTo();
    }

    public function getTable()
    {
        return config('customsort.table');
    }
}
