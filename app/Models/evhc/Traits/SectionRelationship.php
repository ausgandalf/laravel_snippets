<?php

namespace App\Models\evhc\Traits;
use App\Models\evhc\Item;
/**
 * Class SectionRelationship
 */
trait SectionRelationship
{
    public function items()
    {
        return $this->hasMany(Item::class, 'section_id','id')->orderBy("sort_order");
    }
}
