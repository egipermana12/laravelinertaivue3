<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SidebarsModel extends Model
{
    protected $table = 'sidebars';
    protected $fillable = [
        'title', 'url', 'icon', 'isActive', 'parentId', 'sortOrder'
    ];

    /**
     * child hanya boleh punya 1 parent
     */
    public function parent(): HasOne
    {
        return $this->hasOne(SidebarsModel::class, 'id', 'parentId');
    }

    /***
     * parent boleh punya banyak child
     * */
    public function items() :HasMany
    {
        return $this->hasMany(SidebarsModel::class, 'parentId', 'id')->orderBy('sortOrder');
    }

    /***
     * generate array parent child
     * */
    public function generateSidebar()
    {
        // 1. Tentukan kedalaman rekursif yang cukup (misalnya, 5-10 level biasanya cukup)
        $max_depth = 10;
        $nestedRelations = implode('.', array_fill(0, $max_depth, 'items'));

        return static::with($nestedRelations)
            // Memilih hanya item menu yang merupakan root (parent_id = 0)
            ->where('parentId', 0)
            ->orderBy('sortOrder')
            ->get();
    }
}
