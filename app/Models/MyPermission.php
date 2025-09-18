<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;

class MyPermission extends Permission
{
    use HasFactory;

    public function children(){
        return $this->hasMany(Permission::class, 'parent_id', 'id');
    }
}
