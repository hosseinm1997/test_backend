<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class City
 *
 * @property Province provinceRelation
 *
 * @package App\Models
 */
class City extends Model
{
    use HasFactory;

    protected $table = 'cities';
    protected $hidden = ['province_id', 'created_at'];

    public function provinceRelation()
    {
        return $this->belongsTo(Province::class, 'province_id', 'id');
    }
}
