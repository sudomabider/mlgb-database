<?php
/**
 * Created by PhpStorm.
 * User: Veo
 * Date: 6/14/2016
 * Time: 12:23 PM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Clan extends Model
{
    protected $fillable = ['tag', 'name', 'type', 'description', 'badge_small', 'badge_medium', 'badge_large', 'clanLevel', 'clanPoints', 'warWinStreak', 'warWins', 'warTies', 'warLosses', 'members'];
}