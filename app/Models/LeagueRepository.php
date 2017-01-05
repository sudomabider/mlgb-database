<?php
/**
 * Created by PhpStorm.
 * User: veoc
 * Date: 5/01/17
 * Time: 2:40 PM
 */

namespace App\Models;


use Rinvex\Repository\Repositories\EloquentRepository;

class LeagueRepository extends EloquentRepository
{
    protected $model = League::class;
}