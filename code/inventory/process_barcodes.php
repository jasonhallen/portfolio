<?php
use google\appengine\api\cloud_storage\CloudStorageTools;
use google\appengine\api\mail\Message;
ini_set('max_execution_time', 0);
//session_start();
//pre($_POST);
//Include XLSX Reader
include 'simplexlsx/simplexlsx.class.php';
//Ensure Authentication and load API Keys
//Uncomment line below to enable authentication after setting up login.php properly
//require("login.php");
require("SortCallNumber.php");
require("almaBarcodeAPI.php");
$shelflist = [];
$output_array = [];
$problem = false;
$orderProblem = '';
$cnTypeProblem = '';
$nipProblem = '';
$tempProblem = '';
$libraryProblem = '';
$locationProblem = '';
$policyProblem = '';
$typeProblem = '';
$orderProblemCount = 0;
$cnTypeProblemCount = 0;
$tempProblemCount = 0;
$requestProblemCount = 0;
$locationProblemCount = 0;
$libraryProblemCount = 0;
$policyProblemCount = 0;
$typeProblemCount = 0;
//Only run code below if form submitted

if ( $xlsx = SimpleXLSX::parse($_POST['gsfile']) ) {
      list($num_cols, $num_rows) = $xlsx->dimension();
        //load callNumber array and sort for printing below
        //Rows in sheet 1
        $row=1;
        foreach( $xlsx->rows() as $k => $r ) {
          // Start the session when using it. Not before or out of the loop. Remember that you are only using it to store the % of progress.
           //session_start();
            //Skip First row
            if ($k == 0) {
              //Check that first cell is header "barcodes"
              if($r[0] == 'barcodes')
              {
                continue; // Header is ok, skip first row and continue
              }
              else {
                echo "Upload file must have header row labeled barcodes";
                exit;
              }
            }
                //only need first column from Excel sheet, so hard coding 0 for column #
                $barcode = $r[0];
                //echo($barcode);
                /*
                //object elements returned from retrieveBarcodeInfo function call below
                $item_obj->title = (string)$xml_barcode_result->bib_data->title;
                $item_obj->item_link = (string)$xml_barcode_result['link']."?apikey=" . ALMA_SHELFLIST_API_KEY;
                $item_obj->mms_id = (string)$xml_barcode_result->bib_data->mms_id;
                $item_obj->bib_link = (string)$xml_barcode_result->bib_data['link']."?apikey=" . ALMA_SHELFLIST_API_KEY;
                $item_obj->holding_id = (string)$xml_barcode_result->holding_data->holding_id;
                $item_obj->holding_link = (string)$xml_barcode_result->holding_data['link']."?apikey=" . ALMA_SHELFLIST_API_KEY;
                $item_obj->item_pid = (string)$xml_barcode_result->item_data->pid;
                $item_obj->item_barcode = (string)$xml_barcode_result->item_data->barcode;
                $item_obj->call_number = (string)$xml_barcode_result->holding_data->call_number. " " . (string)$xml_barcode_result->item_data->enumeration_a. " " . (string)$xml_barcode_result->item_data->chronology_i;
                $item_obj->in_temp_location = (string)$xml_barcode_result->holding_data->in_temp_location;
                $item_obj->call_number_type = (string)$xml_barcode_result->holding_data->call_number_type;
                $item_obj->status = (string)$xml_barcode_result->item_data->base_status;
                $item_obj->status_desc = (string)$xml_barcode_result->item_data->base_status['desc'];
                $item_obj->process_type = (string)$xml_barcode_result->item_data->process_type;
                $item_obj->library = (string)$xml_barcode_result->item_data->library;
                $item_obj->location = (string)$xml_barcode_result->item_data->location;
                $item_obj->physical_material_type = (string)$xml_barcode_result->item_data->physical_material_type;
                $item_obj->item_note3 = (string)$xml_barcode_result->item_data->internal_note_3;
                $item_obj->requested = (string)$xml_barcode_result->item_data->requested;
                $item_obj->policy = (string)$xml_barcode_result->item_data->policy;
                */
                $itemData = retrieveBarcodeInfo($barcode);
                //If Barcode Not Found Write Scanned Barcode to Item Object So it Will print on report
                if ($itemData->item_barcode == '')
                {
                  $itemData->item_barcode = $barcode;
                  $itemData->title = 'NOT FOUND';
                  $itemData->call_sort = '!';
                }
                else {  
                  //Barcode was found so we can store a normalized call number to use for sorting
                  
                  //Need to remove "DVD " prefix prior to sorting if DVD
                  if (isset($_POST['almaitemType']) && $_POST['almaitemType'] == 'DVD') {  
                    // Remove any inital "DVD " prior to sorting
                    $itemData->call_number = preg_replace("/^DVD\s*/", "", $itemData->call_number);
                  }
                  //if call_number_type == 1 it should be dewey
                  if($itemData->call_number_type == 1)
                  {
                    $itemData->call_sort = normalizeDewey($itemData->call_number);
                  }
                  else {
                    $itemData->call_sort = normalizeLC($itemData->call_number);
                  }
                }
                //For (dubugging) view item info
                //pre($itemData);
                //store to array for sorting
                $unsorted[$row] = $itemData;
                $unsorted[$row]->scan_loc = $row;
                $row= $row+1;
                $percentage = round($row * 100 / $num_rows); // determine the % of completion / load
                //THIS is the most important part. This function will close the session writting. Why? Because if the script loop is still running, the $_SESSION will be unaccesible and you have to wait till it ends to access it.
                echo '<script>
                parent.document.getElementById("progress-bar").innerHTML="<div style=\"width:'.$percentage.'%;background:linear-gradient(to bottom, rgba(125,126,125,1) 0%,rgba(14,14,14,1) 100%); ;height:35px;\">&nbsp;</div>";
                parent.document.getElementById("information").innerHTML="<div style=\"text-align:center; font-weight:bold\">'.$percentage.' % processed.</div>";</script>';
                ob_flush(); 
                flush(); 
                
                /*$_SESSION['percentage'] = $percentage;
                $_SESSION['job'] = "Retrieving Barcodes From API";
                if ($percentage == 100)
                {
                  $_SESSION['job'] = "complete";
                  $_SESSION['percentage'] = 0;
                }
     session_write_close();*/
        }
        echo '<script>
        parent.document.getElementById("progress-section").innerHTML="<div style=\"display:none;</div>";
        </script>';
        //session_destroy(); 
        //pre($unsorted);
        //This converts arroy of stdClass objects to a mutlidimensional
        //array so we can sort using array sort
        $unsortedArray = json_decode(json_encode($unsorted), true);
        //pre($unsortedArray);
        $first = reset($unsortedArray);
        $last = end($unsortedArray);
        $first_call = $first['call_number'];
        //remove spaces and periods
        $first_call = strtr($first_call, array('.' => '', ' ' => ''));
        $last_call = $last['call_number'];
        $last_call = strtr($last_call, array('.' => '', ' ' => ''));
        //var_dump($first, $last, $first_call, $last_call);
        //Sort array and maintain original scan key order
        //Useful for caluculating difference between proper location and scan location
        $sortednk = $unsortedArray;
        //pre($sortednk);
        if ($_POST['almacnType'] == 'dewey') {
          $sortednk_success = usort($sortednk, "SortDeweyObject");
      }
   else {
      $sortednk_success = usort($sortednk, "SortLCObject"); //sort by LC Call Number
      }
        //Sort without maintainin key order.  Just keeping for reference.
        //$sortedkey = $unsortedArray;
        //$sortedkey_success = uasort($sortedkey, "SortLCObject");
        //Start loop of processing records and writing to output array
        $previousCN = 1;
        foreach ($sortednk as $key => $number) {
          //pre($sortednk[$key]);
          $problem = false;
            //Don't flag order issues if only Other problems are requested
            if ($_POST['almaonlyother'] == 'false') {
                //Next two if statements take care of undefined offset issue
                if (!isset($sortednk[$key - 1]['scan_loc'])) {
                    $sortednk[$key - 1]['scan_loc'] = null;
                }
                if (!isset($sortednk[$key + 1]['scan_loc'])) {
                    $sortednk[$key + 1]['scan_loc'] = null;
                }
                $prevScan_loc = $sortednk[$key - 1]['scan_loc'] + 1;
                $scan_loc = $sortednk[$key]['scan_loc'] + 1;
                $nextScan_loc = $sortednk[$key + 1]['scan_loc'] + 1;
                $nextdiff = $nextScan_loc - $scan_loc;
                $prevdiff = $scan_loc - $prevScan_loc;
                if ($prevdiff != 1 && $nextdiff != 1) {
                    //Next two if statements take care of undefined offset issue
                    if (!isset($unsortedArray[$sortednk[$key]['scan_loc'] - 1])) {
                        $unsortedArray[$sortednk[$key]['scan_loc'] - 1] = null;
                    }
                    if (!isset($unsortedArray[$sortednk[$key]['scan_loc'] + 1])) {
                        $unsortedArray[$sortednk[$key]['scan_loc'] + 1] = null;
                    }
                    $move = $prevScan_loc - $scan_loc;
                    $prevScan_loc = 0;
                    $scan_loc = 0;
                    if ($move <0){
                      $move = 'Move item back '.(abs($move) -1)  . ' spaces';
                    }
                    else {
                      $move = 'Move item forward '.($move)  . ' spaces';
                    }
                    $orderProblem = "**OUT OF ORDER**<BR>Item Currently Between:<BR><em>" . $unsortedArray[$sortednk[$key]['scan_loc'] - 1]['call_number'] . "</em> & <em>" . $unsortedArray[$sortednk[$key]['scan_loc'] + 1]['call_number'] . "</em><BR>" . $move . "<BR>";
                    $orderProblemCount += 1;
                    $problem = true;
                } else {
                    $orderProblem = '';
                }
            }
            //Don't flag other issues if only order problems are requested
            if ($_POST['almaonlyorder'] == 'false') {
                if ($_POST['almacnType'] == 'dewey'){
                $cntype = 1;
              }elseif ($_POST['almacnType'] == 'lc') {
                $cntype = 0;
              }
                if ($sortednk[$key]['call_number_type'] != $cntype) {
                    $cnTypeProblem = "**WRONG CN TYPE**<BR>";
                    $cnTypeProblemCount += 1;
                    $problem = true;
                } else {
                    $cnTypeProblem = '';
                }
                if ($sortednk[$key]['status'] != 1) {
                    $nipProblem = "**NIP: " . $sortednk[$key]['process_type'] . "**<BR>";
                    $problem = true;
                } else {
                    $nipProblem = '';
                }
                if ($sortednk[$key]['in_temp_location'] != 'false') {
                    $tempProblem = "**IN TEMP LOC**<BR>";
                    $tempProblemCount += 1;
                    $problem = true;
                } else {
                    $tempProblem = '';
                }
                if ($sortednk[$key]['requested'] != 'false') {
                    $requestProblem = "**ITEM HAS REQUEST**<BR>";
                    $requestProblemCount +=1;
                    $problem = true;
                } else {
                    $requestProblem = '';
                }
                $location = $_POST['almalocation'];
                if ($sortednk[$key]['location'] != $location) {
                    $locationProblem = "**WRONG LOCATION: " . $sortednk[$key]['location'] . "**<BR>";
                    $locationProblemCount += 1;
                    $problem = true;
                } else {
                    $locationProblem = '';
                }
                $library = $_POST['almalibrary'];
                if ($sortednk[$key]['library'] != $library) {
                    $libraryProblem = "**WRONG LIBRARY: " . $sortednk[$key]['library'] . "**<BR>";
                    $libraryProblemCount += 1;
                    $problem = true;
                } else {
                    $libraryProblem = '';
                }
                $policy = $_POST['almapolicy'];
                if ($sortednk[$key]['policy'] != $policy) {
                    if ($sortednk[$key]['policy'] != '') {
                        $policyProblem = "**WRONG ITEM POLICY: " . $sortednk[$key]['policy'] . "**<BR>";
                        $policyProblemCount += 1;
                    } else {
                        $policyProblem = "**BLANK I POLICY**<BR>";
                        $policyProblemCount += 1;
                    }
                    $problem = true;
                } else {
                    $policyProblem = '';
                }
                $type = $_POST['almaitemType'];
                if ($sortednk[$key]['physical_material_type'] != $type) {
                    if ($sortednk[$key]['physical_material_type'] != '') {
                        $typeProblem = "**WRONG TYPE: " . $sortednk[$key]['physical_material_type'] . "**<BR>";
                        $typeProblemCount +=1;
                    } else {
                        $typeProblem = "**BLANK I TYPE**<BR>";
                        $typeProblemCount +=1;
                    }
                    $problem = true;
                } else {
                    $typeProblem = '';
                }
            }
            $scan_loc = $sortednk[$key]['scan_loc'];
            $correct_loc = $key + 1;
            //If row has a problem print in Bold and output problems to an output
            //array that way we can re-sort output array if desired
            $shelflist_obj = new stdClass();
            $shelflist_obj->correct_location = $correct_loc;
            $shelflist_obj->call_number = $sortednk[$key]['call_number'];
            $shelflist_obj->norm_call_number = $sortednk[$key]['call_sort'];
            $shelflist_obj->title = utf8_encode (substr($sortednk[$key]['title'], 0, 20) . '...');
            $shelflist_obj->scanned_location = $scan_loc;
            $shelflist_obj->problem_list = $orderProblem . $cnTypeProblem . $nipProblem . $tempProblem . $libraryProblem . $locationProblem . $policyProblem . $typeProblem;
            $shelflist_obj->barcode = $sortednk[$key]['item_barcode'];
            $shelflist_obj->problem = $problem;
            //Add this loation to the array of locations using the unique location code as the index value
            //This converts stdClass objects to an
            //array so we can sort using array sort
            $shelflist[trim($key)] = json_decode(json_encode($shelflist_obj), true);
            //pre($shelflist);
            // Calculate the percentation
            //$_SESSION['progress'] = intval($key/$num_rows * 100);
        }
        //write out page header info
        echo "<div class='page-header'>";
          echo "  <h1>ShelfList <small>". $_POST['almalibrary'] . ':' . $_POST['almalocation'] . ' Range:' . substr($first_call, 0, 4) . '-' . substr($last_call, 0, 4) .' Run Date:'. date('Ymd') ."</small></h1>";
        echo "</div>";
        echo "<p class='lead'>";
          echo "Upload file contains ". ($num_rows - 1) . " barcodes.";
        echo "</p>";
        echo "<div class='row'>";
        $csv_output_filename = 'ShelfList_' . $_POST['almalibrary'] . '_' . $_POST['almalocation'] . '_' . substr($first_call, 0, 4) . '_' . substr($last_call, 0, 4) . '_' . date('Ymd') . '.csv';
          echo "<div class='col-md-4'><a href=" . "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "index.php" . "> Run New File</a></div> <div class='col-md-4'><a href=gs://bridge-shelf-inventory.appspot.com/" . $csv_output_filename . ">Download File: " . $csv_output_filename . "</a></div>";
        echo "</div>";
        echo "<table style='width: auto;' class='table table-hover table-bordered table-condensed'><tr><td>";
        echo '<B>' . $orderProblemCount . '</b> Order Problems Found</td>';
        echo '<td>' . $cnTypeProblemCount . '</b> Call Number Type Problems Found</td>';
        echo '<td>' . $tempProblemCount . '</b> Temp Location Problems Found</td>';
        echo '<td>' . $requestProblemCount . '</b> Item on Request Problems Found</td></tr>';
        echo '<tr><td>' . $locationProblemCount . '</b> Wrong Location Problems Found</td>';
        echo '<td>' . $libraryProblemCount . '</b> Wrong Library Problems Found</td>';
        echo '<td>' . $policyProblemCount . '</b> Item Policy Problems Found</td>';
        echo '<td>' . $typeProblemCount . '</b> Item Type Problems Found</td></tr>';
        echo '<tr><td>First call number scanned: <B>' . $first_call . '</b></td>';
        echo '<td>Last call number scanned: <B>' . $last_call . '</b></td></tr></table>';
        //pre($output_array);
        outputRecords($shelflist);
        updateInventoryDate($sortednk);
 }   else {
    exit;
}

