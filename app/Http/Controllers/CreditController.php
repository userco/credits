<?php

namespace App\Http\Controllers;

use App\Credit;
use App\CreditInvest;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use DB;
use View;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreditPostListRequest;
use App\Http\Requests\CreditPostInvestRequest;

class CreditController extends Controller{

	public function getList(Request $request)
	{	
		$credits = Credit::paginate(2);
		return View::make('credit/credits_list')->with(array ('credits'=>$credits));		
	}



	public function postList(CreditPostListRequest $request){
		
		$credits = Credit::paginate(2);
	
		$input = Input::get();
		if($input){
			$max_period = $input['max_period'];
			//dd($max_period);
			$min_period = ($input['min_period'])?$input['min_period']:0;
			$request->session()->put('min_period', $min_period);
			$request->session()->put('max_period', $max_period);
			
			$max_amount = $input['max_amount'];
			$min_amount = ($input['min_amount'])? $input['min_amount']:1;
			$request->session()->put('min_amount', $min_amount);
			$request->session()->put('max_amount', $max_amount);
			
			$credits = DB::table('credit')
						 ->select(DB::raw('*'))
						 ->where('period', '>=', $min_period)
						 ->where('period', '<=', $max_period)
						 ->where('total', '>=', $min_amount)
						 ->where('total', '<=', $max_amount)
						 ->paginate(2);
			//dd($credits);
		}	
		else{
			$min_period = $request->session()->get('min_period');
			$max_period = $request->session()->get('max_period');
			$min_amount = $request->session()->get('min_amount');
			$max_amount = $request->session()->get('max_amount');
			
			$credits = DB::table('credit')
						 ->select(DB::raw('*'))
						 ->where('period', '>=', $min_period)
						 ->where('period', '<=', $max_period)
						 ->where('total', '>=', $min_amount)
						 ->where('total', '<=', $max_amount)
						 ->paginate(2);
			
		}	
		return View::make('credit/credits_list')->with(array ('credits'=>$credits));	
	}
	public function getInvest(Request $request, $creditId)
	{	
		$user = Auth::user();
		$userId = $user->id;
		$creditObj = Credit::find($creditId);
		$invested_amount = $creditObj->invested_amount;
		//dd($invested_amount);
		$investment = DB::table('invest_credit')
						 ->select(DB::raw('SUM(investment) as investment'))
						 ->where('user_id', '=', $userId)
						 ->where('credit_id', '<=', $creditId)
						 ->get()[0];
		$investment = $investment->investment;
		return View::make('credit/invest')->with(array ('invested_amount'=>$invested_amount,
														'investment'     => $investment,
														'creditId'       => $creditId
														));			
	}
	public function postInvest(CreditPostInvestRequest $request, $creditId)
	{	

		$user = Auth::user();
		$userId = $user->id;
		$credits = Credit::all();
		$creditObj = Credit::find($creditId);
		$invested_amount = $creditObj->invested_amount;
		$investment = DB::table('invest_credit')
						 ->select(DB::raw('SUM(investment) as investment'))
						 ->where('user_id', '=', $userId)
						 ->where('credit_id', '<=', $creditId)
						 ->get()[0];
		$investment = $investment->investment;
			
			
		$input = Input::get();
		$invest = $input['investment'];	
		$invObj = new CreditInvest;
		$invObj->user_id = $userId;
		$invObj->credit_id = $creditId;
		$invObj->investment = $invest;
		
		$invObj->save();
		$inv = $invested_amount + $invest;
		if($inv <= $creditObj->total){
			$creditObj->invested_amount = $inv;	
			$creditObj->save();
			$notif = "The data is successfully saved.";
			return View::make('credit/invest')->with(array ('invested_amount'=>$invested_amount,
														'investment'     => $investment,
														'creditId'       => $creditId,
														'notif'         => $notif
														));		
		}
		else{		
			$notify = "The investments are greater than the credit amount";
			return View::make('credit/invest')->with(array ('invested_amount'=>$invested_amount,
														'investment'     => $investment,
														'creditId'       => $creditId,
														'notify'         => $notify
														));		
		}
		return View::make('credit/invest')->with(array ('invested_amount'=>$invested_amount,
														'investment'     => $investment,
														'creditId'       => $creditId
														));		
	}
}    