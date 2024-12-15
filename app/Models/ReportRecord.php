<?php

namespace App\Models;

use App\Models\Report;
use Illuminate\Database\Eloquent\Model;
use App\Http\Resources\ReportRecordResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReportRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'report_id',
        'body',
        'path',//(local path) (nullable)
        'position',
    ];

     // Override the toArray method
     public function toArray()
     {
         // Use ReportRecordResource to transform the model's array
         return (new ReportRecordResource($this))->resolve();
     }
 
     // Override the toJson method
     public function toJson($options = 0)
     {
         // Use ReportRecordResource to transform the model's JSON representation
         return (new ReportRecordResource($this))->toJson($options);
     }
 

    // RELATIONSHIPS
    public function report()
    {
        return $this->belongsTo(Report::class);
    }
}
