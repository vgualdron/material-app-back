<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    public $table = "permissions";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'guard_name',
        'display_name',
        'offline'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
    ];

    function toArray(){
        return [
            'id' => $this->id,
            'name' => $this->name,
            'guardName' => $this->guard_name,
            'isFunction' => $this->is_function,
            'displayName' => $this->display_name,
            'offline' => $this->offline,
            'general' => $this->general
        ];
    }
}
