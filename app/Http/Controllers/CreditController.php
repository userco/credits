<?php

namespace App\Http\Controllers;

use App\Credit;
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
		$credits = Credit::all();
		return View::make('credit/credits_list')->with(array ('credits'=>$credits));		
	}



	public function postList(CreditPostListRequest $request){
		
		$credits = Credit::all();

		if($request->isMethod('post') && $request->input('submit')){
			
			$input = Input::get();
			$max_period = $input['max_period'];
			$min_period = ($input['min_period'])?$input['min_period']:0;
			
			$max_amount = $input['max_amount'];
			$min_amount = ($input['min_amount'])? $input['min_amount']:1;
			
			
			$credits = DB::table('credit')
						 ->select(DB::raw('*'))
						 ->where('period', '>=', $min_period)
						 ->where('period', '<=', $max_period)
						 ->where('total', '>=', $min_amount)
						 ->where('total', '<=', $max_amount)
						 ->get();
			
		}
		return View::make('credit/credits_list')->with(array ('credits'=>$credits));	
	}
	public function getInvest(Request $request, $creditId)
	{	
		//$input = Input::get();
	   // $invest = $input['creditId'];	
		//$credit = Credit::find($creditId);
		//dd($creditId);
		return View::make('credit/invest')->with(array ('creditId'=>$creditId));		
	}
	public function postInvest(CreditPostInvestRequest $request, $creditId)
	{	

		$user = Auth::user();
		$userId = $user->id;
		$credit = Credit::find($creditId);
		$invested_amount = $credit->invested_amount;
		$investment = DB::table('invest_credit')
						 ->select(DB::raw('SUM(investment) as investment'))
						 ->where('user_id', '=', $userId)
						 ->where('credit_id', '<=', $creditId)
						 ->get();
						 
		if($request->isMethod('post') && $request->input('submit')){
			
			$input = Input::get();
			$invest = $input['investment'];	
			$invObj = new CreditInvest;
			$invObj->user_id = $userId;
			$invObj->creditId = $creditId;
			$invObj->investment = $invest;
			
			$invObj->save();
			
		}	
		return View::make('credit/invest')->with(array ('invested_amount'=>$invested_amount,
														'investment'     => $investment,
														'creditId'       => $creditId
														));		
	}
}    