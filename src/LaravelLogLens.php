<?php
/*
 * @created 23/09/2020 - 10:07 PM
 * @project log-package
 * @author Aekansh Partani
*/

namespace Rigits\LaravelLogLens;

use Illuminate\Support\Facades\Storage;

class LaravelLogLens
{

    private $storage_path;

    private $pattern = [
        'logs' => "/^\[(?<date>.*)\]\s(?<env>\w+)\.(?<level>\w+):(?<message>.*)/m"
];

    public function __construct()
    {
        $this->storage_path = function_exists('config') ? config('loglens.storage_path', storage_path('logs')) : storage_path('logs');
    }

    public function getLogFileDates()
    {
        $dates = [];
        $files = glob($this->storage_path.'/laravel-*.log');
        $files = array_reverse($files);

        foreach ($files as $path) {
            $fileName = basename($path);
            preg_match('/(?<=laravel-)(.*)(?=.log)/', $fileName, $dtMatch);
            $date = $dtMatch[0];
            array_push($dates, $date);
        }

        return $dates;
    }

    public function getLogData($logDate = null){
        $logFileDates = $this->getLogFileDates();
        if (count($logFileDates) == 0){
            return response()->json([
                'success' => false,
                'message' => 'No log available'
            ]);
        }
        if($logDate == null)
            $logDate = $logFileDates[0];

        $log = array();
        $fileName = 'laravel-' . $logDate . '.log';
        $filePath = $this->storage_path.DIRECTORY_SEPARATOR. $fileName;
        $file = app('files')->get($filePath);

        preg_match_all($this->pattern['logs'], $file, $headings,  PREG_SET_ORDER, 0);

        $logData = preg_split($this->pattern['logs'], $file);
        if ($logData[0] < 1) {
            array_shift($logData);
        }

        foreach ($headings as $heading){
            for ($i = 0, $j = count($heading); $i < $j; $i++) {
                $stack = null;
                if (isset($logData[$i])){
                    $stack =  preg_replace("/^\n*/", '', $logData[$i]);
                }
                $log[] = array(
                    'timestamp' => $heading['date'],
                    'env' => $heading['env'],
                    'level' => $heading['level'],
                    'message' => $heading['message'],
                    'stack' => $stack
                );
            }
        }


        return $log;

    }

    private function print_o($obj){
        echo "<pre>";
        print_r($obj);
        die();
    }



}
