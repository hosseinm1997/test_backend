<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Document
 * @property File fileRelation
 * @package App\Models
 */
class Document extends Model
{
    use HasFactory;
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
}
