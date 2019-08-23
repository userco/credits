<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use DB;
use App\Credit;
use App\Mail\CreditEmail;
use Illuminate\Support\Facades\Mail;

class SyncronizeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:db';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
		$guzzle = new Client;
	    $state = \Str::random(40);
		$response = $guzzle->post('https://dbank.donatix.info/oauth/token', 
		[
			'headers' => [
							'Content-Type' => 'application/x-www-form-urlencoded',
							'Access-Control-Allow-Methods'=>'POST'
						 ],
			'form_params' => [
				'grant_type' => 'client_credentials',
				'client_id' => 1,
				'client_secret' => 'OeKbhkKn0SoSOrXhxba1mMJAMWubZYPnAwCk7Sgw',
				'scope' => '*',
				'state' => $state,
			],
			
		]);
		$token = json_decode((string) $response->getBody(), true)['access_token'];
		
		$response2 = $guzzle->get('https://dbank.donatix.info/api/v1/credits',
		[
			'headers'=> [
							'Authorization' => 'Bearer ' . $token,  
							'Accept' => 'application/json',
							'Content-type' => 'application/json', 'Access-Control-Allow-Methods'=>'GET'
						]
		]);
		$credits = json_decode($response2->getBody(),true);
		$i=0;
		
		foreach($credits as $credit){
			
			if($i>10) break;
			$external_id = $credit['external_id'];
			$type = $credit['type'];
			$total = $credit['total'];
			$request_number = $credit['request_number'];
			$period = $credit['period'];
			$date = $credit['date'];
			$creditId = DB::table('credit')->select('id')->where('external_id', $external_id)->first();
			$id = ($creditId)?$creditId->id :null;
			$creditObject = ($id)? Credit::find($id): null;
			
			if(!$creditObject){
				$creditObject = new Credit;
				$creditObject->external_id = $external_id;
				$creditObject->type = $type;
				$creditObject->total = $total;
				$creditObject->request_number = $request_number;
				$creditObject->period = $period;
				$creditObject->invested_amount = 0;
				
				$creditObject->save();
				
			}else{
				if($creditObject->total > $total){
					$creditObject->total = $total;
					$creditObject->save();
				}
			}
			if($creditObject->total <= $creditObject->invested_amount){
				$credit_id = $creditObject->id;
				$emails = DB::table('invest_credit')
						 ->join('users', 'user_id', '=', 'users.id')
						 ->select('email')
						 ->where('credit_id', '=', $credit_id)
						 ->get();
				$list_emails = [];
				$email_array = json_decode($emails,true);
				foreach($email_array as $email){
					$email_address = $email['email'];
					if(!in_array($email_address, $list_emails)){
						$list_emails[] = $email_address;
						Mail::to($email_address)->queue(new CreditEmail($creditObject));
					}
			    }			
						 
			}
			$i++;		
		}
	}
}
