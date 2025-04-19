<?php
  require_once('includes/load.php');
  
  if(isset($_POST['vendor_id'])){
    $vendor_id = (int)$_POST['vendor_id'];
    
    // Query the vendor information from the 'vendors' table
    $query = "SELECT id, contact_number FROM vendors WHERE id = '{$vendor_id}' LIMIT 1";
    $result = $db->query($query);
    
    if($db->num_rows($result) > 0){
      $vendor = $db->fetch_assoc($result);
      echo json_encode($vendor); // Return the vendor info as a JSON object
    }
  }
?>
