<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transactions;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Constants;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PaymeController extends Controller
{
    public function transaction(Request $request)
    {
        switch ($request->method) {
            case 'CheckPerformTransaction':
                return $this->checkPerformTransaction($request);
            break;
            case 'CreateTransaction':
                return $this->createTransaction($request);
            break;
            case 'CheckTransaction':
                return $this->checkTransaction($request);
            break;
            case 'PerformTransaction':
                return $this->performTransaction($request);
            break;
            case 'CancelTransaction':
                return $this->cancelTransaction($request);
            break;
            case 'GetStatement':
                return $this->getStatement($request);
            break;
            case 'ChangePassword':
                return $this->changePassword($request);
            break;
        }
    }


    private function checkPerformTransaction($request)
    {
        if (empty($request->params['account'])) {
            $response = [
                'id' => $request->id,
                'error' => [
                    'code' => -32504,
                    'message' => 'Недостаточно привилегий для выполнения метода.'
                ]
            ];
            return json_encode($response);
        }
        else{
            $account = $request->params['account'];
            $order = Order::where(['id' => $account['order_id'], 'status' => Constants::ORDERED])->first();
            if (empty($order)) {
                $response = [
                    'id' => $request->id,
                    'error' => [
                        'code' => -31050,
                        'message' => [
                            'uz' => 'Buyurtma topilmadi',
                            'ru' => 'Заказ не найден',
                            'en' => 'Order not found',
                        ]
                    ]
                ];
                return json_encode($response);
            }
            else if(($order->all_price * 100) != $request->params['amount']){
                $response = [
                    'id' => $request->id,
                    'error' => [
                        'code' => -31001,
                        'message' => [
                            'uz' => 'Notogri summa',
                            'ru' => 'Неверная сумма',
                            'en' => 'Incorrect amount',
                        ]
                    ]
                ];
                return json_encode($response);
            }
        }

        $order_details = OrderDetail::where('order_id', $order_id)->get();
        $arr = [];

        if (count($order_details) > 0) {
            foreach ($order_details as $value) {
                $arr[] = [
                    'discount' => $value->discount_price,
                    'title' => $value->product->name,
                    'price' => $value->price,
                    'count' => $value->quantity,
                    'code' => (string)Constants::MEN_CODE,
                    'vat_percent' => (int)Constants::TAX_PERCENT,
                    'package_code' => Constants::MEN_PACKAGE_CODE
                ];
            }
        }

        $response = [
            'result' => [
                'allow' => true,
                'detail' => [
                    'receipt_type' => 0,
                    'items' => $arr
                ]
            ]
        ];
        return json_encode($response);
    }

    private function createTransaction($request)
    {
        if (empty($request->params['account'])) {
            $response = [
                'id' => $request->id,
                'error' => [
                    'code' => -32504,
                    'message' => 'Недостаточно привилегий для выполнения метода.'
                ]
            ];
            return json_encode($response);
        }
        else{
            $account = $request->params['account'];
            $order = Order::where(['id' => $account['order_id'], 'status' => Constants::ORDERED])->first();

            $transaction = Transactions::where(['order_id' => $account['order_id'], 'state' => 1])->get();
            if (empty($order)) {
                $response = [
                    'id' => $request->id,
                    'error' => [
                        'code' => -31050,
                        'message' => [
                            'uz' => 'Buyurtma topilmadi',
                            'ru' => 'Заказ не найден',
                            'en' => 'Order not found',
                        ]
                    ]
                ];
                return json_encode($response);
            }
            else if(($order->all_price * 100) != $request->params['amount']){
                $response = [
                    'id' => $request->id,
                    'error' => [
                        'code' => -31001,
                        'message' => [
                            'uz' => 'Notogri summa',
                            'ru' => 'Неверная сумма',
                            'en' => 'Incorrect amount',
                        ]
                    ]
                ];
                return json_encode($response);
            }
            else if(count($transaction) == 0){
                $currentMills = intval(microtime(true) * 1000);
                $transaction = new Transactions();
                $transaction->paycom_transaction_id = $request->params['id'];
                $transaction->paycom_time = $request->params['time'];
                $transaction->paycom_time_datetime = now();
                $transaction->create_time = now();
                $transaction->create_time_unix = $currentMills;
                $transaction->amount = $request->params['amount'];
                $transaction->state = 1;
                $transaction->order_id = $account['order_id'];
                $transaction->save();

                $response = [
                    'result' => [
                        'create_time' => $request->params['time'],
                        'transaction' => strval($transaction->id),
                        'state' => $transaction->state
                    ]
                ];
                return json_encode($response);
            }
            else if((count($transaction) == 1) && ($transaction->first()->paycom_time == $request->params['time']) && ($transaction->first()->paycom_transaction_id == $request->params['id'])){
                $response = [
                    'result' => [
                        'create_time' => $request->params['time'],
                        'transaction' => "{$transaction[0]->id}",
                        'state' => intval($transaction[0]->state)
                    ]
                ];
                return json_encode($response);
            }
            else{
                $response = [
                    'id' => $request->id,
                    'error' => [
                        'code' => -31099,
                        'message' => [
                            'uz' => 'Buyurtma tolovi hozirda amalga oshirilmoqda',
                            'ru' => 'Оплата заказа сейчас обрабатывается',
                            'en' => 'Payment for the order is now being processed',
                        ]
                    ]
                ];
                return json_encode($response);
            }
        }
    }

    private function checkTransaction($request)
    {
        $transaction = Transactions::where('paycom_transaction_id', $request->params['id'])->first();
        $response = [];
        if (empty($transaction)) {
            $response = [
                'id' => $request->id,
                'error' => [
                    'code' => -31003,
                    'message' => 'Транзакция не найдена'
                ]
            ];
            return json_encode($response);
        }
        else if($transaction->state == 1){
            $response = [
                'result' => [
                    'create_time' => intval($transaction->paycom_time),
                    'perform_time' => intval($transaction->perform_time_unix),
                    'cancel_time' => 0,
                    'transaction' => strval($transaction->id),
                    'state' => $transaction->state,
                    'reason' => $transaction->reason
                ]
            ];
        }
        else if($transaction->state == 2){
            $response = [
                'result' => [
                    'create_time' => intval($transaction->paycom_time),
                    'perform_time' => intval($transaction->perform_time_unix),
                    'cancel_time' => 0,
                    'transaction' => strval($transaction->id),
                    'state' => $transaction->state,
                    'reason' => $transaction->reason
                ]
            ];
        }
        else if($transaction->state == -1 || $transaction->state == -2){
            $response = [
                'result' => [
                    'create_time' => intval($transaction->paycom_time),
                    'perform_time' => intval($transaction->perform_time_unix),
                    'cancel_time' => intval($transaction->cancel_time_unix),
                    'transaction' => strval($transaction->id),
                    'state' => $transaction->state,
                    'reason' => $transaction->reason
                ]
            ];
        }
        return json_encode($response);
    }

    private function performTransaction($request)
    {
        $transaction = Transactions::where('paycom_transaction_id', $request->params['id'])->first();
        if (empty($transaction)) {
            $response = [
                'id' => $request->id,
                'error' => [
                    'code' => -31003,
                    'message' => 'Транзакция не найдена'
                ]
            ];
            return json_encode($response);
        }
        else if($transaction->state == 1){
            $currentMills = intval(microtime(true) * 1000);
            $transaction->state = 2;
            $transaction->perform_time = date('Y-m-d H:i:s');
            $transaction->perform_time_unix = $currentMills;
            $transaction->update();

            // order statusi o'zgarishi kerak
            // $order = Order::where('id', $transaction->order_id)->first();
            // $order->status = 0
            // $order->update();

            $response = [
                'result' => [
                    'transaction' => strval($transaction->id),
                    'perform_time' => intval($transaction->perform_time_unix),
                    'state' => intval($transaction->state),
                ]
            ];
            return json_encode($response);
        }
        else if($transaction->state == 2){
            $response = [
                'result' => [
                    'transaction' => strval($transaction->id),
                    'perform_time' => intval($transaction->perform_time_unix),
                    'state' => intval($transaction->state),
                ]
            ];
            return json_encode($response);
        }
    }

    private function cancelTransaction($request)
    {
        $transaction = Transactions::where('paycom_transaction_id', $request->params['id'])->first();
        $response = [];
        $currentMills = intval(microtime(true) * 1000);
        if (empty($transaction)) {
            $response = [
                'id' => $request->id,
                'error' => [
                    'code' => -31003,
                    'message' => 'Транзакция не найдена'
                ]
            ];
        }
        else if($transaction->state == 1){
            $transaction->reason = $request->params['reason'];
            $transaction->cancel_time = date('Y-m-d H:i:s');
            $transaction->cancel_time_unix = $currentMills;
            $transaction->state = -1;
            $transaction->update();

            // order statusi o'zgarishi kerak
            $order = Order::where('id', $transaction->order_id)->first();
            $order->status = Constants::CANCELLED;
            $order->update();

            $response = [
                'result' => [
                    'state' => intval($transaction->state),
                    'cancel_time' => intval($transaction->cancel_time_unix),
                    'transaction' => strval($transaction->id),
                ]
            ];
        }
        else if($transaction->state == 2){
            $transaction->reason = $request->params['reason'];
            $transaction->cancel_time = date('Y-m-d H:i:s');
            $transaction->cancel_time_unix = $currentMills;
            $transaction->state = -2;
            $transaction->update();

            // order statusi o'zgarishi kerak
            $order = Order::where('id', $transaction->order_id)->first();
            $order->status = Constants::CANCELLED;
            $order->update();

            $response = [
                'result' => [
                    'state' => intval($transaction->state),
                    'cancel_time' => intval($transaction->cancel_time_unix),
                    'transaction' => strval($transaction->id),
                ]
            ];
        }
        else if ($transaction->state == -1 || $transaction->state == -2) {
            $response = [
                'result' => [
                    'state' => intval($transaction->state),
                    'cancel_time' => intval($transaction->cancel_time_unix),
                    'transaction' => strval($transaction->id),
                ]
            ];
        }

        return json_encode($response);
    }

    private function getStatement($request)
    {
        $from = $request->params['from'];
        $to = $request->params['to'];

        $transactions = Transactions::whereBetween('paycom_time',[$from, $to])->get();
        $arr = [];
        if (count($transactions) > 0) {
            foreach ($transactions as $key => $value) {
                $arr[] = [
                    'id' => $value->paycom_transaction_id,
                    'time' => $value->paycom_time,
                    'amount' => $value->amount,
                    'account' => [
                        'order_id' => $value->order_id
                    ],
                    'create_time' => intval($value->create_time_unix),
                    'perform_time' => intval($value->perform_time_unix),
                    'cancel_time' => intval($value->cancel_time_unix) ?? 0,
                    'transaction' => $value->id,
                    'state' => $value->state,
                    'reason' => $value->reason
                ];
            }
        }
        $response = [
            'result' => [
                'transactions' => $arr
            ]
        ];
        return json_encode($response);
    }

    private function changePassword($request)
    {
        if (empty($request->params['account'])) {
            $response = [
                'id' => $request->id,
                'error' => [
                    'code' => -32504,
                    'message' => 'Недостаточно привилегий для выполнения метода.'
                ]
            ];
            return json_encode($response);
        }
    }

}
