<?php


namespace App\Console\Commands;


use App\EloquentQueries\Api\Interfaces\MeetingQueries;

use App\Http\Services\interfaces\IMeetingService;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\QueryException;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\Log;
use Monolog\Handler\StreamHandler;
use Psr\Log\LoggerInterface;

class DestroyExpiredMeeting extends Command
{


    protected $signature = 'meeting:destroy-expired';
    protected $description = 'DestroyExpiredMeeting';


    public function __construct()
    {
        parent::__construct();
    }


    public function handle(IMeetingService $meetingService, MeetingQueries $meetingQueries)
    {

        Log::info('cron ' . __CLASS__ . ' started');

        try {

            $meetings = $meetingQueries->getExpired();

            Log::info($meetings->count() . ' expired meetings found');

            if ($meetings->isNotEmpty()) {

                foreach ($meetings as $meeting) {

                    try {

                        $meetingService->destroy($meeting->id, null, true);

                    } catch (QueryException $queryException) {

                        Log::alert('meeting ' . $meeting->id . ' destroy failed', [$queryException]);
                    }

                }

            }


        } catch (\Exception $exception) {

            Log::alert('cron ' . __CLASS__ . ' error', [$exception]);
        }

        Log::info('cron ' . __CLASS__ . ' completed');
    }
}
















