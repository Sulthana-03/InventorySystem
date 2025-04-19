<?php
  $page_title = 'Delete Invoice';
  require_once('includes/load.php');
  page_require_level(2);  // Ensure the user has the correct permission level

  // Check if the ID parameter is provided
  if (isset($_GET['id']) && !empty($_GET['id'])) {
    $invoice_id = (int)$_GET['id'];

    // Find the invoice from the database
    $invoice = find_by_id('invoices', $invoice_id);

    if ($invoice) {
        // Delete the invoice from the database
        $query = "DELETE FROM invoices WHERE id = '{$invoice_id}' LIMIT 1";
        $result = $db->query($query);

        if ($result && $db->affected_rows() === 1) {
            // Successful deletion
            $session->msg('s', "Invoice deleted successfully.");
            redirect('invoice.php', false);  // Redirect to the invoices list page
        } else {
            // Deletion failed
            $session->msg('d', 'Sorry, failed to delete the invoice.');
            redirect('invoice.php', false);  // Redirect back to the invoices list page
        }
    } else {
        // Invoice not found
        $session->msg('d', 'Invoice not found.');
        redirect('invoice.php', false);
    }
  } else {
      // No ID provided
      $session->msg('d', 'No invoice ID provided.');
      redirect('invoice.php', false);
  }
?>
