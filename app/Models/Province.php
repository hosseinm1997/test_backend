<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Infrastructure\Traits\Sortable\SortableTrait;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Infrastructure\Traits\Searchable\SearchableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Province extends Model
{
    use HasFactory, SearchableTrait, SortableTrait;

    protected $table = 'provinces';

    protected $hidden = ['created_at'];

    public $searchable = ['id', 'title'];

    public $sortable = ['id'];

    public function cities(): HasMany
    {
        return $this->hasMany(City::class, 'province_id');
    }
}
