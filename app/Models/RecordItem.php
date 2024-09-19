<?php

namespace App\Models;

use App\Models\Record;
use Illuminate\Database\Eloquent\Model;
use App\Http\Resources\RecordItemResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RecordItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'record_id',
        'user_id',
        'unit_cost',
        'quantity',
        'info',
        'value',
    ];

    // Override the toArray method
    public function toArray()
    {
        // Use RecordItemResource to transform the model's array
        return (new RecordItemResource($this))->resolve();
    }

    // Override the toJson method
    public function toJson($options = 0)
    {
        // Use RecordItemResource to transform the model's JSON representation
        return (new RecordItemResource($this))->toJson($options);
    }

    // RELATIONSHIPS

    public function record()
    {
        return $this->belongsTo(Record::class);
    }
}
