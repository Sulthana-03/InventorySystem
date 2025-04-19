<?php
  $page_title = 'All Invoices';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);

  // Search logic
  $search_term = '';
  if (isset($_GET['search'])) {
      $search_term = $_GET['search'];
      // Sanitize the search input
      $search_term = remove_junk($search_term);
  }

  // Modify the query to include search functionality
  if (!empty($search_term)) {
      $invoices = join_invoice_tablee($search_term);  // Pass search term to the function
  } else {
      $invoices = join_invoice_tablee();  // If no search term, fetch all invoices
  }
?>

<?php include_once('layouts/header.php'); ?>

<div class="row">
    <div class="col-md-12">
        <?php echo display_msg($msg); ?>
    </div>

    <!-- Search Form -->
    <div class="col-md-12">
        <form method="get" action="invoice.php" class="form-inline">
            <div class="form-group">
                <label for="search">Search:</label>
                <input type="text" name="search" id="search" class="form-control" placeholder="Search by Invoice Date, Vendor Name, or Vendor ID" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
            </div>
            <button type="submit" class="btn btn-primary">Search</button>
        </form>
    </div>

    <!-- Invoices Table -->
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <div class="pull-right">
                    <a href="add_invoice.php" class="btn btn-primary">Add New</a>
                </div>
            </div>
            <div class="panel-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 50px;">S.no</th>
                            <th class="text-center" style="width: 18%;">Vendor Name</th>
                            <th class="text-center" style="width: 10%;">Vendor ID</th>
                            <th class="text-center" style="width: 20%;">Product Name</th>
                            <th class="text-center" style="width: 10%;">Quantity</th>
                            <th class="text-center" style="width: 10%;">Buying Price</th>
                            <th class="text-center" style="width: 10%;">Category</th>
                            <th class="text-center" style="width: 10%;">Invoice Date</th>
                            <th class="text-center" style="width: 100px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($invoices as $invoice): ?>
                        <tr>
                            <td class="text-center"><?php echo count_id(); ?></td>
                            <td> <?php echo remove_junk($invoice['vendor_name']); ?></td>
                            <td class="text-center"> <?php echo remove_junk($invoice['vendor_id']); ?></td>
                            <td class="text-center"> <?php echo remove_junk($invoice['product_name']); ?></td>
                            <td class="text-center"> <?php echo remove_junk($invoice['quantity']); ?></td>
                            <td class="text-center"> <?php echo remove_junk($invoice['buying_price']); ?></td>
                            <td class="text-center"> <?php echo remove_junk($invoice['category']); ?></td>
                            <td class="text-center"> <?php echo read_date($invoice['invoice_date']); ?></td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="edit_invoice.php?id=<?php echo (int)$invoice['id'];?>" class="btn btn-info btn-xs" title="Edit" data-toggle="tooltip">
                                        <span class="glyphicon glyphicon-edit"></span>
                                    </a>
                                    <a href="delete_invoice.php?id=<?php echo (int)$invoice['id'];?>" class="btn btn-danger btn-xs" title="Delete" data-toggle="tooltip">
                                        <span class="glyphicon glyphicon-trash"></span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>
