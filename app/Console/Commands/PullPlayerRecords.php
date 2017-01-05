<?php
/**
 * Created by PhpStorm.
 * User: Veo
 * Date: 6/13/2016
 * Time: 5:55 PM
 */

namespace App\Console\Commands;

use App\Models\ColorRepository;
use App\Models\PlayerRepository;
use App\Models\RecordRepository;
use App\Providers\ClashOfClans\Client;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Facades\DB;

class PullPlayerRecords extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'coc:records';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pull CoC player records';
    /**
     * @var Client
     */
    private $client;

    /**
     * New entries from the command.
     *
     * @var array
     */
    private $status = [];

    /**
     * PullOverviewData constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        parent::__construct();

        $this->client = $client;

        $this->status = [
            'members' => null,
            'records' => null,
        ];
    }

    /**
     * Do the Thing
     */
    public function fire()
    {
        $memberList = $this->client->getMembers();

        try {
            DB::transaction(function() use ($memberList) {
                $this->status['members'] = $this->updateMembers($memberList);
                $this->addPlayerRecords($memberList);
                $this->status['records'] = $memberList->count();
            });

            $this->info('Successful as of ' . Carbon::now()->toRfc822String());
            $this->info($this->status['records'] . ' records added.');
            $this->info($this->status['members'] . ' new members created.');
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    private function addPlayerRecords($records)
    {
        $recordRepository = app(RecordRepository::class);

        foreach ($records as $key => $record) {
            $records[$key] = collect($this->processRecordData($record));
            $recordRepository->create($record->toArray());
        }
    }

    private function updateMembers($memberList)
    {
        $newCount = 0;

        $playerRepository = app(PlayerRepository::class);

        foreach ($memberList as $member) {
            $member['league_slug'] = $member['league']['name'] ?? 'unranked';
            if ($player = $playerRepository->find($member->get('tag'))) {
                $playerRepository->update($player, $member->toArray());
            } else {
                $playerRepository->create($member->toArray() + ['color_id' => app(ColorRepository::class)->getAvailableColor()->id]);
                $newCount++;
            }
        }

        return $newCount;
    }

    private function processRecordData($data)
    {
        if (isset($data['league']) AND (is_array($data['league']) OR $data['league'] instanceof Arrayable)) {
            $data['league_slug'] = kebabize($data['league']['name']);
        }

        if (isset($data['badgeUrls']) AND (is_array($data['badgeUrls']) OR $data['badgeUrls'] instanceof Arrayable)) {
            $data['badge_small'] = isset($data['badgeUrls']['small']) ? $data['badgeUrls']['small'] : null;
            $data['badge_medium'] = isset($data['badgeUrls']['medium']) ? $data['badgeUrls']['medium'] : null;
            $data['badge_large'] = isset($data['badgeUrls']['large']) ? $data['badgeUrls']['large'] : null;
        }

        if (isset($data['iconUrls']) AND (is_array($data['iconUrls']) OR $data['iconUrls'] instanceof Arrayable)) {
            $data['icon_tiny'] = isset($data['iconUrls']['tiny']) ? $data['iconUrls']['tiny'] : null;
            $data['icon_small'] = isset($data['iconUrls']['small']) ? $data['iconUrls']['small'] : null;
            $data['icon_medium'] = isset($data['iconUrls']['medium']) ? $data['iconUrls']['medium'] : null;
        }

        return $data;
    }
}