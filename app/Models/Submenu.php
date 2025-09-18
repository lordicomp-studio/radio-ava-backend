<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submenu extends Model
{
    use HasFactory;

    protected $fillable = [
        'menu_id',
        'parent_id',
        'title',
        'url',
    ];

    protected $with = [ 'submenus' ];

    public function menu(){
        return $this->belongsTo(Menu::class, 'menu_id', 'id');
    }

    // this gets all the related *child* submenus
    public function submenus(){
        return $this->hasMany(Submenu::class, 'parent_id', 'id');
    }
}
