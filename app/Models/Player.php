<?php
/**
 * Created by PhpStorm.
 * User: Veo
 * Date: 6/13/2016
 * Time: 8:02 PM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    protected $primaryKey = 'tag';

    public $incrementing = false;

    protected $fillable = ['tag', 'name', 'role', 'league_slug', 'color_id'];

    protected $appends = ['id'];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new RecentScope());
    }

    public function getIDAttribute()
    {
        return preg_replace('/^#/', '', $this->getAttributeFromArray('tag'));
    }

    /*
    |--------------------------------------------------------------------------
    | Records
    |--------------------------------------------------------------------------
    */
    public function records()
    {
        return $this->hasMany(Record::class, 'tag');
    }

    public function record()
    {
        return $this->hasOne(Record::class, 'tag');
    }

    /*
    |--------------------------------------------------------------------------
    | League
    |--------------------------------------------------------------------------
    */
    public function league()
    {
        return $this->belongsTo(League::class, 'league_slug');
    }

    /*
    |--------------------------------------------------------------------------
    | Color
    |--------------------------------------------------------------------------
    */
    public function color()
    {
        return $this->belongsTo(Color::class);
    }
}