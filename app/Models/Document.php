<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Document
 * @property File fileRelation
 * @property Enumeration typeRelation
 * @property Enumeration statusRelation
 * @package App\Models
 */
class Document extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'documents';

    protected $fillable = [
        'file_id',
        'type',
        'status',
        'organization_id',
        'user_id',
    ];

    public function fileRelation()
    {
        return $this->belongsTo(File::class, 'file_id', 'id');
    }

    public function typeRelation()
    {
        return $this->belongsTo(Enumeration::class, 'type', 'id');
    }

    public function statusRelation()
    {
        return $this->belongsTo(Enumeration::class, 'status', 'id');
    }
}
