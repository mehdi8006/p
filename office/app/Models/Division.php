<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = 'division';
    protected $primaryKey = 'division_id';

    protected $fillable = [
        'division_nom',
        'division_responsable',
        'password',
    ];

    public function tasks()
    {
        return $this->hasMany(Task::class, 'division_id', 'division_id');
    }
    
}
