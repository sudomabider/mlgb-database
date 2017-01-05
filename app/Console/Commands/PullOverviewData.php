<?php
/**
 * Created by PhpStorm.
 * User: Veo
 * Date: 6/14/2016
 * Time: 4:36 PM
 */

namespace App\Console\Commands;


use App\Models\ClanRepository;
use App\Models\LeagueRepository;
use App\Providers\ClashOfClans\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PullOverviewData extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'coc:overview';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pull CoC overview info';
    /**
     * @var Client
     */
    private $client;


    /**
     * PullOverviewData constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        parent::__construct();

        $this->client = $client;
    }

    /**
     * Do the Thing
     */
    public function fire()
    {
        $clanData = $this->client->getClan();
        $memberList = $clanData->pull('memberList');
        $leagueList = $this->client->getLeagues() ;

        try {
            DB::transaction(function() use ($clanData, $memberList, $leagueList) {
                $this->insertClanInfo($clanData);
                $this->updateLeaguesInfo($leagueList);
            });

            $this->info('clan and leagues info updated successfully');
        } catch (\Exception $e) {
            throw $e;
            $this->error('something went wrong');
        }
    }

    private function insertClanInfo(Collection $data)
    {
        app(ClanRepository::class)->create($data->toArray());
    }


    private function updateLeaguesInfo($leagueList)
    {
        $leagueRepository = app(LeagueRepository::class);

        //Existing leagues
        $leagueSlugs = $leagueRepository->findAll()->pluck('slug')->flatten()->toArray();

        foreach ($leagueList as $league) {

            $league['icon_tiny'] = $league['iconUrls']['tiny'] ?? null;
            $league['icon_small'] = $league['iconUrls']['small'] ?? null;
            $league['icon_medium'] = $league['iconUrls']['medium'] ?? null;
            $league['slug'] = kebabize($league['name']);

            $slug = kebabize($league->get('name'));
            if (in_array($slug, $leagueSlugs)) {
                $leagueRepository->update($slug, $league->toArray());
            } else {
                $leagueRepository->create($league->toArray());
            }
        }
    }
}