<?php

namespace App\Jobs;

use App\Models\Users;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Redis;

class PayOrderJob implements ShouldQueue
{
    use Queueable, InteractsWithQueue;


    public int $tries = 3;
    protected int $user_id;
    protected float $amount;
    /**
     * Create a new job instance.
     */
    public function __construct($data)
    {
        $this->user_id = $data["user_id"];
        $this->amount = $data["amount"];
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        try {
            $user = Users::find($this->user_id);
            $user->amount += $this->amount;
            $user->save();
        } catch (\Exception $e) {
            // 异常情况插入队列头部重试
            if ($this->attempts() < $this->tries) {

                $payload = $this->job->getRawBody();

                $payloadData = json_decode($payload, true);
                $payloadData['data']['attempts'] = $this->attempts() + 1;
                $payload = json_encode($payloadData);
                Redis::lpush('laravel_database_queues:queue_' . ($this->user_id % 10), $payload);

                $this->job->delete();
            } else {
                $this->fail($e);
            }
        }
    }
}
