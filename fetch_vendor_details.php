<?php
require_once('includes/load.php');

// Check if vendor_id is provided
if (isset($_GET['vendor_id'])) {
  $vendor_id = (int)$_GET['vendor_id'];

  // Fetch vendor details
  $vendor = find_by_id('vendors', $vendor_id);
  
  if ($vendor) {
    // Return vendor name and contact number as JSON
    echo json_encode([
      'vendor_name' => $vendor['name'],
      'contact_number' => $vendor['contact_number']
    ]);
  } else {
    echo json_encode(['error' => 'Vendor not found.']);
  }
} else {
  echo json_encode(['error' => 'No vendor ID provided.']);
}
?>
