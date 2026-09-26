<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Satker extends Model
{
    use HasFactory;

    protected $fillable = ['kode', 'nama', 'ai_provider', 'gemini_api_key', 'deepseek_api_key'];
}
