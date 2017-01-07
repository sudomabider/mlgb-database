<?php
/**
 * Created by PhpStorm.
 * User: Veo
 * Date: 6/13/2016
 * Time: 9:12 PM
 */

namespace App\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    protected $table = 'player_records';
    
    protected $fillable = ['tag', 'expLevel', 'league_slug', 'trophies', 'clanRank', 'donations', 'donationsReceived'];

    protected $appends = ['time'];

    public function getTimeAttribute()
    {
        return $this->created_at->format('Y-m-d h:i');
    }

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */
    public function league()
    {
        return $this->belongsTo(League::class, 'league_slug');
    }
}