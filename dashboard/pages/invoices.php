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
(require_once (ROOT_DIR . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'CustomerAccounting.php')) or die("Accounting related file not found");
global $user_obj;
$errorMsg = null;
$form_url = getURI();
$ACCOUNTS = new CustomerAccounting(CONNECTION->getPDOObject(), $user_obj->customer->id);    // money account settings
$fmt = new NumberFormatter( 'en_US', NumberFormatter::CURRENCY );   // money formatter

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(isset($_POST['statement_id']) && isset($_POST['endRepairsButton']) && $_POST['endRepairsButton'] === 'true') {
        $ACCOUNTS->interruptRepairs($_POST['statement_id']);
    }
    else if(isset($_POST['statement_id']) && isset($_POST['payNowAdvanceButton']) && isset($_POST['maximum_amount'])) {
        if($_POST['payNowAdvanceButton'] > $_POST['maximum_amount']) return;
        $ACCOUNTS->payNowAdvance($_POST['statement_id'], (float)$_POST['payNowAdvanceButton']);
    }
    else if(isset($_POST['statement_id']) && isset($_POST['payNowEndButton']) && isset($_POST['maximum_amount'])) {
        if($_POST['payNowEndButton'] > $_POST['maximum_amount']) return;
        $ACCOUNTS->payNowEnd($_POST['statement_id'], (float)$_POST['payNowEndButton']);
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoices</title>
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
    $statement_list = $ACCOUNTS->getFinancialStatements($user_obj->customer->id);
} catch (Exception $e) {
}
?>

<div class="card m-3 p-3">
    <div class="row h-100">
        <div class="col">
                    <?php
                    if(!empty($statement_list)) {
                        $i = 0;
                        foreach ($statement_list as $stmt) {
                            $i++;//discount, total_expense AS expenses, net_product AS net, gross_income AS gross, status FROM financial_statements WHERE customer_id = ? ORDER BY start';
                            $statement = $ACCOUNTS->getSingleFinancialStatement($stmt['id']);
                            $statement['discount'] *= 100;
                            $statement['payable'] = $statement['gross'] - $statement['payment'];

echo <<< ENDL_
                      <h3>{$i}# Statement</h3>
            <table class="table table-striped">
                <tr>
                    <td colspan="1"><strong>Progress: </strong> {$statement['status']}</td>
                    <td colspan="2"><strong>Payment: </strong> {$fmt->formatCurrency($statement['payment'], 'USD')}</td>
                </tr>
                <tr>
                    <td><strong>Start Date: </strong>{$statement['start']}</td>
                    <td><strong>End Date: </strong>{$statement['end']}</td>
                    <td><strong>Delivery Date: </strong>{$statement['delivery']}</td>
                </tr>
                <tr>
                    <td colspan="3"><strong>Rescue Address: </strong>{$statement['rescue_address']}</td>
                </tr>
                <tr>
                    <td><strong>Advance Payment: </strong>{$fmt->formatCurrency($statement['advance'], 'USD')}</td>
                    <td><strong>Discount: </strong>{$statement['discount']}%</td>
                    <td></td>
                </tr>
                <tr>
                    <td><strong>Total Expenses: </strong>{$fmt->formatCurrency($statement['expenses'], 'USD')}</td>
                    <td><strong>Net: </strong>{$fmt->formatCurrency($statement['net'], 'USD')}</td>
                    <td><strong>Gross: </strong>{$fmt->formatCurrency($statement['gross'], 'USD')}</td>
                </tr>
                <tr>
                    <td colspan="3"><strong>Total Account: </strong>{$fmt->formatCurrency($statement['payable'], 'USD')}</td>
                </tr>
            </table>
            
            <table class='table table-striped table-bordered'>
                <thead>
                <tr>
                    <th>#</th>
                    <th>Date</th>
                    <th>Parts' Description</th>
                    <th>Parts' Expense</th>
                    <th>Work Description</th>
                    <th>Service Revenue</th>
                    <th>Additional Info</th>
                    <th>Sub Total</th>
                </tr>
                </thead>
                <tbody>
ENDL_;
                            $stmt_accounts = $ACCOUNTS->getFinancialAccounts($stmt['id']);
                            $count = 0;
                            if(!empty($stmt_accounts))  // without braces for single statement
                            foreach($stmt_accounts as $account) {
                                $count++;
echo <<< ENDL_
                    <tr class="text-end">
                        <td>{$count}</td>
                        <td>{$account['date']}</td>
                        <td>{$account['vehicle_parts_desc']}</td>
                        <td>{$fmt->formatCurrency($account['parts_expense'], 'USD')}</td>
                        <td>{$account['vehicle_service_desc']}</td>
                        <td>{$fmt->formatCurrency($account['service_revenue'], 'USD')}</td>
                        <td>{$account['notes']}</td>
                        <td>{$fmt->formatCurrency($account['sub_total'], 'USD')}</td>
                    </tr>
ENDL_;
                            } // end of foreach for accounts
                                $amount = $statement['gross'] - $statement['payment'];
                                if($statement['status'] === 'OPEN' || $amount > 0) {
echo <<< ENDL_
                <table class='table table-striped table-bordered mb-5'>
                <tr>
                    <td colspan="3">Options</td>
                </tr>
                <tr>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="statement_id" value="{$stmt['id']}">
                            <input type="hidden" name="maximum_amount" value="{$amount}">
ENDL_;
                if($statement['status'] === 'OPEN') {
echo <<< ENDL_
                            <button name="endRepairsButton" class="btn btn-warning" value="true"><i class="fa-solid fa-hand"></i> Interrupt Repairs</button>
                        </form>
                    </td>
                    <td>
                        <form method="POST">
                        <input type="hidden" name="statement_id" value="{$stmt['id']}">
                        <input type="hidden" name="maximum_amount" value="{$amount}">
ENDL_;
                }
echo <<< ENDL_
                <div class="d-inline-grid">
                <input name="amountField" class="form-control" type="number" step="0.01" min="0.00" value="{$amount}" required>
ENDL_;
                if($statement['status'] === 'OPEN') {
                    echo '<button name="payNowAdvanceButton" class="btn btn-success float-start"><i class="fa-regular fa-credit-card"></i> Pay Now</button>&nbsp;';
                }
                else {
                    echo '<button name="payNowEndButton" class="btn btn-success"><i class="fa-regular fa-credit-card"></i> Pay Now</button>&nbsp;';
                }
echo <<< ENDL_
                </div>
                    </td>
                    <td colspan="2"></td>
                </tr>
                </table>
            </form>
ENDL_;
                                }
                        }   // end of statements' foreach loop
                    }
                    else {
                        echo "<h3>No Invoice found</h3>";
                    }
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