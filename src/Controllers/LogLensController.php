<?php
/*
 * @created 23/09/2020 - 11:11 PM
 * @project log-package
 * @author Aekansh Partani
*/

namespace Rigits\LaravelLogLens\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Rigits\LaravelLogLens\LaravelLogLens;

class LogLensController extends Controller
{
    private $log_lens;

    public function __construct()
    {
        $this->log_lens = new LaravelLogLens();
    }

    public function index(Request $request){
        $date = null;
        if (isset($request->d)){
            $date = Crypt::decrypt($request->d);
            $logData = $this->log_lens->getLogData($date);
        }else{
            $logData = $this->log_lens->getLogData();
        }
        $logFileDates = $this->log_lens->getLogFileDates();
        $logFileDatesUrls =  [];
        foreach ($logFileDates as $fileDate){
            $encrpytUrl = "?d=".Crypt::encrypt($fileDate);
            $logFileDatesUrls[$encrpytUrl] = $fileDate;
        }
        if ($date == null)
            $date = $logFileDates[0];

        session(['date' => $date]);


        return view('log-lens::log', compact('logData', 'logFileDatesUrls'));
    }

    private function print_o($obj){
        echo "<pre>";
        print_r($obj);
        die();
    }

}
