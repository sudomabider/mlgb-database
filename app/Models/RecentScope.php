<?php
/**
 * Created by PhpStorm.
 * User: veoc
 * Date: 5/01/17
 * Time: 8:25 PM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class RecentScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $builder->orderBy('created_at');
    }
}