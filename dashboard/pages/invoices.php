<?php
session_start();
if(!defined('ROOT_DIR')) {
    $arr = explode(DIRECTORY_SEPARATOR, __DIR__);
    $arr = array_slice($arr, 0, count($arr) - 2);
    define("ROOT_DIR", implode(DIRECTORY_SEPARATOR, $arr));
}
if(!isset($GLOBALS['WEBSITE_VARS'])) {
    (require_once (ROOT_DIR . DIRECTORY_SEPARATOR . 'site_variables.php')) or die("Variables file not found");
}
(include relativePathSystem(ABSOLUTE_PATHS['DASHBOARD_HEADERS'])) or die("Header related file not found");
global $user_obj;
$errorMsg = null;
$form_url = getURI();

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    // pay now or interrupt
}

$social_posts_array = CONNECTION->getSingleUserPosts($user_obj->id);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Rescue Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="<?php echo b5_theme_link(); ?>">
    <link rel="stylesheet" href="../styles.css">

</head>
<body style="background-image: none">
<?php
(include_once (relativePathSystem(ABSOLUTE_PATHS['DASHBOARD_DIR']) . 'pages' . DIRECTORY_SEPARATOR . 'menu.php')) or die("Failed to load component");
try {
    $invoices_list = CONNECTION->getUserInvoices($user_obj->customer->id);

} catch (Exception $e) {
}
?>

<div class="card m-3 p-3">
    <div class="row h-100">
        <div class="col">
                    <?php
                    if(!empty($invoices_list)) {
                        $i = 0;
                        foreach ($invoices_list as $invoice) {
                            $i++;//discount, total_expense AS expenses, net_product AS net, gross_income AS gross, status FROM financial_statements WHERE customer_id = ? ORDER BY start';
                            $discount = $invoice['discount'] > 0 ? $invoice['discount'] * $invoice['gross'] : 0;
                            $total_gross = $invoice['gross'] - $invoice['payment'] - $discount;
echo <<< ENDL_
            <h3>{$i}# Statement</h3>
            <table class="table table-borderless table-striped">
                <tr>
                    <td colspan="1"><strong>Progress: </strong> {$invoice['status']}</td>
                    <td colspan="2"><strong>Payment $: </strong> {$invoice['payment']}</td>
                </tr>
                <tr>
                    <td><strong>Start Date: </strong>{$invoice['start']}</td>
                    <td><strong>End Date: </strong>{$invoice['end']}</td>
                    <td><strong>Delivery: </strong>{$invoice['issued']}</td>
                </tr>
                <tr>
                    <td colspan="3"><strong>Rescue Address: </strong>{$invoice['rescue_address']}</td>
                </tr>
                <tr>
                    <td><strong>Advance Payment: </strong>{$invoice['advance']}</td>
                    <td><strong>Discount %: </strong>{$invoice['discount']}</td>
                    <td><strong>Delivery Date: </strong>{$invoice['issued']}</td>
                </tr>
                <tr>
                    <td><strong>Total Expenses: </strong>{$invoice['expenses']}</td>
                    <td><strong>Net: </strong>{$invoice['net']}</td>
                    <td><strong>Gross: </strong>{$invoice['gross']}</td>
                </tr>
                <tr>
                    <td colspan="3"><strong>Total Account: </strong>{$total_gross}</td>
                </tr>
            </table>
            
            
            <table class='table table-striped table-bordered'>
                <thead>
                <tr>
                    <th>#</th>
                    <th>Date</th>
                    <th>Part Description</th>
                    <th>Part Expense</th>
                    <th>Work Description</th>
                    <th>Additional Info</th>
                    <th>Sub Total</th>
                </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{$i}</td>
                        <td>{$invoice['accounts']['date']}</td>
                        <td>{$invoice['accounts']['vehicle_parts_desc']}</td>
                        <td>{$invoice['accounts']['parts_expense']}</td>
                        <td>{$invoice['accounts']['vehicle_service_desc']}</td>
                        <td>{$invoice['accounts']['notes']}</td>
                        <td>{$invoice['accounts']['sub_total']}</td>
                    </tr>
                </tbody>
            </table>
            <form method="POST">
                <table class='table table-striped table-bordered mb-5'>
                <tr>
                    <td colspan="3">Options</td>
                </tr>
                <tr>
                    <td colspan="3">
                        <button name="interruptButton" class="btn btn-warning"><i class="fa-solid fa-hand"></i> Interrupt Repairs</button>
                        <button name="paymentButton" class="btn btn-success"><i class="fa-regular fa-credit-card"></i> Pay Now</button>
                    </td>
                </tr>
                </table>
            </form>
            <hr class="m-5">
ENDL_;
                        }   // end of statements' foreach loop
                    }
                    else {
                        echo "<h3>No Invoice found</h3>";
                    }
echo <<< ENDL_

ENDL_;
?>
        </div>
    </div>
</div>

<?php if(isset($errorMsg)) {
    echo <<< ENDL_
    <div class="col-md-8 mx-auto my-2 fixed-bottom alert alert-info alert-dismissible fadein show" role="alert" style="z-index: 99999; position: fixed;">
        <strong>{$errorMsg}</strong> 
        <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
</div>
ENDL_;
}
?>
<script href="<?php echo relativePath(ROOT_DIR . DIRECTORY_SEPARATOR . 'dashboard' . DIRECTORY_SEPARATOR . 'scripts.js'); ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>