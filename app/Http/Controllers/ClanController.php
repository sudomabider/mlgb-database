<?php
/**
 * Created by PhpStorm.
 * User: veoc
 * Date: 6/01/17
 * Time: 2:44 PM
 */

namespace App\Http\Controllers;


use App\Models\ClanRepository;
use App\Models\PlayerRepository;
use App\Models\RecordRepository;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class ClanController extends Controller
{
    /**
     * @var ClanRepository
     */
    private $clanRepository;
    /**
     * @var PlayerRepository
     */
    private $playerRepository;
    /**
     * @var RecordRepository
     */
    private $recordRepository;

    /**
     * ClanController constructor.
     * @param ClanRepository $clanRepository
     * @param PlayerRepository $playerRepository
     * @param RecordRepository $recordRepository
     */
    public function __construct(ClanRepository $clanRepository, PlayerRepository $playerRepository, RecordRepository $recordRepository)
    {
        $this->clanRepository = $clanRepository;
        $this->playerRepository = $playerRepository;
        $this->recordRepository = $recordRepository;
    }

    public function index()
    {
        $timerStart = microtime(true);

        $cached = false;

        if (Cache::has('home-page-data')) {
            $data = Cache::get('home-page-data');
            $cached = true;
        } else {
            $data = $this->calculateAShitTonOfData();
            Cache::put('home-page-data', $data, 10);
        }

        $timerEnd = microtime(true);
        $time = number_format($timerEnd - $timerStart, 6);

        return response()->json($data + compact('time', 'cached'));
    }

    private function calculateAShitTonOfData()
    {
        $clan = $this->clanRepository->orderBy('id', 'desc')->limit(1)->findAll()->first();

        $players = $this->playerRepository->with(['color'])->findAll();

        $players->each(function($player) {
            $player->records = $this->recordRepository->withinRange($player->tag, 7, Carbon::now())->findAll();
        });

        $mostActivePlayer = $this->getMostActivePlayer($players);

        $soManyTrophies = $this->getSoManyTrophies($players);

        $donor = $this->getDonor($players);

        $donee = $this->getDonee($players);

        return compact('clan', 'mostActivePlayer', 'soManyTrophies', 'donor', 'donee');
    }

    private function getMostActivePlayer(Collection $players)
    {
        $mostActivePlayer = $players->first();

        foreach ($players as $player) {
            $records = $player->records;

            $activeRecords = $records->filter(function ($record, $key) use ($records) {
                return $records->has($key - 1) AND $record->trophies !== $records->get($key - 1)->trophies;
            });

            $activeness = $activeRecords->count();

            if ($activeness > ($mostActivePlayer->activeness ? : 0)) {
                $mostActivePlayer = $player;
                $mostActivePlayer->activeness = $activeness;
            }
        }

//        $records->each(function ($record, $key) use ($records) {
//            if ($records->has($key - 1)) {
//                $record['trophiesVariation'] = (int)$record->trophies - (int)$records->get($key - 1)->trophies;
//            } else {
//                $record['trophiesVariation'] = 0;
//            }
//        });

        return $mostActivePlayer;
    }

    private function getSoManyTrophies(Collection $players)
    {
        $soManyTrophies = $players->first();

        foreach ($players as $player) {
            $low = $player->records->sortBy('trophies')->first();

            $min = $low ? $low->trophies : 0;

            $high = $player->records->sortByDesc('trophies')->first();

            $max = $high ? $high->trophies : 0;

            $trophySpan = $max- $min;

            if ($trophySpan > ($soManyTrophies->trophy_span ? : 0)) {
                $soManyTrophies = $player;
                $soManyTrophies->trophy_span = $trophySpan;
            }
        }

        return $soManyTrophies;
    }

    private function getDonor(Collection $players)
    {
        $donor = $players->first();

        foreach ($players as $player) {
            $records = $player->records;
            $diff = $this->calculateTrueDiff($records, 'donations');
            if ($diff > ($donor->diff ? : 0)) {
                $donor = $player;
                $donor->diff = $diff;
            }
        }

        return $donor;
    }

    private function getDonee(Collection $players)
    {
        $donor = $players->first();

        foreach ($players as $player) {
            $records = $player->records;
            $diff = $this->calculateTrueDiff($records, 'donationsReceived');
            if ($diff > ($donor->diff ? : 0)) {
                $donor = $player;
                $donor->diff = $diff;
            }
        }

        return $donor;
    }

    private function calculateTrueDiff(Collection $records, $key)
    {
        $max = $records->max($key);
        $min = $records->min($key);

        $first = $records->first();
        $first = $first ? $first->$key : 0;

        $last = $records->last();
        $last = $last ? $last->$key : 0;

        // if values go down it means a new season has started
        if ($min < $first) {
            $diff = $max - $first + $last;
        } else {
            $diff = $max - $min;
        }

        return $diff;
    }

}