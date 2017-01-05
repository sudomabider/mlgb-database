<?php
/**
 * Created by PhpStorm.
 * User: veoc
 * Date: 5/01/17
 * Time: 9:22 PM
 */

namespace App\Models;


use Colors\RandomColor;
use Rinvex\Repository\Repositories\EloquentRepository;

class ColorRepository extends EloquentRepository
{
    protected $model = Color::class;

    public function getAvailableColor()
    {
        if ($players = Player::with('color')->get()) {
            $colors = $players->pluck('color')->pluck('id');
        } else {
            $colors = [];
        }

        return Color::whereNotIn('id', $colors)->first() ? : $this->createRandomColor();
    }

    private function createRandomColor()
    {
        list($created, $color) = $this->create(['value' => $this->generateRandomColor()]);

        return $color;
    }

    private function generateRandomColor($count = 1)
    {
        $options = [];

        if ($count === 1) {
            return RandomColor::one($options);
        }

        return RandomColor::many($count, $options);
    }
}