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
        $data = Cache::remember('home-page-data', 10, function() {
            return $this->calculateAShitTonOfData();
        });

        return response()->json($data);
    }

    private function calculateAShitTonOfData()
    {
        $clan = $this->clanRepository->orderBy('id', 'desc')->limit(1)->findAll()->first();

        $players = $this->playerRepository->findAll();

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
            $records = $this->recordRepository->withinRange($player->tag, 7, Carbon::now())->findAll();

            $activeRecords = $records->filter(function ($record, $key) use ($records) {
                return $records->has($key - 1) AND $record->trophies !== $records->get($key - 1)->trophies;
            });

            $activeness = $activeRecords->count();

            if ($activeness > ($mostActivePlayer->activeness ? : 0)) {
                $mostActivePlayer = $player;
                $mostActivePlayer->activeness = $activeness;
            }
        }

        $records = $this->recordRepository->withinRange($mostActivePlayer->tag, 7)->findAll();

//        $records->each(function ($record, $key) use ($records) {
//            if ($records->has($key - 1)) {
//                $record['trophiesVariation'] = (int)$record->trophies - (int)$records->get($key - 1)->trophies;
//            } else {
//                $record['trophiesVariation'] = 0;
//            }
//        });

        $mostActivePlayer->records = $records;
        $mostActivePlayer->load('color');
        return $mostActivePlayer;
    }

    private function getSoManyTrophies(Collection $players)
    {
        $soManyTrophies = $players->first();

        foreach ($players as $player) {
            $low = $this->recordRepository->withinRange($player->tag, 7, Carbon::now())
                ->orderBy('trophies')->findAll()->first();

            $min = $low ? $low->trophies : 0;

            $high = $this->recordRepository->withinRange($player->tag, 7, Carbon::now())
                ->orderBy('trophies', 'desc')->findAll()->first();

            $max = $high ? $high->trophies : 0;

            $trophySpan = $max- $min;

            if ($trophySpan > ($soManyTrophies->trophy_span ? : 0)) {
                $soManyTrophies = $player;
                $soManyTrophies->trophy_span = $trophySpan;
            }
        }

        $soManyTrophies->records = $this->recordRepository->withinRange($soManyTrophies->tag, 7, Carbon::now())->findAll();
        $soManyTrophies->load('color');

        return $soManyTrophies;
    }

    private function getDonor(Collection $players)
    {
        $donor = $players->first();

        foreach ($players as $player) {
            $records = $this->recordRepository->withinRange($player->tag, 7, Carbon::now())->findAll();
            $diff = $this->calculateTrueDiff($records, 'donations');
            if ($diff > ($donor->diff ? : 0)) {
                $donor = $player;
                $donor->diff = $diff;
            }
        }

        $donor->records = $this->recordRepository->withinRange($donor->tag, 7, Carbon::now())->findAll();
        return $donor->load('color');
    }

    private function getDonee(Collection $players)
    {
        $donor = $players->first();

        foreach ($players as $player) {
            $records = $this->recordRepository->withinRange($player->tag, 7, Carbon::now())->findAll();
            $diff = $this->calculateTrueDiff($records, 'donationsReceived');
            if ($diff > ($donor->diff ? : 0)) {
                $donor = $player;
                $donor->diff = $diff;
            }
        }

        $donor->records = $this->recordRepository->withinRange($donor->tag, 7, Carbon::now())->findAll();
        return $donor->load('color');
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