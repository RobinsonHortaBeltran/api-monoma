<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    use HasFactory;
    
    protected $table = 'candidates';

    protected $fillable = [
        'name',
        'source',
        'owner',
        'created_by'
    ];

    public function owner_data()
    {
        return $this->belongsTo(User::class, 'owner', 'id');
    }
    public function created_by_data()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
