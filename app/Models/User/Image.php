<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $table = 'dbmaster.view_userphoto';
    protected $primaryKey = 'id';
    public $incrementing = false;

    protected $fillable = ['id', 'foto'];

    protected $appends = ['image'];

    public function getImageAttribute()
    {
        return $this->foto;
    }
}
