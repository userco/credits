<?php
 
namespace App\Mail;
 
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
 
class CreditEmail extends Mailable
{
    use Queueable, SerializesModels;
     
    /**
     * The demo object instance.
     *
     * @var Demo
     */
    public $credit;
 
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($credit)
    {
        $this->credit = $credit;
    }
 
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('i_n_f@abv.bg')
                    ->view('mails.email');      
                      
    }
}