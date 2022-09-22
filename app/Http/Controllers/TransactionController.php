<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Bmatovu\MtnMomo\Products\Collection;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function makeCollection(Request $request){

        $request->validate([
            'payer' => 'required',
            'amount' => 'required'
        ]);

        $collection = new Collection();
        $msisdn = '46733123453';

        $timestamp = now()->timestamp;
        $transactionId =  $timestamp.'TR00'.$request->payer.'A22';
        $momoTransactionId = $collection->requestToPay($transactionId, $msisdn , $request->amount);

        $status = $collection->getTransactionStatus($momoTransactionId);
        $getStatus = $status['status'];

        $newTrans = Transaction::create([
            'payer' => $request->payer,
            'amount' => $request->amount,
            'transactionId' => $momoTransactionId,
            'transactionRef' => $transactionId,
            'status' => $getStatus,
            'msisdn' => $msisdn,
            'comments' => 'initialized'
        ]);

        if ($newTrans) {
            $getCardBalance = Card::where('holder', $request->payer)->first();

            $balance = $getCardBalance->balance;

            $topUp = $balance + $request->amount;

            $getCardBalance->balance = $topUp;

            $getCardBalance->save();



            return response()->json([
                'message' => 'true',
                'info' => $newTrans,
                'status'=> $status,
                'card' => $getCardBalance
            ], 201);
        } else {
            return response()->json([
                'error' => 'Error Occuerd'
            ], 405);
        }



    }

    public function getStatus($id_){
        $id = $id_;

        $collection = new Collection();
        $status = $collection->getTransactionStatus($id);

        return response()->json([
            'status' => $status
        ],201);
    }

    public function getInfo(){
        $partyId = "46733123454";

        $collection = new Collection();
        $info = $collection->getAccountHolderBasicInfo($partyId);

        return response()->json([
            'info' => $info
        ],201);
    }


    public function getBalance(){
        $partyId = "46733123454";

        $collection = new Collection();
        $balance = $collection->getAccountBalance();

        if(!$balance){
            return response()->json([
                'error' => 'No Balance'
            ],403);
        }else {

        return response()->json([
            'balance' => $balance
        ],201);
        }

    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction)
    {
        //
    }
}