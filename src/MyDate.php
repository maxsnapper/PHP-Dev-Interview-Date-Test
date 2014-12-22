<?php

  class MyDate {
    public static $years = 0;
    public static $months = 0;
    public static $days = 0;
    public static $total_days = 0;
    public static $invert = false;

    public static $start = array();
    public static $end = array();
    
    public static function diff($strStart, $strEnd) {
        self::$total_days = 0;
        self::$months = 0;
        self::$days = 0;
        self::$total_days = 0;
        self::$years = 0;
        echo "\n", $strStart, " - ", $strEnd , "\n";
        // Invert check and swap dates (to try not causing negative values)
        if($strStart > $strEnd){
            self::$end = self::dateToArr($strStart);
            self::$start = self::dateToArr($strEnd);
            self::$invert = true;
        }else{
            self::$start = self::dateToArr($strStart);
            self::$end = self::dateToArr($strEnd);
            self::$invert = false;
        }

        self::dayCount();
        self::monthCount();
        self::yearCount();    
        //self::getTotalDaysCount();
         
      $return = (object)array(
        'years' => abs(self::$years),
        'months' => abs(self::$months),
        'days' => abs(self::$days),
        'total_days' => abs(self::$total_days),
        'invert' => self::$invert
      );
      print_r($return);
      return $return;
    }
   
    public static function dayCount(){
        if(self::$start['day'] == self::$end['day']){
            self::$days = 0;
        }else if(self::$start['day'] < self::$end['day']){
            self::$days = self::$end['day'] - self::$start['day'];
        }else if(self::$start['day'] > self::$end['day']){
            self::$days = abs(self::DaysInMonth(self::$start['month']) - self::$start['day']) + self::$end['day'];
            self::$months --;
        }
        self::$total_days += self::$days;
    }
    
    public static function monthCount(){
        $currentYear = self::$start['year'];
        self::$months = abs(self::$end['month'] - self::$start['month']);
        if(self::$start['month'] > self::$end['month']){
            self::$months -= 11;
            $incMonth = 1;
        }else{
            $incMonth = 0;
        }
        $isLeapYear = (self::isLeapYear(self::$start['month']) || self::isLeapYear(self::$end['month']))? true : false;
        for($month = self::$start['month']+$incMonth; $month <> self::$end['month'];  $month++){
            if($month > 12){ 
                $month = 1; 
                self::$years = -1; 
                $currentYear++; 
            }
            self::$total_days += self::DaysInMonth($month, ($isLeapYear || self::isLeapYear($currentYear)));
        }
    }

    public static function yearCount(){
        //self::$years = 0;
        for($year = self::$start['year']; $year < self::$end['year']; $year++){
            self::$years ++;
            if($year == self::$start['year'] && self::$start['month'] > self::$end['month']){
                //ignore this
            }else{
                self::$total_days += ((self::isLeapYear($year))? 366 : 365);
            }
        }
    }

    public static function dateToArr($strDate){
        try{
            $arrDate = split('/', $strDate);
            if ( 
                   count($arrDate) !== 3 
                || (!is_numeric($arrDate[0]) 
                    || !is_numeric($arrDate[1]) 
                    || !is_numeric($arrDate[2])
                   )
              ){
                throw new Exception('Format of the date appears to be incorrect');
            }else{
                return array(
                    'year'=> $arrDate[0]
                    , 'month'=> $arrDate[1]
                    , 'day' => $arrDate[2]
                );
                
            }
        } catch (Exception $e) {
            echo "There was an error: ", $e->getMessage(), "\n";
            exit();
        }
    }

    public static function daysInMonth($month, $isLeapYear = false){
        $monthToDays = array(0, 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
        if ($isLeapYear){ $monthToDays[2] = 29; }
        return $monthToDays[intval($month)];
   }

   public static function isLeapYear($year){
      return (($year % 4 == 0)? true: false);
   }

}
