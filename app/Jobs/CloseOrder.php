<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

//代表这个类需要被放到队列中执行，而不是触发时立即执行
class CloseOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    
    public function __construct(Order $order,$delay)
    {
        dump($order);die;
        $this->order = $order;
        //设置延迟时间，delay()方法的参数代表多少秒之后执行
        $this->delay($delay);
        Log::error("开始延迟");
    }

    /**
     * Execute the job.
     *定义这个任务类具体的执行逻辑
     * 当队列处理器从队列中取出任务时，会调用handle（）方法
     * @return void
     */
    // 
    public function handle()
    {
        Log::error('小的已经在处理了');
        //判断对应的订单是否已经被支付
        //如果已经支付则不需要关闭订单，直接退出
        if($this->order->paid_at){
            Log::error('小的已经在处理了2');
            return;
        }
        // 通过事务执行sql  关闭订单
        \DB::transaction(function(){
            Log::error('小的已经在处理了3');
            //将订单的closed字段标记为true,即关闭订单
            $this->order->update(['closed' => true]);
            //循环遍历订单中的商品SKU，将订单中的数量加回到SKU的库存中去
            foreach($this->order->items as $item){
                Log::error('小的已经在处理了4');
                $this->productSku->addStock($item->stock);
            }
        });
    }}