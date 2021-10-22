<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Enumeration
 *
 * @package App\Models
 */
class Enumeration extends Model
{
    use HasFactory;

    protected $table = 'enumerations';
    protected $visible = ['id', 'title', 'order', 'meta_data'];
    protected $casts = [
        'meta_data' => 'array'
    ];

    public function documentTypeRelation()
    {
        return $this->hasMany(Document::class, 'type', 'id');
    }
}
