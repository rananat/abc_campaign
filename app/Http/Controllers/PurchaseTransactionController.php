<?php

namespace App\Http\Controllers;

use App\Models\PurchaseTransaction;
use App\Http\Requests\StorePurchaseTransactionRequest;
use App\Http\Requests\UpdatePurchaseTransactionRequest;

class PurchaseTransactionController extends Controller
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePurchaseTransactionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePurchaseTransactionRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PurchaseTransaction  $purchaseTransaction
     * @return \Illuminate\Http\Response
     */
    public function show(PurchaseTransaction $purchaseTransaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PurchaseTransaction  $purchaseTransaction
     * @return \Illuminate\Http\Response
     */
    public function edit(PurchaseTransaction $purchaseTransaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePurchaseTransactionRequest  $request
     * @param  \App\Models\PurchaseTransaction  $purchaseTransaction
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePurchaseTransactionRequest $request, PurchaseTransaction $purchaseTransaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PurchaseTransaction  $purchaseTransaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(PurchaseTransaction $purchaseTransaction)
    {
        //
    }
}
