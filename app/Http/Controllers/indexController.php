<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderFormRequest;
use App\Jobs\PayOrderJob;

class indexController
{
    public function index(OrderFormRequest $orderForm)
    {
        $order = $orderForm->validated();
        // 多队列单进程
        dispatch((new PayOrderJob($order)))->onQueue('queue_' . $order["user_id"] % 10);
//        PayOrderJob::dispatch($order)->onQueue('queue_' . ($order["user_id"] % 10));;
    }
}
