<?php
/**
 * Created by PhpStorm.
 * User: Veo
 * Date: 6/14/2016
 * Time: 6:43 PM
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    public $timestamps = false;

    protected $fillable = ['value'];

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */
    public function player()
    {
        return $this->hasOne(Player::class);
    }
}