<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Organization
 *
 * @property Enumeration typeRelation
 * @property Enumeration categoryRelation
 * @property Enumeration statusRelation
 * @property City cityRelation
 * @property File logoFileRelation
 *
 * @package App\Models
 */
class Organization extends Model
{
    use HasFactory;

    protected $table = 'organizations';
    protected $with = [
        'logoFileRelation'
    ];

    public function typeRelation()
    {
        return $this->belongsTo(
            Enumeration::class,
            'type',
            'id'
        );
    }

    public function categoryRelation()
    {
        return $this->belongsTo(
            Enumeration::class,
            'category',
            'id'
        );
    }

    public function statusRelation()
    {
        return $this->belongsTo(
            Enumeration::class,
            'status',
            'id'
        );
    }

    public function cityRelation()
    {
        return $this->belongsTo(
            City::class,
            'city',
            'id'
        );
    }

    public function logoFileRelation()
    {
        return $this->belongsTo(File::class, 'logo_file_id', 'id');
    }
}
