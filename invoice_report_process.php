<?php
$page_title = 'Invoice Report';
$results = '';
require_once('includes/load.php');
// Check if the user has permission to view this page
page_require_level(3);

if (isset($_POST['submit'])) {
    $req_dates = array('start-date', 'end-date');
    validate_fields($req_dates);

    if (empty($errors)) :
        $start_date = remove_junk($db->escape($_POST['start-date']));
        $end_date = remove_junk($db->escape($_POST['end-date']));
        // Fetch invoice data based on the provided date range
        $results = find_invoice_by_dates($start_date, $end_date);
    else :
        $session->msg("d", $errors);
        redirect('invoice_report.php', false);
    endif;
} else {
    $session->msg("d", "Select dates");
    redirect('invoice_report.php', false);
}
?>

<!doctype html>
<html lang="en-US">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Invoice Report</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css"/>
    <style>
        @media print {
            html, body {
                font-size: 9.5pt;
                margin: 0;
                padding: 0;
            }
            .page-break {
                page-break-before: always;
                width: auto;
                margin: auto;
            }
            .print-btn {
                display: none; /* Hide the print button during printing */
            }
            .header, .footer {
                display: none; /* Hide header and footer */
            }
        }

        .page-break {
            width: 980px;
            margin: 0 auto;
        }

        .invoice-head {
            margin: 40px 0;
            text-align: center;
        }

        .invoice-head h1, .invoice-head strong {
            padding: 10px 20px;
            display: block;
        }

        .invoice-head h1 {
            margin: 0;
            border-bottom: 1px solid #212121;
        }

        table thead tr th {
            text-align: center;
            border: 1px solid #ededed;
        }

        table tbody tr td {
            vertical-align: middle;
        }

        .invoice-head, table.table thead tr th, table tbody tr td, table tfoot tr td {
            border: 1px solid #212121;
            white-space: nowrap;
        }

        .invoice-head h1, table thead tr th, table tfoot tr td {
            background-color: #f8f8f8;
        }

        tfoot {
            color: #000;
            text-transform: uppercase;
            font-weight: 500;
        }

        .print-btn {
            margin: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="print-btn">
            <button class="btn btn-primary" onclick="window.print()">Print Report</button>
        </div>
    </div>

    <?php if ($results): ?>
        <div class="page-break">
            <div class="invoice-head">
                <h1>Inventory Management System - Invoice Report</h1>
                <strong><?php if (isset($start_date)) { echo $start_date; } ?> To <?php if (isset($end_date)) { echo $end_date; } ?></strong>
            </div>
            <table class="table table-border">
                <thead>
                    <tr>
                        <th>Invoice Date</th>
                        <th>Vendor Name</th>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Buying Price</th>
                        <th>Category</th>
                        <th>Total Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results as $result): ?>
                        <tr>
                            <td><?php echo remove_junk($result['invoice_date']); ?></td>
                            <td><?php echo remove_junk(ucfirst($result['vendor_name'])); ?></td>
                            <td><?php echo remove_junk(ucfirst($result['product_name'])); ?></td>
                            <td class="text-right"><?php echo remove_junk($result['quantity']); ?></td>
                            <td class="text-right"><?php echo remove_junk($result['buying_price']); ?></td>
                            <td class="text-right"><?php echo remove_junk($result['category']); ?></td>
                            <td class="text-right"><?php echo number_format($result['quantity'] * $result['buying_price'], 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr class="text-right">
                        <td colspan="6">Grand Total</td>
                        <td>Rs. <?php echo number_format(total_invoice_price($results), 2); ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-danger">
            <strong>Sorry!</strong> No invoices found for the selected date range.
        </div>
    <?php endif; ?>

</body>
</html>

<?php if (isset($db)) { $db->db_disconnect(); } ?>
