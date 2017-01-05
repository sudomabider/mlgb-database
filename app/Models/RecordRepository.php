<?php
/**
 * Created by PhpStorm.
 * User: veoc
 * Date: 5/01/17
 * Time: 2:39 PM
 */

namespace App\Models;


use Carbon\Carbon;
use Rinvex\Repository\Repositories\EloquentRepository;

class RecordRepository extends EloquentRepository
{
    protected $model = Record::class;

    public function withinRange($player, $days = 30, $until = null)
    {
        $player = $player instanceof Player ? $player->tag : $player;

        if (!$until) {
            $record = $this->orderBy('created_at', 'desc')->where('tag', '=', $player)->limit(1)->findAll()->first();
            $until = $record ? $record->created_at : Carbon::now();
        } else {
            $until = ($until instanceof Carbon) ? $until : Carbon::parse($until);
        }

        return $this->where('tag', '=', $player)
            ->where('created_at', '>', $this->parseTime($until)->subDays($days)->format($this->getDateFormat()))
            ->where('created_at', '<',  $this->parseTime($until)->format($this->getDateFormat()));

    }

    private function parseTime($time)
    {
        return ($time instanceof Carbon) ? clone $time : Carbon::parse($time);
    }


    public function wherePlayer($tag)
    {
        return $this->where('tag', $tag);
    }

    protected function getDateFormat()
    {
        return 'Y-m-d h:i:s';
    }
}