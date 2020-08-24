<?php


namespace App\Jobs;


use App\Exceptions\HelperMethodException;
use App\Helpers\FileHelper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class DestroyFiles implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Consist full path to files
     * format https://razam-dev.bedj.pro/files/images/meeting_photos/5e831bfa577687.66728693.jpeg
     * @var  array<string>
     */
    protected $files;

    public function __construct($files)
    {
        $this->files = $files;
    }


    public function handle()
    {

        Log::info('job ' . __CLASS__ . ' started');

        if (false === FileHelper::deleteMany($this->files)) {
            Log::info('job ' . __CLASS__ . ' error, some files cant be deleted');
        }
        Log::info('job ' . __CLASS__ . ' end');
    }
}






















