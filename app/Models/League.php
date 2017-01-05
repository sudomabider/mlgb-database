<?php
/**
 * Created by PhpStorm.
 * User: Veo
 * Date: 6/14/2016
 * Time: 11:25 AM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class League extends Model
{
    public $incrementing = false;

    protected $primaryKey = 'slug';

    protected $fillable = ['slug', 'name', 'icon_tiny', 'icon_small', 'icon_medium'];
}