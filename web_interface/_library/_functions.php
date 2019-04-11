<?php
//---------------------------------------------------------------------------------------------
// Implement the IIF function
function IIF($flgBOOL, $truePART, $falsePART) {
   return ($flgBOOL ? $truePART : $falsePART);
}
//---------------------------------------------------------------------------------------------
// Convert date from "YYYY-MM-DD HH:MM" into Unix TimeStamp format compatible with JavaScript
function dbDate2Java($dbDate) {
   $datetime = DateTimeImmutable::createFromFormat('Y-m-d H:i', $dbDate, new DateTimeZone('UTC'));
   return date_timestamp_get($datetime)*1000;
}
//---------------------------------------------------------------------------------------------
// Check provided date against format string to evaluate its validity 
function validateDateFormat($date, $format = 'Y-m-d H:i') {
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}
//---------------------------------------------------------------------------------------------
function printDBdate($dbDate) {
   if ($dbDate == "") {
      return "n/a";
   } else {
      return substr($dbDate, 6, 2)."/".substr($dbDate, 4, 2)."/".substr($dbDate, 0, 4)." at ".substr($dbDate, 8, 2).":".substr($dbDate, 10, 2);
   }
}
//---------------------------------------------------------------------------------------------
function getMonthName($vlMonth) {
   switch ($vlMonth) {
      case "1":
         return "Gen";
      	break;
      case "2":
         return "Feb";
      	break;
      case "3":
         return "Mar";
      	break;
      case "4":
         return "Apr";
      	break;
      case "5":
         return "Mag";
      	break;
      case "6":
         return "Giu";
      	break;
      case "7":
         return "Lug";
      	break;
      case "8":
         return "Ago";
      	break;
      case "9":
         return "Set";
      	break;
      case "10":
         return "Ott";
      	break;
      case "11":
         return "Nov";
      	break;
      case "12":
         return "Dic";
      	break;
      default:
         return "?";
   }
}
//---------------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------------
?>

