<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Merchant;
use App\Models\Payer;
use App\Models\Payment;
use Illuminate\Http\Request;
use Bmatovu\MtnMomo\Products\Collection;
use Illuminate\Support\Facades\DB;


class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $payment = Payment::all();

        $payments = DB::table('payments')
                        ->join('payers', 'payments.payer', '=', 'payers.id')
                        ->select('payments.*', 'payers.cellphone')
                        ->orderByDesc('id')
                        ->get();


        return response()->json([
            'status' => "true",
            'data' => $payments
        ], 200);

        // $collection = new Collection();
        // $momoTransactionId = $collection->requestToPay('transactionId', '46733123454', 36.50);

        // $transactionStatus = $collection->getTransactionStatus($momoTransactionId);

        // dd($transactionStatus);

        // $status = $transactionStatus['status'];

        // dd($status);


        
    }

    public function getHistory(){
        $payments = DB::table('payments')
                        ->join('payers', 'payments.payer', '=', 'payers.id')
                        ->join('merchants', 'payments.receiver', '=', 'merchants.id')
                        ->select('payments.*', 'payers.cellphone', 'merchants.name as merchant')
                        ->where('payments.success', '=', true)
                        ->orderByDesc('id')
                        ->get();


        return response()->json([
            'status' => "true",
            'data' => $payments
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'payer' => 'required',
            'amount' => 'required',
            'card' => 'required',
        ]);

        $payer = $request->card;

        $get_amount = Card::find($payer);

        // dd($request->amount);

        if ($get_amount->balance < $request->amount) {

            $payment = Payment::create([
                'payer' => $request->payer,
                'card' => $request->card,
                'amount' => $request->amount,
                'receiver' => $request->receiver,
                'success' => 0
            ]);

            return response()->json([
                'message' => 'Insufficient Funds',
                'data' => $payment
            ], 401);
        } 

        if ($get_amount->balance >= $request->amount) {
            // Update Merchant Balance
            $get_merchant = Merchant::find($request->receiver);

            $merchant_balance = $get_merchant->balance;

            $add_balance = $merchant_balance + $request->amount;

            $get_merchant->balance = $add_balance;

            $get_merchant->save();

            // Update Card Holder Balance
            $get_holder = Card::find($request->card);

            $holder_balance = $get_holder->balance;

            $minus_balance = $holder_balance - $request->amount;

            $get_holder->balance = $minus_balance;

            $get_holder->save();

            $payment = Payment::create([
                'payer' => $request->payer,
                'card' => $request->card,
                'amount' => $request->amount,
                'receiver' => $request->receiver,
                'success' => 1
            ]);

            return response()->json([
                'message' => 'Payment Successful',
                'data' => $payment,
                'card' => $get_holder,
                'merchant' => $get_merchant
            ], 201);
        } 


        $payment = Payment::create($request->all());

        return response()->json([
            'data' => $payment,
            'message' => 'success'
        ], 200);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function show(Payment $payment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Payment $payment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Payment $payment)
    {
        //
    }
}