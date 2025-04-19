<?php
  $page_title = 'Add Invoice';
  require_once('includes/load.php');
  page_require_level(2);

  // Fetch all vendors, products, and categories for the select boxes
  $all_vendors = find_all('vendors');
  $all_products = find_all('products');
  $all_categories = find_all('categories');
?>

<?php
  if(isset($_POST['add_invoice'])){
    $req_fields = array('invoice-date', 'invoice-vendor', 'invoice-product', 'invoice-quantity', 'invoice-buying-price', 'invoice-product-category', 'invoice-contact');
    validate_fields($req_fields);
    
    if(empty($errors)){
      $invoice_date      = remove_junk($db->escape($_POST['invoice-date']));
      $vendor_id         = remove_junk($db->escape($_POST['invoice-vendor']));
      $product_id        = remove_junk($db->escape($_POST['invoice-product']));
      $quantity          = remove_junk($db->escape($_POST['invoice-quantity']));
      $contact_number    = remove_junk($db->escape($_POST['invoice-contact']));
      $buying_price      = remove_junk($db->escape($_POST['invoice-buying-price']));
      $category_id       = remove_junk($db->escape($_POST['invoice-product-category']));
      $date              = make_date();
      
      // Fetch vendor name
      $vendor_query = "SELECT name FROM vendors WHERE id = '{$vendor_id}' LIMIT 1";
      $vendor_result = $db->query($vendor_query);
      if($vendor_result && $db->num_rows($vendor_result) > 0) {
        $vendor = $db->fetch_assoc($vendor_result);
        $vendor_name = $vendor['name'];
      } else {
        $vendor_name = 'Unknown'; // Default value if vendor is not found
      }

      // Fetch product name
      $product_query = "SELECT name FROM products WHERE id = '{$product_id}' LIMIT 1";
      $product_result = $db->query($product_query);
      if($product_result && $db->num_rows($product_result) > 0) {
        $product = $db->fetch_assoc($product_result);
        $product_name = $product['name'];
      } else {
        $product_name = 'Unknown'; // Default value if product is not found
      }

      // Fetch category name
      $category_query = "SELECT name FROM categories WHERE id = '{$category_id}' LIMIT 1";
      $category_result = $db->query($category_query);
      if($category_result && $db->num_rows($category_result) > 0) {
        $category = $db->fetch_assoc($category_result);
        $category_name = $category['name'];
      } else {
        $category_name = 'Unknown'; // Default value if category is not found
      }

      // Insert into the invoices table
      $query  = "INSERT INTO invoices (";
      $query .= "invoice_date, vendor_id, vendor_name, product_name, quantity, contact_number, buying_price, category, date";
      $query .= ") VALUES (";
      $query .= " '{$invoice_date}', '{$vendor_id}', '{$vendor_name}', '{$product_name}', '{$quantity}', '{$contact_number}', '{$buying_price}', '{$category_name}', '{$date}'";
      $query .= ")";
      
      if($db->query($query)){
        $session->msg('s', "Invoice added successfully.");
        redirect('add_invoice.php', false);
      } else {
        $session->msg('d', "Sorry, the invoice could not be added.");
        redirect('invoice.php', false);
      }
    } else {
      $session->msg('d', $errors);
      redirect('add_invoice.php', false);
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
  <div class="col-md-8">
      <div class="panel panel-default">
        <div class="panel-heading">
          <strong>
            <span class="glyphicon glyphicon-file"></span>
            <span>Add New Invoice</span>
         </strong>
        </div>
        <div class="panel-body">
         <div class="col-md-12">
          <form method="post" action="add_invoice.php" class="clearfix">
              
              <!-- Date Field -->
              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-calendar"></i>
                  </span>
                  <input type="date" class="form-control" name="invoice-date" required>
               </div>
              </div>
              
              <!-- Vendor Name Field -->
              <div class="form-group">
                <div class="row">
                  <div class="col-md-6">
                    <select class="form-control" name="invoice-vendor" id="invoice-vendor" required>
                      <option value="">Select Vendor</option>
                      <?php foreach ($all_vendors as $vendor): ?>
                        <option value="<?php echo (int)$vendor['id'] ?>">
                          <?php echo $vendor['name'] ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>

                  <!-- Vendor ID Field (Automatically populated) -->
                  <div class="col-md-3">
                    <input type="text" class="form-control" name="invoice-vendor-id" id="invoice-vendor-id" readonly placeholder="Vendor ID">
                  </div>

                  <!-- Vendor Phone Field (Automatically populated) -->
                  <div class="col-md-3">
                    <input type="text" class="form-control" name="invoice-contact" id="invoice-contact" readonly placeholder="Vendor Phone Number">
                  </div>
                </div>
              </div>

              <!-- Product Field -->
              <div class="form-group">
                <div class="row">
                  <div class="col-md-6">
                    <select class="form-control" name="invoice-product" required>
                      <option value="">Select Product</option>
                    <?php foreach ($all_products as $product): ?>
                      <option value="<?php echo (int)$product['id'] ?>">
                        <?php echo $product['name'] ?></option>
                    <?php endforeach; ?>
                    </select>
                  </div>
                </div>
              </div>

              <!-- Quantity Field -->
              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                    <i class="glyphicon glyphicon-sort-by-order"></i>
                  </span>
                  <input type="number" class="form-control" name="invoice-quantity" placeholder="Quantity" required>
                </div>
              </div>

              <!-- Buying Price Field -->
              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                    <i class="glyphicon">&#8377;</i>
                  </span>
                  <input type="number" class="form-control" name="invoice-buying-price" placeholder="Buying Price" required>
                  <span class="input-group-addon">.00</span>
                </div>
              </div>

              <!-- Product Category Field -->
              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                    <i class="glyphicon glyphicon-th-list"></i>
                  </span>
                  <select class="form-control" name="invoice-product-category" required>
                    <option value="">Select Product Category</option>
                    <?php foreach ($all_categories as $cat): ?>
                      <option value="<?php echo (int)$cat['id'] ?>">
                        <?php echo $cat['name'] ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>

              <!-- Submit Button -->
              <button type="submit" name="add_invoice" class="btn btn-success">Add Invoice</button>
          </form>
         </div>
        </div>
      </div>
    </div>
  </div>

<?php include_once('layouts/footer.php'); ?>

<!-- AJAX to fetch vendor details -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript">
  $(document).ready(function(){
    $('#invoice-vendor').change(function(){
      var vendor_id = $(this).val();
      if(vendor_id != ''){
        $.ajax({
          url: 'fetch_vendor.php',
          method: 'POST',
          data: {vendor_id: vendor_id},
          dataType: 'json',
          success: function(data){
            $('#invoice-vendor-id').val(data.id);
            $('#invoice-contact').val(data.contact_number);
            // Setting the vendor name to the hidden field (if necessary)
            $('input[name="invoice-vendor-name"]').val(data.name);
          }
        });
      } else {
        $('#invoice-vendor-id').val('');
        $('#invoice-contact').val('');
      }
    });
  });
</script>
