<?php
  $page_title = 'Edit Invoice';
  require_once('includes/load.php');
  page_require_level(2);

  // Get invoice data by ID
  if (isset($_GET['id'])) {
    $invoice = find_by_id('invoices', (int)$_GET['id']);
    $all_vendors = find_all('vendors');
    $all_products = find_all('products');
    $all_categories = find_all('categories');
  }

  if (!$invoice) {
    $session->msg("d", "Missing invoice id.");
    redirect('invoice.php');
  }

  // Fetch the vendor's contact number for the selected vendor
  $vendor_contact_number = '';
  if ($invoice['vendor_id']) {
    $vendor = find_by_id('vendors', (int)$invoice['vendor_id']);
    if ($vendor) {
      $vendor_contact_number = $vendor['contact_number'];
    }
  }
?>

<?php
// Handle form submission for updating invoice
if (isset($_POST['update_invoice'])) {
    // Required fields for validation
    $req_fields = array('invoice_date', 'vendor_id', 'product_name', 'quantity', 'buying_price', 'category');
    validate_fields($req_fields);

    if (empty($errors)) {
        // Sanitize form data
        $invoice_date = remove_junk($db->escape($_POST['invoice_date']));
        $vendor_id = (int)$_POST['vendor_id'];
        $vendor_name = remove_junk($db->escape($_POST['vendor_name']));
        $product_name = remove_junk($db->escape($_POST['product_name']));
        $quantity = (int)$_POST['quantity'];
        $buying_price = remove_junk($db->escape($_POST['buying_price']));
        $category = remove_junk($db->escape($_POST['category'])); // Get category name directly
        $contact_number = remove_junk($db->escape($_POST['contact_number']));
   
        // Update query to modify the invoice
        $query = "UPDATE invoices SET 
                  invoice_date = '{$invoice_date}', 
                  vendor_id = '{$vendor_id}', 
                  vendor_name = '{$vendor_name}', 
                  product_name = '{$product_name}', 
                  quantity = '{$quantity}', 
                  buying_price = '{$buying_price}', 
                  category = '{$category}', 
                  contact_number = '{$contact_number}'  
                  WHERE id = '{$invoice['id']}'";
        $result = $db->query($query);

        if($result && $db->affected_rows() === 1){
            $session->msg('s', "Invoice updated successfully.");
            redirect('invoice.php', false);
        } else {
            $session->msg('d', 'Sorry, failed to update invoice.');
            redirect('edit_invoice.php?id='.$invoice['id'], false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('edit_invoice.php?id='.$invoice['id'], false);
    }
}
?>

<?php include_once('layouts/header.php'); ?>

<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>
</div>

<div class="row">
  <div class="panel panel-default">
    <div class="panel-heading clearfix">
      <strong>
        <span class="glyphicon glyphicon-pencil"></span>
        <span>Edit Invoice</span>
      </strong>
    </div>
    <div class="panel-body">
      <div class="col-md-7">
        <form method="post" action="edit_invoice.php?id=<?php echo (int)$invoice['id']; ?>">

          <!-- Invoice Date -->
          <div class="form-group">
            <label for="invoice_date">Invoice Date</label>
            <div class="input-group">
              <span class="input-group-addon">
                <i class="glyphicon glyphicon-calendar"></i>
              </span>
              <input type="date" class="form-control" name="invoice_date" value="<?php echo remove_junk($invoice['invoice_date']); ?>" required>
            </div>
          </div>

          <!-- Vendor Information -->
          <div class="form-group">
            <label for="vendor_name">Vendor</label>
            <div class="row">
              <div class="col-md-6">
                <select class="form-control" name="vendor_name" id="vendor_name" required onchange="updateVendorId()">
                  <option value="">Select Vendor</option>
                  <?php foreach ($all_vendors as $vendor): ?>
                    <option value="<?php echo remove_junk($vendor['name']); ?>" 
                            <?php if ($invoice['vendor_name'] === $vendor['name']) echo "selected"; ?> >
                      <?php echo remove_junk($vendor['name']); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-md-6">
                <input type="text" class="form-control" name="vendor_id" id="vendor_id" value="<?php echo (int)$invoice['vendor_id']; ?>" placeholder="Vendor ID" readonly>
              </div>
            </div>
          </div>

          <!-- Vendor Contact Number -->
          <div class="form-group">
            <label for="contact_number">Vendor Contact Number</label>
            <input type="text" class="form-control" name="contact_number" id="contact_number" value="<?php echo $vendor_contact_number; ?>" readonly>
          </div>

          <!-- Product Information -->
          <div class="form-group">
            <label for="product_name">Product</label>
            <div class="row">
              <div class="col-md-6">
                <select class="form-control" name="product_name" required>
                  <option value="">Select Product</option>
                  <?php foreach ($all_products as $product): ?>
                    <option value="<?php echo remove_junk($product['name']); ?>" <?php if($invoice['product_name'] === $product['name']) echo "selected"; ?>>
                      <?php echo remove_junk($product['name']); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-md-6">
                <input type="number" class="form-control" name="quantity" value="<?php echo remove_junk($invoice['quantity']); ?>" placeholder="Quantity" required>
              </div>
            </div>
          </div>

          <!-- Buying Price -->
          <div class="form-group">
            <label for="buying_price">Buying Price</label>
            <div class="input-group">
              <span class="input-group-addon">
                <i class="glyphicon glyphicon-usd"></i>
              </span>
              <input type="number" class="form-control" name="buying_price" value="<?php echo remove_junk($invoice['buying_price']); ?>" placeholder="Buying Price" required>
            </div>
          </div>

          <!-- Category -->
          <div class="form-group">
            <label for="category">Category</label>
            <select class="form-control" name="category" required>
              <option value="">Select Category</option>
              <?php foreach ($all_categories as $category): ?>
                <!-- Set the value as category name -->
                <option value="<?php echo remove_junk($category['name']); ?>" <?php if($invoice['category'] === $category['name']) echo "selected"; ?>>
                  <?php echo remove_junk($category['name']); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Submit Button -->
          <button type="submit" name="update_invoice" class="btn btn-primary">Update Invoice</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  // Function to update vendor_id based on selected vendor name
  function updateVendorId() {
    var vendorName = document.getElementById('vendor_name').value;
    var vendorIdInput = document.getElementById('vendor_id');
    var contactInput = document.getElementById('contact_number');

    // Array of vendors and their details (id, name, contact)
    var vendors = <?php echo json_encode($all_vendors); ?>;

    // Find the selected vendor based on vendor name
    var selectedVendor = vendors.find(vendor => vendor.name === vendorName);

    if (selectedVendor) {
      // Update the vendor ID and contact number
      vendorIdInput.value = selectedVendor.id;
      contactInput.value = selectedVendor.contact_number;
    }
  }

  // Initialize vendor details when the page loads
  document.addEventListener('DOMContentLoaded', function() {
    updateVendorId(); // Ensure the fields are populated correctly on page load
  });
</script>

<?php include_once('layouts/footer.php'); ?>
