<?php
/**
 * Created by PhpStorm.
 * User: veoc
 * Date: 5/01/17
 * Time: 8:54 PM
 */

namespace App\Models;


use Rinvex\Repository\Repositories\EloquentRepository;

class ClanRepository extends EloquentRepository
{
    protected $model = Clan::class;
}