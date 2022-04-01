<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CompanyId;
use App\Models\CompanyBooking;
use App\Models\CompanyStaff;
use App\Models\CompanyCustomers;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class SendNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'booking:notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a 5 minutes email/sms reservation';

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
	    $data = array('name'=>"Virat");

	    Mail::send('front.email.mail', $data, function($message) {
		    $message->to('sanatatea@yandex.com', 'Tutorials')
		            ->subject('Laravel');
		    $message->from('sanatatea@yandex.com','Virat');
	    });

//	    $company_list = CompanyId::where('active',1)->where('deleted',0)->get();
//
//	    if($company_list){
//		    foreach ($company_list as $one_company){
//			    $company_settings = checkCompanySettings($one_company->id);
//
//			    /*For staff*/
//			    if($company_settings->staff_send_email){
//				    $staff_booking = CompanyBooking::where('deleted',0)
//				                                   ->where('company_id',$one_company->id)
//				                                   ->where('booking_date','>=',date('Y-m-d',strtotime(Carbon::now())))
//				                                   ->where('booking_date','<=',date('Y-m-d',strtotime(Carbon::now()->addMinutes(5))))
//				                                   ->where(function($query) use($company_settings){
//					                                   $query->where('booking_date','<=',date('Y-m-d',strtotime(Carbon::now()->addDays($company_settings->staff_before_day)->addHours(date('H',strtotime($company_settings->staff_before_time)))->addMinutes(date('i',strtotime($company_settings->staff_before_time))))))
//					                                         ->where('booking_time','<=',date('H:i',strtotime(Carbon::now()->addDays($company_settings->staff_before_day)->addHours(date('H',strtotime($company_settings->staff_before_time)))->addMinutes(date('i',strtotime($company_settings->staff_before_time))))));
//				                                   })
//				                                   ->where('message_staff_sent',0)
//				                                   ->get();
//
//
//				    if($staff_booking){
//					    foreach ($staff_booking as $one_booking){
//						    sendNotifications($one_company->id,$one_booking->id,2,'send_before','staff');
//					    }
//				    }
//			    }
//
//			    /*For customer*/
//			    if($company_settings->customer_send_email || $company_settings->customer_send_sms){
//				    $customers_booking = CompanyBooking::where('deleted',0)
//				                                       ->where('company_id',$one_company->id)
//				                                       ->where('booking_date','>=',date('Y-m-d',strtotime(Carbon::now())))
//				                                       ->where('booking_date','<=',date('Y-m-d',strtotime(Carbon::now()->addMinutes(5))))
//				                                       ->where(function($query) use($company_settings){
//					                                       $query->where('booking_date','<=',date('Y-m-d',strtotime(Carbon::now()->addDays($company_settings->customer_before_day)->addHours(date('H',strtotime($company_settings->customer_before_time)))->addMinutes(date('i',strtotime($company_settings->customer_before_time))))))
//					                                             ->where('booking_time','<=',date('H:i',strtotime(Carbon::now()->addDays($company_settings->customer_before_day)->addHours(date('H',strtotime($company_settings->customer_before_time)))->addMinutes(date('i',strtotime($company_settings->customer_before_time))))));
//				                                       })
//				                                       ->where('message_sent',0)
//				                                       ->get();
//
//				    if($customers_booking){
//					    foreach ($customers_booking as $one_booking){
//						    sendNotifications($one_company->id,$one_booking->id,2,'send_before','customer');
//					    }
//				    }
//			    }
//		    }
//	    }

//	    $this->info('Success');
    }
}
