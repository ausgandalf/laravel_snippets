<?php

namespace App\Models\evhc\Traits;

use App\Models\evhc\Category;
use App\Models\evhc\Section;
use App\Models\evhc\ItemNote;
use App\Models\evhc\ItemMeterSetting;
/**
 * Class ItemRelationship
 */
trait ItemRelationship
{
    public function category()
    {
        return $this->hasOne(Category::class, 'id','category_id');
    }
    public function section()
    {
        return $this->hasOne(Section::class, 'id','section_id');
    }
    public function notes()
    {
        return $this->hasMany(ItemNote::class, 'item_id','id')->orderBy("sort_order");
    }
    public function meter_setting()
    {
        return $this->hasOne(ItemMeterSetting::class, 'item_id','id');
    }
}
