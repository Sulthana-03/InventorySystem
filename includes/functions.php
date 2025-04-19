<?php
 $errors = array();

 /*--------------------------------------------------------------*/
 /* Function for Remove escapes special
 /* characters in a string for use in an SQL statement
 /*--------------------------------------------------------------*/
function real_escape($str){
  global $con;
  $escape = mysqli_real_escape_string($con,$str);
  return $escape;
}
/*--------------------------------------------------------------*/
/* Function for Remove html characters
/*--------------------------------------------------------------*/
function remove_junk($str){
  $str = nl2br($str);
  $str = htmlspecialchars(strip_tags($str, ENT_QUOTES));
  return $str;
}
/*--------------------------------------------------------------*/
/* Function for Uppercase first character
/*--------------------------------------------------------------*/
function first_character($str){
  $val = str_replace('-'," ",$str);
  $val = ucfirst($val);
  return $val;
}
/*--------------------------------------------------------------*/
/* Function for Checking input fields not empty
/*--------------------------------------------------------------*/
function validate_fields($var){
  global $errors;
  foreach ($var as $field) {
    $val = remove_junk($_POST[$field]);
    if(isset($val) && $val==''){
      $errors = $field ." can't be blank.";
      return $errors;
    }
  }
}
/*--------------------------------------------------------------*/
/* Function for Display Session Message
   Ex echo displayt_msg($message);
/*--------------------------------------------------------------*/
function display_msg($msg =''){
   $output = array();
   if(!empty($msg)) {
      foreach ($msg as $key => $value) {
         $output  = "<div class=\"alert alert-{$key}\">";
         $output .= "<a href=\"#\" class=\"close\" data-dismiss=\"alert\">&times;</a>";
         $output .= remove_junk(first_character($value));
         $output .= "</div>";
      }
      return $output;
   } else {
     return "" ;
   }
}
/*--------------------------------------------------------------*/
/* Function for redirect
/*--------------------------------------------------------------*/
function redirect($url, $permanent = false)
{
    if (headers_sent() === false)
    {
      header('Location: ' . $url, true, ($permanent === true) ? 301 : 302);
    }

    exit();
}
/*--------------------------------------------------------------*/
/* Function for find out total saleing price, buying price and profit
/*--------------------------------------------------------------*/
function total_price($totals){
   $sum = 0;
   $sub = 0;
   $profit = 0;
   foreach($totals as $total ){
     $sum += $total['total_saleing_price'];
     $sub += $total['total_buying_price'];
     $profit = $sum - $sub;
   }
   return array($sum,$profit);
}
/*--------------------------------------------------------------*/
/* Function for Readable date time
/*--------------------------------------------------------------*/
function read_date($str){
     if($str)
      return date('F j, Y, g:i:s a', strtotime($str));
     else
      return null;
  }
/*--------------------------------------------------------------*/
/* Function for  Readable Make date time
/*--------------------------------------------------------------*/
function make_date(){
  return strftime("%Y-%m-%d %H:%M:%S", time());
}
/*--------------------------------------------------------------*/
/* Function for  Readable date time
/*--------------------------------------------------------------*/
function count_id(){
  static $count = 1;
  return $count++;
}
/*--------------------------------------------------------------*/
/* Function for Creting random string
/*--------------------------------------------------------------*/
function randString($length = 5)
{
  $str='';
  $cha = "0123456789abcdefghijklmnopqrstuvwxyz";

  for($x=0; $x<$length; $x++)
   $str .= $cha[mt_rand(0,strlen($cha))];
  return $str;
}


//

function join_invoice_table() {
  global $db;
  
  // SQL query to fetch data from the invoices table
  $query = "SELECT 
              invoices.id, 
              invoices.invoice_date, 
              invoices.quantity, 
              invoices.buying_price, 
              invoices.vendor_name, 
              invoices.vendor_id, 
              invoices.contact_number, 
              invoices.product_name, 
              invoices.category, 
              invoices.date
            FROM invoices";
  
  // Execute the query
  $result = $db->query($query);
  
  // Check if data exists
  if ($result->num_rows > 0) {
    // Fetch all data and return it as an associative array
    return $result->fetch_all(MYSQLI_ASSOC);
  } else {
    // If no data, return an empty array
    return [];
  }
}


//
// In your includes/load.php or database query file

// Function to find an invoice by ID
function find_invoice_by_id($invoice_id) {
  global $db;  // Use the global database connection

  // SQL query to fetch the invoice by ID
  $sql = "SELECT * FROM invoices WHERE id = '{$invoice_id}' LIMIT 1";
  $result = $db->query($sql);

  // Check if we have a result
  if($result && $db->num_rows($result) > 0) {
      // Return the invoice data as an associative array
      return $db->fetch_assoc($result);
  } else {
      // If no invoice is found, return false
      return false;
  }
}

//
// Function to join invoice table and search based on the provided search term
function join_invoice_tablee($search_term = '') {
  global $db;

  $sql = "SELECT * FROM invoices";

  if (!empty($search_term)) {
      $sql .= " WHERE vendor_name LIKE '%$search_term%' OR vendor_id LIKE '%$search_term%' OR invoice_date LIKE '%$search_term%'";
  }

  // Execute the query and return the results
  return find_by_sql($sql);
}

//
function find_invoice_by_dates($start_date, $end_date) {
  global $db;
  $sql = "SELECT * FROM invoices WHERE invoice_date BETWEEN '{$start_date}' AND '{$end_date}' ORDER BY invoice_date DESC";
  $result = $db->query($sql);
  return $result->fetch_all(MYSQLI_ASSOC);
}
//
function total_invoice_price($results) {
  $total = 0;

  foreach ($results as $result) {
      $total += $result['quantity'] * $result['buying_price'];
  }

  return $total;
}

?>
