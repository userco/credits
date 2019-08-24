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
	
	//get list of credits
	public function getList(Request $request)
	{	
		//get all credits to pages
		$credits = Credit::paginate(5);
		
		if($request->session()){
			$min_period = ($request->session()->get('min_period')!="")?$request->session()->get('min_period'):0;
			$max_period = $request->session()->get('max_period')?$request->session()->get('max_period'):36;
			$min_amount = $request->session()->get('min_amount')?$request->session()->get('min_amount'):1;
			$max_amount = $request->session()->get('max_amount');
		
			//if max amount is not set
			if(!$max_amount){
				
				$credits = DB::table('credit')
						 ->select(DB::raw('*'))
						 ->where('period', '>=', $min_period)
						 ->where('period', '<=', $max_period)
						 ->where('total', '>=', $min_amount)
						 ->paginate(5);
			}else
			//if max amount is set
			{
				$credits = DB::table('credit')
						 ->select(DB::raw('*'))
						 ->where('period', '>=', $min_period)
						 ->where('period', '<=', $max_period)
						 ->where('total', '>=', $min_amount)
						 ->where('total', '<=', $max_amount)
						 ->paginate(5);
			}

			//message for display	
			$notice ="<div class='alert alert-info'>";			 
			$notice .= "<b>Search results</b>  ";
			$notice .= "<br>";
			$notice .= "Minimum period is: <b>".$min_period." months</b><br>";
			$notice .= "Maximum period is: <b>".$max_period." months</b><br>";
			$notice .= "Minimum amount is: <b>".$min_amount." BGN</b><br>";
			if(!$max_amount){
				 $notice .= "Maximum amount is: <b>unlimited</b><br>";
			}else{	
				$notice .= "Maximum amount is: <b>".$max_amount." BGN</b><br>";
			}
			$notice .= "</div>";
		}
		else{
			$notice = "";
		}
		return View::make('credit/credits_list')->with(array ('credits' => $credits,
																'notice'=> $notice));		
	}

	public function postList(CreditPostListRequest $request){
		
		//get all credits to pages
		$credits = Credit::paginate(5);
		$input = Input::get();
		
		if($input){
			$max_period = ($input['max_period']!="")?$input['max_period']:36;
			$min_period = ($input['min_period']!="")?$input['min_period']:0;
			$request->session()->put('min_period', $min_period);
			$request->session()->put('max_period', $max_period);
			
			$min_amount = ($input['min_amount']!="")? $input['min_amount']:1;
			$request->session()->put('min_amount', $min_amount);
		
			$max_amount = $input['max_amount'];
			//if max amount is not set
			if(!$max_amount){
				$request->session()->put('max_amount', 0);
				$credits = DB::table('credit')
						 ->select(DB::raw('*'))
						 ->where('period', '>=', $min_period)
						 ->where('period', '<=', $max_period)
						 ->where('total', '>=', $min_amount)
						 ->paginate(5);
			}
			else
			//if max amount is set
			{	
				$request->session()->put('max_amount', $max_amount);
				$credits = DB::table('credit')
							 ->select(DB::raw('*'))
							 ->where('period', '>=', $min_period)
							 ->where('period', '<=', $max_period)
							 ->where('total', '>=', $min_amount)
							 ->where('total', '<=', $max_amount)
							 ->paginate(5);
			}			 
		}	
		else
		//if there is no input	
		{
			$min_period = $request->session()->get('min_period');
			$max_period = $request->session()->get('max_period');
			$min_amount = $request->session()->get('min_amount');
			$max_amount = $request->session()->get('max_amount');
			//if max amount is not set
			if(!$max_amount){
				$credits = DB::table('credit')
						 ->select(DB::raw('*'))
						 ->where('period', '>=', $min_period)
						 ->where('period', '<=', $max_period)
						 ->where('total', '>=', $min_amount)
						 ->paginate(5);
			}else
			//if max amount is set
			{
				$credits = DB::table('credit')
						 ->select(DB::raw('*'))
						 ->where('period', '>=', $min_period)
						 ->where('period', '<=', $max_period)
						 ->where('total', '>=', $min_amount)
						 ->where('total', '<=', $max_amount)
						 ->paginate(5);
			}
		}
		//message for display			
		$notice ="<div class='alert alert-info'>";			 
		$notice .= "<b>Search results</b>  ";
		$notice .= "<br>";
		$notice .= "Minimum period is: <b>".$min_period." months</b><br>";
		$notice .= "Maximum period is: <b>".$max_period." months</b><br>";
		$notice .= "Minimum amount is: <b>".$min_amount."BGN</b><br>";
		if(!$max_amount){
			 $notice .= "Maximum amount is: <b>unlimited</b><br>";
		}else{	
			$notice .= "Maximum amount is: <b>".$max_amount." BGN</b><br>";
		}
		$notice .= "</div>";
		
		return View::make('credit/credits_list')->with(array ('credits' => $credits,
																'notice'=> $notice));	
	}
	
	//make investment in credit
	public function getInvest(Request $request, $creditId)
	{	
		//get logged in user
		$user = Auth::user();
		$userId = $user->id;
		$creditObj = Credit::find($creditId);
		$invested_amount = $creditObj->invested_amount;
		
		//get all investments of the user in that credit
		$investment = DB::table('invest_credit')
						 ->select(DB::raw('SUM(investment) as investment'))
						 ->where('user_id', '=', $userId)
						 ->where('credit_id', '=', $creditId)
						 ->first();
		$investment = $investment->investment;

		return View::make('credit/invest')->with(array ('invested_amount'=>$invested_amount,
														'investment'     => $investment,
														'creditObj'       => $creditObj,
														));			
	}
	public function postInvest(CreditPostInvestRequest $request, $creditId)
	{	
		//get logged in user
		$user      = Auth::user();
		$userId    = $user->id;
		$credits   = Credit::all();
		$creditObj = Credit::find($creditId);
		$invested_amount = $creditObj->invested_amount;
		
		//get all investments of the user in that credit
		$investment = DB::table('invest_credit')
						 ->select(DB::raw('SUM(investment) as investment'))
						 ->where('user_id', '=', $userId)
						 ->where('credit_id', '=', $creditId)
						 ->first();
		$investment = $investment->investment;
		$input = Input::get();
		$invest = $input['investment'];			
		
		$now_invested = $investment;
		
		//if the new investment plus the invested amount is less than or equal to total amount of
		//the credit
		if(($invested_amount + $invest) <= $creditObj->total){
			
			//save the new investment
			$invObj = new CreditInvest;
			$invObj->user_id    = $userId;
			$invObj->credit_id  = $creditId;
			$invObj->investment = $invest;
			$invObj->save();
			
			//save the new invested amount
			$creditObj->invested_amount = $invested_amount + $invest;	
			$creditObj->save();
			$now_invested += $invest;
			$invested_amount += $invest;
			$notif = "The data is successfully saved.";
			return View::make('credit/invest')->with(array ('invested_amount'=>$invested_amount,
														'investment'     => $now_invested,
														'creditObj'       => $creditObj,
														'notif'         => $notif,
														));		
		}
		else
		//if the new investment plus the invested amount is not less than total amount of the credit
		{		
			$notify = "The investments are greater than the credit amount";
			return View::make('credit/invest')->with(array ('invested_amount'=>$invested_amount,
														'investment'     => $investment,
														'creditObj'       => $creditObj,
														'notify'         => $notify
														));		
		}
		return View::make('credit/invest')->with(array ('invested_amount'=>$invested_amount,
														'investment'     => $investment,
														'creditObj'       => $creditObj
														));		
	}
	public function getCredit($credit_id){
		$credit = Credit::find($credit_id);	
		return $credit->toJson();
	}	
	
}    