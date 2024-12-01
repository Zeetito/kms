<?php

namespace App\Models;

use App\Models\Report;
use Illuminate\Database\Eloquent\Model;
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

    // RELATIONSHIPS
    public function report()
    {
        return $this->belongsTo(Report::class);
    }
}
