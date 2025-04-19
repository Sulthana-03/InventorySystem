<?php
$page_title = 'Edit Vendor';
require_once('includes/load.php');

// Check if the user has permission to view this page
page_require_level(1);

// Get the vendor ID from the URL
if (isset($_GET['id'])) {
    $vendor_id = (int)$_GET['id'];
    
    // Fetch vendor data from the database
    $vendor = find_by_id('vendors', $vendor_id);
    
    if (!$vendor) {
        $session->msg('d', "Vendor not found.");
        redirect('vendor.php', false);
    }
} else {
    $session->msg('d', "Missing vendor ID.");
    redirect('vendor.php', false);
}

// Update vendor data
if (isset($_POST['update_vendor'])) {
    $req_fields = array('vendor-name', 'vendor-contact');
    validate_fields($req_fields);

    if (empty($errors)) {
        $vendor_name    = remove_junk($db->escape($_POST['vendor-name']));
        $contact_number = remove_junk($db->escape($_POST['vendor-contact']));
        $date_updated   = make_date(); // Get current date/time
        
        // Update vendor in the database
        $query = "UPDATE vendors SET name = '{$vendor_name}', contact_number = '{$contact_number}', date_added = '{$date_updated}' WHERE id = {$vendor_id}";

        if ($db->query($query)) {
            $session->msg('s', "Vendor updated successfully.");
            redirect('vendor.php', false);
        } else {
            $session->msg('d', "Sorry, failed to update vendor.");
            redirect('vendor.php', false);
        }
    } else {
        $session->msg('d', $errors);
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

<!-- Edit Vendor Form -->
<div class="row">
    <div class="col-md-8">
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong>
                    <span class="glyphicon glyphicon-edit"></span>
                    <span>Edit Vendor</span>
                </strong>
            </div>
            <div class="panel-body">
                <form method="post" action="edit_vendor.php?id=<?php echo $vendor['id']; ?>" class="clearfix">
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="glyphicon glyphicon-user"></i>
                            </span>
                            <input type="text" class="form-control" name="vendor-name" value="<?php echo $vendor['name']; ?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="glyphicon glyphicon-phone"></i>
                            </span>
                            <input type="text" class="form-control" name="vendor-contact" value="<?php echo $vendor['contact_number']; ?>" required>
                        </div>
                    </div>
                    <button type="submit" name="update_vendor" class="btn btn-primary">Update Vendor</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>
