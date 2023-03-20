<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;
    public $table="news";
    public function getDateAttribute($value)
    {
        return date('Y-m-d\TH:s', strtotime($value));
    }
}
