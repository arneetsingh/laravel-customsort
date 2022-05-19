<?php

namespace ArneetSingh\CustomSort\Traits;

use ArneetSingh\CustomSort\Models\CustomSort;

trait CanCustomSort
{
    /**
     * Get custom sort records.
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function customSort()
    {
        return $this->morphMany(CustomSort::class, 'sortable');
    }

    /**
     * Scope to order by custom sort
     * Records will be returned in order of decreasing priority from custom_sorts table,
     * any references not present in custom_sorts, will be added at the end
     * @param  [QueryBuilder] $query
     * @return void
     */
    public function scopeOrderByCustom($query, $columnForRemaining = null, $sortForRemaining = null)
    {
        $columnForRemaining = $columnForRemaining ?: $this->getKeyName();
        $sortForRemaining = $sortForRemaining ?: config('customsort.order_for_remaining');

        $modelTableName = $this->getTable();
        $primaryKey = $this->getKeyName();
        $morphClass = $this->customSort()->getMorphClass();
        $customSortTable = config('customsort.table');

        $connection = config('database.default');
        $driver = config("database.connections.{$connection}.driver");

        if ($driver == 'mysql') {
            $morphClass = str_replace("\\", "\\\\", $morphClass);
        }

        $subQuery = CustomSort::select('priority')
            ->whereRaw("$modelTableName.$primaryKey = $customSortTable.sortable_id AND $customSortTable.sortable_type = '$morphClass' ");

        $query->orderByRaw("({$subQuery->limit(1)->toSql()}) DESC");
    }

    public static function setNewOrder(array $arrayIds)
    {
        // get the morphclass name from Relation::morphMap
        $morphClass = (new self())->customSort()->getMorphClass();
        // delete all entries for this type of model
        CustomSort::where('sortable_type', $morphClass)->delete();
        // insert custom sort records
        collect($arrayIds)->transform(function ($item, $key) use ($arrayIds, $morphClass) {
            CustomSort::create([
                'sortable_id' => $item,
                'sortable_type' => $morphClass,
                'priority' => count($arrayIds) - $key,
            ]);
        });
    }

    public function setOrderPriority($priority)
    {
        // get the morphclass name from Relation::morphMap
        $morphClass = (new self())->customSort()->getMorphClass();

        CustomSort::updateOrCreate([
            'sortable_id' => $this->id,
            'sortable_type' => $morphClass,
        ], [
            'priority' => $priority,
        ]);
    }
}
