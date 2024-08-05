<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Statistics\PaymentsService;
use App\Services\Statistics\CostsService;
use App\Services\Statistics\RegistrationService;
use App\Services\Statistics\UserRegistrationMonthlyService;
use App\Services\Statistics\DavinciUsageService;
use App\Services\Statistics\GoogleAnalyticsService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\SupportTicket;
use App\Models\Payment;
use App\Models\User;

class AdminController extends Controller
{
    /**
     * Display admin dashboard
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $year = $request->input('year', date('Y'));
        $month = $request->input('month', date('m'));

        $payment = new PaymentsService($year, $month);
        $cost = new CostsService($year, $month);
        $davinci = new DavinciUsageService($month, $year);
        $registration = new RegistrationService($year, $month);
        $user_registration = new UserRegistrationMonthlyService($month);
        $google = new GoogleAnalyticsService();
       
        $total_data_monthly = [
            'new_subscribers_current_month' => $registration->getNewSubscribersCurrentMonth(),
            'new_subscribers_past_month' => $registration->getNewSubscribersPastMonth(),
            'income_current_month' => $payment->getTotalPaymentsCurrentMonth(),
            'income_past_month' => $payment->getTotalPaymentsPastMonth(),
            'words_current_month' => $davinci->getTotalWordsCurrentMonth(),
            'words_past_month' => $davinci->getTotalWordsPastMonth(),
            'images_current_month' => $davinci->getTotalImagesCurrentMonth(),
            'images_past_month' => $davinci->getTotalImagesCurrentMonth(),
            'contents_current_month' => $davinci->getTotalContentsCurrentMonth(),
            'contents_past_month' => $davinci->getTotalContentsPastMonth(),
            'transactions_current_month' => $payment->getTotalTransactionsCurrentMonth(),
            'transactions_past_month' => $payment->getTotalTransactionsPastMonth(),
        ];

        $total_data_yearly = [
            'total_new_users' => $registration->getNewUsersCurrentYear(),
            'total_users' => $registration->getTotalUsers(),
            'total_subscribers' => $registration->getTotalSubscribers(),
            'total_nonsubscribers' => $registration->getTotalNonSubscribers(),
            'total_new_subscribers' => $registration->getNewSubscribersCurrentYear(),
            'total_income' => $payment->getTotalPaymentsCurrentYear(),
            'words_generated' => $davinci->getTotalWordsCurrentYear(),
            'images_generated' => $davinci->getTotalImagesCurrentYear(),
            'contents_generated' => $davinci->getTotalContentsCurrentYear(),
            'transactions_generated' => $payment->getTotalTransactionsCurrentYear(),
            'referral_earnings' => $payment->getTotalReferralEarnings(),
            'referral_payouts' => $payment->getTotalReferralPayouts(),
        ];
        
        $chart_data['total_new_users'] = json_encode($registration->getAllUsers());
        $chart_data['total_income'] = json_encode($payment->getPayments());
        $chart_data['monthly_earnings'] = json_encode($payment->getPayments());
        $chart_data['user_countries'] = json_encode($this->getAllCountries());

        if (!empty(config('services.google.analytics.property')) && !empty(config('services.google.analytics.credentials'))) {
            $chart_data['traffic_label'] = json_encode($google->getTrafficLabels());
            $chart_data['traffic_data'] = json_encode($google->getTrafficData());
            $chart_data['google_countries'] = $google->userCountries();
            $chart_data['google_countries_total'] = $google->userCountriesTotal();
            $chart_data['google_average_session'] = $google->averageSessionDuration();
            $chart_data['google_sessions'] = $google->sessions();
            $chart_data['google_session_views'] = $google->sessionViews();
            $chart_data['google_bounce_rate'] = $google->bounceRate();
            $chart_data['google_users'] = json_encode($google->users());
            $chart_data['google_user_sessions'] = json_encode($google->userSessions());
        }

        $chart_data['gpt3_words'] = $davinci->gpt3TurboWords();
        $chart_data['gpt3_tasks'] = $davinci->gpt3TurboTasks();
        $chart_data['gpt4_words'] = $davinci->gpt4Words();
        $chart_data['gpt4_tasks'] = $davinci->gpt4Tasks();
        $chart_data['gpt4o_words'] = $davinci->gpt4oWords();
        $chart_data['gpt4o_tasks'] = $davinci->gpt4oTasks();
        $chart_data['gpt4t_words'] = $davinci->gpt4TurboWords();
        $chart_data['gpt4t_tasks'] = $davinci->gpt4TurboTasks();
        $chart_data['opus_words'] = $davinci->opusWords();
        $chart_data['opus_tasks'] = $davinci->opusTasks();
        $chart_data['sonnet_words'] = $davinci->sonnetWords();
        $chart_data['sonnet_tasks'] = $davinci->sonnetTasks();
        $chart_data['haiku_words'] = $davinci->haikuWords();
        $chart_data['haiku_tasks'] = $davinci->haikuTasks();
        $chart_data['gemini_words'] = $davinci->geminiWords();
        $chart_data['gemini_tasks'] = $davinci->geminiTasks();
        

        $percentage['subscribers_current'] = json_encode($registration->getNewSubscribersCurrentMonth());
        $percentage['subscribers_past'] = json_encode($registration->getNewSubscribersPastMonth());
        $percentage['income_current'] = json_encode($payment->getTotalPaymentsCurrentMonth());
        $percentage['income_past'] = json_encode($payment->getTotalPaymentsPastMonth());
        $percentage['images_current'] = json_encode($davinci->getTotalImagesCurrentMonth());
        $percentage['images_past'] = json_encode($davinci->getTotalImagesCurrentMonth());
        $percentage['contents_current'] = json_encode($davinci->getTotalContentsCurrentMonth());
        $percentage['contents_past'] = json_encode($davinci->getTotalContentsPastMonth());
        $percentage['transactions_current'] = json_encode($payment->getTotalTransactionsCurrentMonth());
        $percentage['transactions_past'] = json_encode($payment->getTotalTransactionsPastMonth());

        $notifications = Auth::user()->notifications->where('type', '<>', 'App\Notifications\GeneralNotification')->all();
        $tickets = SupportTicket::whereNot('status', 'Resolved')->whereNot('status', 'Closed')->latest()->paginate(8);

        $users = User::latest()->take(10)->get();
        $transaction = Payment::latest()->take(10)->get();       
 
        return view('admin.dashboard.index', compact('total_data_monthly', 'total_data_yearly', 'chart_data', 'percentage', 'users', 'transaction', 'notifications', 'tickets'));
    }


    /**
     * Show list of all countries
     */
    public function getAllCountries()
    {        
        $countries = User::select(DB::raw("count(id) as data, country"))
                ->groupBy('country')
                ->orderBy('data')
                ->pluck('data', 'country');    
        
        return $countries;        
    }

}
