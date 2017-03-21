<?php namespace App\Handlers\Events;

use App\Events\OrderHistoryWasUpdated;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldBeQueued;
use Mail;

class EmailHistoryUpdateConfirmation {

	/**
	 * Create the event handler.
	 *
	 * @return void
	 */
	public function __construct()
	{
		//
	}

	/**
	 * Handle the event.
	 *
	 * @param  OrderHistoryWasUpdated  $event
	 * @return void
	 */
	public function handle(OrderHistoryWasUpdated $event)
	{
		$order_info = array();
		$order = $event->order;
		$order_id = $event->order->order_id;
                $order_info['address']= $event->order->getAddressForOrder($order_id);
		$order_info['customer']= $event->order->getCustomerInfoByOrderId($order_id);
		$order_info['order_info']= $event->order->getOrderDetailsForId($order_id);
		if(!empty($order_info['order_info']['order'])) {
			$order_info['order_info']['order']->order_history_comment = $event->order->statComment;
			$order_info['order_info']['order']->order_history_status = $event->order->getOrderStatusById($event->order->history_status);
                        Mail::send('emails.order_history', array('order_info' => $order_info), function($message) use ($order_info) {
                            $message->to($order_info['customer']->email, $order_info['customer']->name);
                            if(env('APP_ENV') != 'local') {
                                //As per the Sudhir email
                                $message->cc('order@zapdel.com');
                                //$message->cc('kanojia.a@boibanit.com');
                                $message->bcc('prakash.s@boibanit.com', 'Sudhir Prakash');
                                $message->bcc('orderzapdel@gmail.com', 'Zapdel');
                            }
                            $message->subject('Order Status Updated');
                        });
                }
         }

}
