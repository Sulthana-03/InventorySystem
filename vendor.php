<?php
$page_title = 'Manage Vendors';
require_once('includes/load.php');

// Check if the user has permission to view this page
page_require_level(1);

// Fetch all vendors from the database
$all_vendors = find_all('vendors');
if (!$all_vendors) {
    $all_vendors = []; // Ensure $all_vendors is an array, even if no records are found
}

// Add new vendor
if (isset($_POST['add_vendor'])) {
    $req_fields = array('vendor-name', 'vendor-contact');
    validate_fields($req_fields);

    if (empty($errors)) {
        $vendor_name    = remove_junk($db->escape($_POST['vendor-name']));
        $contact_number = remove_junk($db->escape($_POST['vendor-contact']));
        $date_added     = make_date();  // Function to get current date/time

        // Prepare the SQL query using query() method (no prepared statements here)
        $query = "INSERT INTO vendors (name, contact_number, date_added) VALUES ('{$vendor_name}', '{$contact_number}', '{$date_added}')";

        // Execute the query
        if ($db->query($query)) {
            $session->msg('s', "Vendor added successfully.");
            redirect('vendor.php', false);
        } else {
            // If execution fails, print the error
            echo "Error executing query: " . $db->error . "<br>";
            exit();  // Stop execution
        }
    } else {
        $session->msg('d', $errors);
        redirect('vendor.php', false);
    }
}

// Delete vendor
if (isset($_GET['delete_vendor'])) {
    $vendor_id = (int)$_GET['delete_vendor'];

    // Ensure the vendor ID is valid
    if ($vendor_id <= 0) {
        $session->msg('d', "Invalid vendor ID.");
        redirect('vendor.php', false);
    }

    // SQL query to delete vendor using query() method
    $query = "DELETE FROM vendors WHERE id = {$vendor_id}";
    if ($db->query($query)) {
        $session->msg('s', "Vendor deleted successfully.");
        redirect('vendor.php', false);
    } else {
        $session->msg('d', "Failed to delete vendor. Error: " . $db->error);
        redirect('vendor.php', false);
    }
}
?>

<?php include_once('layouts/header.php'); ?>

<div class="row">
    <div class="col-md-12">
        <?php echo display_msg($msg); ?>
    </div>
</div>

<!-- Vendor Management Form -->
<div class="row">
    <div class="col-md-8">
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong>
                    <span class="glyphicon glyphicon-plus"></span>
                    <span>Add New Vendor</span>
                </strong>
            </div>
            <div class="panel-body">
                <form method="post" action="vendor.php" class="clearfix">
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="glyphicon glyphicon-user"></i>
                            </span>
                            <input type="text" class="form-control" name="vendor-name" placeholder="Vendor Name" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="glyphicon glyphicon-phone"></i>
                            </span>
                            <input type="text" class="form-control" name="vendor-contact" placeholder="Contact Number" required>
                        </div>
                    </div>
                    <button type="submit" name="add_vendor" class="btn btn-success">Add Vendor</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Vendor Management Table -->
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong>
                    <span class="glyphicon glyphicon-list"></span>
                    <span>Manage Vendors</span>
                </strong>
            </div>
            <div class="panel-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Vendor Name</th>
                            <th>Contact Number</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($all_vendors)): ?>
                            <?php foreach ($all_vendors as $index => $vendor): ?>
                                <tr>
                                    <td><?php echo $index + 1; ?></td>
                                    <td><?php echo $vendor['name']; ?></td>
                                    <td><?php echo $vendor['contact_number']; ?></td>
                                    <td>
                                        <a href="edit_vendor.php?id=<?php echo $vendor['id']; ?>" class="btn btn-info btn-xs">Edit</a>
                                        <a href="vendor.php?delete_vendor=<?php echo $vendor['id']; ?>" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure you want to delete this vendor?');">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center">No vendors found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>
