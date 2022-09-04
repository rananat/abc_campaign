<?php

namespace App\Http\Controllers;

use App\Models\CustomerCampaign;
use App\Models\PurchaseTransaction;
use App\Models\Voucher;
use App\Http\Requests\StoreCustomerCampaignRequest;
use App\Http\Requests\UpdateCustomerCampaignRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class CustomerCampaignController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    const VOUCHER_ID_POS = 7;

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
     * @param  \App\Http\Requests\StoreCustomerCampaignRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCustomerCampaignRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CustomerCampaign  $customerCampaign
     * @return \Illuminate\Http\Response
     */
    public function show(CustomerCampaign $customerCampaign)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CustomerCampaign  $customerCampaign
     * @return \Illuminate\Http\Response
     */
    public function edit(CustomerCampaign $customerCampaign)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCustomerCampaignRequest  $request
     * @param  \App\Models\CustomerCampaign  $customerCampaign
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCustomerCampaignRequest $request, CustomerCampaign $customerCampaign)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CustomerCampaign  $customerCampaign
     * @return \Illuminate\Http\Response
     */
    public function destroy(CustomerCampaign $customerCampaign)
    {
        //
    }

    protected function getVoucherCode($id)
    {
        //Voucher id is enough for uniqueness.
        //If a random code is also required, we can use any method to generate a unique random voucher code using $id as seed.
        $code = md5(strval($id));
        //Add the voucher id to md5 result in order to retrieve voucher id back from voucher code
        return substr($code, 0, $this::VOUCHER_ID_POS) . strval($id) . substr($code, $this::VOUCHER_ID_POS); 
    }

    public function isEligible(Request $request, $id="")
    {
        if ($request->isMethod('post')) {
            $id = $request->input('id');
        }

        if (!is_numeric($id)) return ["status"=>500, "msg"=>"Invalid Customer Id"];
        
        //A customer cannot participate twice. If allowed, then additional conditions should be added to the following query.
        $customerCampaign = CustomerCampaign::where(['customer_id' => $id])->first();
        if ($customerCampaign) {
            return ["status"=>500,"msg"=>"Customer has already participated"];
        }

        $result = PurchaseTransaction::select(DB::raw('count(*) as transaction_count, sum(total_spent) as amount'))                    
                    ->whereRaw('customer_id = ' . $id . ' and transaction_at between DATE_SUB(DATE(NOW()), INTERVAL 30 DAY) AND DATE(NOW())')
                    ->get();
                    //->toSql();

        if ($result[0]->transaction_count >= 3 and $result[0]->amount >= 100) {                        

            DB::beginTransaction();

            try {
                //Acquire Lock - if we use any lock mechanism other than lockforupdate

                //Find the first unused voucher
                $voucher = Voucher::lockForUpdate()->firstwhere('status', 'U');

                //Change the status to L
                $voucher->update(['status' => 'L']);                  

                //Release Lock if we use any lock mechanism other than lockforupdate

                //Insert into customerCampaign customer_id, start_timestamp, voucher_id, status - L - Locked
                CustomerCampaign::create(['customer_id' => $id, 'start_timestamp' => date('Y-m-d H:i:s'), 'voucher_id' => $voucher->id, 'status' => 'L'])->toSql();                

                DB::commit();

            } catch (\Exception $e) {
                return ["status"=>500, "msg"=>$e->getMessage()];
                DB::rollback();
            }

            return ["status"=>200,"msg"=>"Eligible and Voucher locked"];

        }  else {

            try{
                //Insert into customerCampaign customer_id, start_timestamp, voucher_id, status - I - Ineligible                
                CustomerCampaign::updateOrCreate(['customer_id' => $id], ['start_timestamp' => date('Y-m-d H:i:s'), 'status' => 'I']);

            } catch (\Exception $e) {
                return ["status"=>500, "msg"=>$e->getMessage()];
            }

            return ["status"=>500,"msg"=>"Not Eligible"];
        }    

    }

    public function validateSubmission(Request $request, $id="") {
        $response = ["status"=>200, "msg"=>"success"];
        $baseURL = "";

        if ($request->isMethod('post')) {
            $id = $request->input('id');
            //Validate image here..
            if ($request->file('image')) {
                $response = Http::post(baseURL . 'api/validateImage', [
                    'image' => $request->file('image')
                ]);                
            }
        }

        if (!is_numeric($id)) return ["status"=>500, "msg"=>"Invalid Customer Id"];

        if ($response["status"] != 200) return ["status"=>500, "msg"=>"Image Validation Failed"];

        $customerCampaign = CustomerCampaign::where(['customer_id' => $id/*, 'status' => 'L'*/])->first();
        if (!$customerCampaign) {
            return ["status"=>500,"msg"=>"Exception: Customer has not participated yet"];
        }

        if ($customerCampaign and $customerCampaign->status != 'L') {
            return ["status"=>500,"msg"=>"Customer has already participated"];
        }

        $success = false; $submissionTime = date('Y-m-d H:i:s');

        $start = new \DateTime($customerCampaign->start_timestamp);
        $end = new \DateTime($submissionTime);
        $diff = $start->diff($end);

        if ($diff->days == 0 and $diff->h == 0 and $diff->i <= 10) {

            DB::beginTransaction();

            try {
                //Acquire Lock - if we use any lock mechanism other than lockforupdate

                //Find the voucher allotted to the Customer
                $voucher = Voucher::lockForUpdate()->where(['id' => $customerCampaign->voucher_id, 'status' => 'L'])->first();

                if (!$voucher) throw new \Exception("Voucher unexpectedly not locked");

                //Change the status to A - Allotted
                $voucher->update(['status' => 'A']);                  

                //Release Lock if we use any lock mechanism other than lockforupdate

                //Update customerCampaign with status = S - success and submission time.
                $customerCampaign->update(['status' => 'S', 'end_timestamp' => $submissionTime]); 
                
                DB::commit();

            } catch (\Exception $e) {
                return ["status"=>500, "msg"=>$e->getMessage()];
                DB::rollback();
            }

            return ["status"=>200, "msg"=>"Customer has won the voucher " . $this->getVoucherCode($voucher->id)/*, "start"=>$customerCampaign->start_timestamp, "end" => $submissionTime, 'hrDiff'=>$diff->h, 'minDiff' => $diff->i*/];
        } else {
            DB::beginTransaction();

            try {
                //Find the voucher allotted to the Customer
                $voucher = Voucher::lockForUpdate()->where(['id' => $customerCampaign->voucher_id, 'status' => 'L'])->first();

                if (!$voucher) throw new \Exception("Voucher unexpectedly not locked");

                //Change the status to U - Unused
                $voucher->update(['status' => 'U']);                  

                //Release Lock if we use any lock mechanism other than lockforupdate

                //Update customerCampaign with status = F - fail and submission time.
                $customerCampaign->update(['status' => 'F', 'end_timestamp' => $submissionTime]); 
                
                DB::commit();

            } catch (\Exception $e) {
                return ["status"=>500, "msg"=>$e->getMessage()];
                DB::rollback();
            }
            return ["status"=>500, "msg"=>"Submission Time exceeded"/*, "start"=>$customerCampaign->start_timestamp, "end" => $submissionTime, 'hrDiff'=>$diff->h, 'minDiff' => $diff->i*/];
        }
    }

}