function pre($data) {
    print '<pre>' . print_r($data, true) . '</pre>';
}
function outputRecords($output){
  //Use global to allow use inside of function
  global $csv_output_filename;
  // check if cached barcodeOutput file exists and delete if needed
  if (file_exists("gs://bridge-shelf-inventory.appspot.com/output/" . $csv_output_filename)) {
      unlink("gs://bridge-shelf-inventory.appspot.com/output/" . $csv_output_filename);
      if (isset($_GET['debug'])) {
          print("cache file deleted");
      }
  }
// open the csv file for writing
  $csv_file = fopen('gs://bridge-shelf-inventory.appspot.com/output/' . $csv_output_filename, 'w');
// save the CSV column headers
  fputcsv($csv_file, array('Correct_Position', 'Call_Number', 'norm_call_number','Title', 'Position Scanned', 'Problem', 'Barcode'));
  echo "<table id='CNTable' style='width: auto;' class='table table-hover table-striped table-bordered table-condensed tablesorter'>";
  echo "<thead>";
  echo "<tr>";
  echo "<th>Correct<BR>Order</th>";
  echo "<th>Correct CN Order</th>";
  //  echo "<th>Norm CN Order</th>";
  echo "<th>Title</th>";
  echo "<th>Where<BR>Scanned</th>";
  echo "<th>Problem</th>";
  echo "<th>Barcode</th>";
  echo "</tr>";
  echo "</thead>";
  echo "<tbody>";
  foreach ($output as $key => $number) {
    //Don't print non-problems if only problems are requested
    if ($_POST['almaonlyproblems'] == 'true' && $output[$key]['problem'] != 1) {
    continue;
    }
    //Highlight problem rows using bootstrap contextual class
  if ($output[$key]['problem'] == 1) {
    echo "<tr class='danger' style='font-weight:bold'>";
  }
  else {
    echo "<tr>";
  }
          echo "<td>" . $output[$key]['correct_location'] . "</td>";
          echo "<td>" . $output[$key]['call_number'] . "</td>";
          //          echo "<td>" . $output[$key]['norm_call_number'] . "</td>";
          echo "<td>" . $output[$key]['title'] . "</td>";
          echo "<td>" . $output[$key]['scanned_location']  . "</td>";
          echo "<td>" . $output[$key]['problem_list'] . "</td>";
          echo "<td>" . $output[$key]['barcode']   . "</td>";
          echo "</tr>";
          //output to csv
          //remove <BR's> from csv output
          $problems = preg_replace('#(<br */?>\s*)+#i', '', $output[$key]['problem_list'] );
          //remove <em's> from csv output
          $problems = preg_replace('#(<em */?>\s*)+#i', '', $problems);
          $problems = preg_replace('#(</em */?>\s*)+#i', '', $problems);
          fputcsv($csv_file, array($output[$key]['correct_location'], $output[$key]['call_number'], $output[$key]['norm_call_number'], $output[$key]['title'], $output[$key]['scanned_location'],$problems,"=\"" . $output[$key]['barcode'] ."\"" ));
  }
  echo "</tbody>";
  echo "</table>";
  // Close the output CSV file
  $fileNameCSV = 'gs://bridge-shelf-inventory.appspot.com/output/' . $csv_output_filename;
  $publicUrl = CloudStorageTools::getPublicUrl($fileNameCSV, false);
  $body = '<p>Your report is now available to download at:</p><a href="' . $publicUrl . '">' . $publicUrl . '</a>';


  try {
    $message = new Message();
    $message->setSender('no-reply@bridge-shelf-inventory.appspotmail.com');
    $message->addTo($_POST['email']);
    $message->setSubject('Your Inventory Report');
    $message->setHtmlBody($body);                 
    $message->send();
    echo 'Mail Sent';
} catch (InvalidArgumentException $e) {
    echo 'There was an error';
}
      fclose($csv_file);

} 

function updateInventoryDate($sortednk) {
  foreach ($sortednk as $key) {
    $file_contents = file_get_contents("cache/barcodes/" . $key['item_barcode'] . ".xml"); 
    $file_contents = preg_replace("#<inventory_date>(.*?)</inventory_date>#", "<inventory_date>" . date("Y-m-d") . "</inventory_date>", $file_contents);
    file_put_contents("cache/barcodes/" . $key['item_barcode'] . ".xml",$file_contents);
    $url = "https://api-na.hosted.exlibrisgroup.com/almaws/v1/bibs/" . $key['mms_id'] . "/holdings/" . $key['holding_id'] . "/items/" . $key['item_pid'] . "?apikey=" . ALMA_SHELFLIST_API_KEY;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml'));
    curl_setopt( $ch, CURLOPT_POSTFIELDS, $file_contents);
    $result = curl_exec($ch);
  }
}
?>