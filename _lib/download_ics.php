<?php
require_once(dirname(dirname(__FILE__)) . '/config/config.php');
include(ROOT . '/_lib/autoload.php');
spl_autoload_register('load_classes');

$_eventsData = new Dashboard();

if ($_eventsData->gpGet('loc') === 'team')
{
    $f = $_eventsData->GetEventsData($_GET['id'], 'cp_team_events_calendar');
} else {
    $f = $_eventsData->GetEventsData($_GET['id']);
}

$fileName = str_replace(" " , "-", $f['event_title']) . '.ics';

function format_timestamp($timestamp) {

    $dt = (is_object($timestamp))?$timestamp:new DateTime($timestamp);
    return $dt->format('Ymd\THis');
}

header('Content-Type: text/calendar; charset=utf-8');
header('Content-Disposition: attachment; filename=' . $fileName);

$eventAddress = $f['event_address'] . ' ' . ucfirst(strtolower($f['event_city'])) . ' ' . $f['event_state'] . ' ' . $f['event_zip'];
$timezone = $f['timezone'] ? $f['timezone'] : 'America/New_York'; // set default timezone to america/new york if no timezone set
//BUG FIX for strip_tags() leaving blank/white spaces
$descriptionDirty = strip_tags(str_replace('&nbsp;', ' ', $f['event_description']));
$descriptionClean = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\\n", $descriptionDirty);

$endDateTime = format_timestamp(date('Ymd', strtotime($f['event_date'])) . ' ' . date('His', strtotime($f['event_end_time'])));
$startDateTime = format_timestamp(date('Ymd', strtotime($f['event_date'])) . ' ' . date('His', strtotime($f['event_time'])));


$details = array(
    'location' => $eventAddress,
    'description' => $descriptionClean,
    'tzdtend' => $timezone.':'.$endDateTime,
    'tzdtstart' => $timezone.':'.$startDateTime,
    'summary' => $f['event_title'],
    'url' => $f['url'],
);

//FOR REOCCURRING
if($f['is_reoccuring']=='1'){
    //FOR WEEKLY DAY
    if($f['event_reoccuring_type']=='Weekly Day'){
        $startDate = $f['event_date'];
        $getDate = date('d',strtotime($startDate));
        $count = 0;
        $weekDays = [];  
        $j=0;
        for($i=1; $i<=52; $i++){
            $days = explode(',', $f['event_reoccuring_days']);
            $daysArray = [
                            '0'=>'sunday this week',
                            '1'=>'monday this week',
                            '2'=>'tuesday this week',
                            '3'=>'wednesday this week',
                            '4'=>'thursday this week',
                            '5'=>'friday this week',
                            '6'=>'saturday this week',
                        ];
            $daysIcsValues = [
                            '0'=>'SU',
                            '1'=>'MO',
                            '2'=>'TU',
                            '3'=>'WE',
                            '4'=>'TH',
                            '5'=>'FR',
                            '6'=>'SA',
                        ];
            foreach($days as $day){                    
                if(array_key_exists($day,$daysArray)){
                    if(!is_null($f['event_end_date']) && strtotime($f['event_end_date'])>=strtotime($startDate) ){
                        if(strtotime($daysArray[$day], strtotime($startDate))>strtotime($f['event_date'])){
                            $count++;  
                            $weekDays[] =  $daysIcsValues[$day]; 
                            if($j==0){
                                $details['tzdtstart']=$timezone.':'.format_timestamp(date("Ymd", strtotime($daysArray[$day], strtotime($startDate))). ' ' . date('His', strtotime($f['event_time'])));
                                $j++;
                            }

                        }                           
                    }
                }
            }
            $startDate = date('Y-m-d', strtotime($startDate.' +7 day'));
        }
        $details['rrule']="FREQ=WEEKLY;COUNT=".$count.";WKST=SU;BYDAY=".implode("!",array_unique($weekDays));
    }
    //FOR DAILY
    elseif($f['event_reoccuring_type']=='Daily'){
        $startDate = $f['event_date'];
        $getDate = date('d',strtotime($startDate));
        $count = 0;  
        for($i=1; $i<=365; $i++){
            if(!is_null($f['event_end_date']) && strtotime($f['event_end_date'])>=strtotime($startDate) ){
                $setDate = date('Y-m', strtotime($startDate)).'-'.$getDate;
                $count++;               
                $startDate = date('Y-m-d', strtotime($startDate.' +1 day'));
            }
        }
        $details['rrule']="FREQ=DAILY;COUNT=".$count;
    }
    //FOR WEEKLY
    elseif($f['event_reoccuring_type']=='Weekly'){
        $startDate = $f['event_date'];
        $getDate = date('d',strtotime($startDate));
        $count = 0;  
        for($i=1; $i<=52; $i++){
            if(!is_null($f['event_end_date']) && strtotime($f['event_end_date'])>=strtotime($startDate) ){
                $setDate = date('Y-m', strtotime($startDate)).'-'.$getDate;
                $count++;               
                $startDate = date('Y-m-d', strtotime($startDate.' +7 day'));
            }
        }
        $details['rrule']="FREQ=WEEKLY;COUNT=".$count;
    }
    //FOR MONTHLY
    elseif($f['event_reoccuring_type']=='Monthly'){
        $startDate = $f['event_date'];
        $getDate = date('d',strtotime($startDate));
        $count = 0;  
        for($i=1; $i<=12; $i++){
            if(!is_null($f['event_end_date']) && strtotime($f['event_end_date'])>=strtotime($startDate) ){
                $setDate = date('Y-m', strtotime($startDate)).'-'.$getDate;
                if(date('m',strtotime($setDate))==date('m',strtotime($startDate))){
                    $count++;               
                }
                $startDate = date('Y-m-d', strtotime($startDate.' +1 month'));
            }
        }
        $details['rrule']="FREQ=MONTHLY;COUNT=".$count;
    }

    
}   

$ics = new ICS($details);

echo $ics->to_string();
