<?php
# Designed by Ryder Damen, 2017 - ryderdamen.com
# Purpose: to return classes in a lecture hall that match the given parameters

# File setup
date_default_timezone_set('America/Toronto');
header('Cache-Control: no-cache');
header('Pragma: no-cache');



#importing variables

$DOTW = htmlspecialchars($_GET["day"]);

#Converting selection to actual date for printing to the user
if ($DOTW == "today") {$printday = "today";}
if ($DOTW == "M") {$printday = "on Mondays";}
if ($DOTW == "T") {$printday = "on Tuesdays";}
if ($DOTW == "W") {$printday = "on Wednesdays";}
if ($DOTW == "R") {$printday = "on Thursdays";}
if ($DOTW == "F") {$printday = "on Fridays";}
if ($DOTW == "S") {$printday = "on Saturdays";}
if ($DOTW == "Su") {$printday = "on Sundays";}



# If the today selector was used, find the date for today
if ($DOTW == "today"){

	$DOTW = date('N', time());
if ($DOTW == 1){$DOTW = "M"; $printday = "today (Monday)";}
if ($DOTW == 2){$DOTW = "T"; $printday = "today (Tuesday)";}
if ($DOTW == 3){$DOTW = "W"; $printday = "today (Wednesday)";}
if ($DOTW == 4){$DOTW = "R"; $printday = "today (Thursday)";}
if ($DOTW == 5){$DOTW = "F"; $printday = "today (Friday)";}
if ($DOTW == 6){$DOTW = "S"; $printday = "today (Saturday)";}
if ($DOTW == 7){$DOTW = "Su"; $printday = "today (Sunday)";}
	
}



$building = htmlspecialchars($_GET["building"]);
$thenumber = htmlspecialchars($_GET["number"]);

$currentroom = $building . $thenumber;
$dayoftheweek = $DOTW;


#determining what duration we are in
$currentmonth = date('n', time());
if ( (1 <= $currentmonth) && ($currentmonth <= 4)){
    $currentduration = "D3";
}
if ( (9 <= $currentmonth) && ($currentmonth <= 12)){
    $currentduration = "D2";
}



#building the start of the page:


echo "
<!DOCTYPE html>
<!--
██████╗ ██████╗ 
██╔══██╗██╔══██╗
██████╔╝██║  ██║
██╔══██╗██║  ██║
██║  ██║██████╔╝
╚═╝  ╚═╝╚═════╝     
Designed and built by Ryder Damen, 2017
-->
<html lang='en'>
  <head>
	<title>$currentroom - Room Lookup - BrockU</title>
	<meta name='description' content='A way to look up classes currently happening at Brock University'>
	<meta name='author' content='Ryder Damen'>
	<link rel='icon' href='favicon.ico'>
	<link rel='stylesheet' type='text/css' href='style.css'>
	<link rel='stylesheet' media='screen and (max-width: 800px)' href='mobile.css' />
	<link rel='stylesheet' media='screen and (max-device-width: 800px)' href='mobile.css' />
      
<script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js'></script>

<script>
$(document).ready(function(){
  sortTable();
});
</script>

<script>
function sortTable() {
  var table, rows, switching, i, x, y, shouldSwitch;
  table = document.getElementById('classes');
  switching = true;
  /*Make a loop that will continue until
  no switching has been done:*/
  while (switching) {
    //start by saying: no switching is done:
    switching = false;
    rows = table.getElementsByTagName('TR');
    /*Loop through all table rows (except the
    first, which contains table headers):*/
    for (i = 1; i < (rows.length - 1); i++) {
      //start by saying there should be no switching:
      shouldSwitch = false;
      /*Get the two elements you want to compare,
      one from current row and one from the next:*/
      x = rows[i].getElementsByTagName('TD')[0];
      y = rows[i + 1].getElementsByTagName('TD')[0];
      //check if the two rows should switch place:
      if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
        //if so, mark as a switch and break the loop:
        shouldSwitch= true;
        break;
      }
    }
    if (shouldSwitch) {
      /*If a switch has been marked, make the switch
      and mark that a switch has been done:*/
      rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
      switching = true;
    }
  }
}
</script>
</head>
<body>
	  <div class='header'>
			<h1>Classes in $currentroom $printday</h1>
	  </div>
	  <div class='container'>
		  <div class='content'>
<table class='table' id='classes'><tr><th><p>Class</p></th><th><p>Type</p></th><th><p>Start</p></th><th><p>End</p></th></tr>
";

#starting the search loop
$file = fopen('undergrad-fall-winter-2016.csv', 'r');
while (($line = fgetcsv($file)) !== FALSE) {

                        $coursecode = $line[2];
                        $duration = $line[3];
                        $type = $line[6]; 
                        $days = $line[8];    
                        $time = $line[9];
                        $location = $line[10];
                        $subject = $line[12];
                        $code = $line[13];
   
    
#if the current room matches the location
    if ($currentroom == $location){
        
        
        if ($currentduration == $duration or $duration == "D1"){
        
        # if the date is today
        if (strpos($days, $dayoftheweek) !== false) {
                                    
            
            #date functions
                $tEvent = explode("-", $time);
                $start = str_replace(' ', '', $tEvent[0]);
                if (strlen($start) <= 3){
                    $start = "0" . $start;
                } 
                $start = strtotime($start);
                $tablesorter = date('Hi', $start);
                $start = date('g:i A', $start);
            
                $end = str_replace(' ', '', $tEvent[1]);
                if (strlen($end) <= 3){
                                        $end = "0" . $end;
                                        } 
                $end = strtotime($end);
                $end = date('g:i A', $end);
            
            #linking functions
            $code = str_replace(' ', '_', $code);
            
            #determining the type of class (lecture, seminar, lab etc)
            
            if (strpos($type, 'LEC') !== false) {
                $classtype = "Lecture";
            }
            if (strpos($type, 'SEM') !== false) {
                $classtype = "Seminar";
            }
            if (strpos($type, 'LAB') !== false) {
                $classtype = "Laboratory";
            }
            if (strpos($type, 'TUT') !== false) {
                $classtype = "Tutorial";
            }
            if (strpos($type, 'BLD') !== false) {
                $classtype = "Blended Course";
            }

            
            #return the classes in that room for that day
            echo "<tr><td style='display:none'>{$tablesorter}</td><td><a href='https://brocku.ca/webcal/current/undergrad/{$subject}.html#{$subject}{$code}' target='_blank'>{$coursecode}</a></td><td>{$classtype}</td><td>{$start}</td><td>{$end}</td></tr>";
}
 
    }
        
    } else continue;
   
}

fclose($file);

#finishing the page
echo "</table>";

echo "
<a href='index.html' id='checkagain'>Check another room</a>
</div></div>
    
        <script>
$( document ).ready(function() {
'sortTable()'
});
</script>
   
	<div class='footer'>
		<div>
			<p>Designed by <a href='http://ryderdamen.com'>Ryder Damen</a>.</p>
		</div>
	</div>
  </body>
</html>
";

?>