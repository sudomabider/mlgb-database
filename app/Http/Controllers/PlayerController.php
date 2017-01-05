<?php
/**
 * Created by PhpStorm.
 * User: veoc
 * Date: 5/01/17
 * Time: 2:35 PM
 */

namespace App\Http\Controllers;


use App\Models\PlayerRepository;
use App\Models\RecordRepository;

class PlayerController extends Controller
{
    /**
     * @var PlayerRepository
     */
    private $playerRepository;

    /**
     * PlayerController constructor.
     * @param PlayerRepository $playerRepository
     */
    public function __construct(PlayerRepository $playerRepository)
    {
        $this->playerRepository = $playerRepository;
    }

    public function index()
    {
        $activePlayers = $this->playerRepository->with(['league', 'record'])->where('league_slug', '<>', 'unranked')->findAll();

        $activePlayers = $activePlayers->sortByDesc(function($player){
            return $player->record->trophies;
        })->values();

        $inactivePlayers = $this->playerRepository->with(['league', 'record'])->where('league_slug', '=', 'unranked')->findAll();

        return response()->json(compact('activePlayers', 'inactivePlayers'));
    }

    public function show($tag, RecordRepository $recordRepository)
    {
        $tag = '#' . $tag;
        $days = 7;

        if (!$player = $this->playerRepository->with(['league', 'color'])->findBy('tag', $tag)) {
            return response()->json([], 404);
        }

        $records = $recordRepository->withinRange($tag, 7)->findAll();

        $records->each(function($record, $key) use ($records) {
            if ($records->has($key-1)) {
                $record['trophiesVariation'] = (int) $record->trophies - (int) $records->get($key-1)->trophies;
            } else {
                $record['trophiesVariation'] = 0;
            }
        });

        $latest = $records->max('created_at')->format('Y-m-d h:i');

        $lowTrophy = $recordRepository->wherePlayer($tag)->orderBy('trophies')->limit(1)->findAll()->first()->trophies;
        $highTrophy = $recordRepository->wherePlayer($tag)->orderBy('trophies', 'desc')->limit(1)->findAll()->first()->trophies;
        $highDonation = $recordRepository->wherePlayer($tag)->orderBy('donations', 'desc')->limit(1)->findAll()->first()->donations;
        $highDonationsReceived = $recordRepository->wherePlayer($tag)->orderBy('donationsReceived', 'desc')->limit(1)->findAll()->first()->donationsReceived;

        $careerStart = $recordRepository->wherePlayer($tag)->orderBy('created_at')->limit(1)->findAll()->first()->created_at->toDateString();
        $careerEnd = $recordRepository->wherePlayer($tag)->orderBy('created_at', 'desc')->limit(1)->findAll()->first()->created_at->toDateString();

        $player['meta'] = compact('lowTrophy', 'highTrophy','highDonation', 'highDonationsReceived', 'careerStart', 'careerEnd');

        return response()->json(compact('player', 'records', 'latest', 'days'));
    }
}