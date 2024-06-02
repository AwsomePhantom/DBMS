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
(require_once (ROOT_DIR . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'BusinessAccounting.php')) or die("Accounting related file not found");
(include relativePathSystem(ABSOLUTE_PATHS['DASHBOARD_HEADERS'])) or die("Header related file not found");
global $user_obj;
$errorMsg = null;
$ACCOUNTS = new BusinessAccounting(CONNECTION->getPDOObject(), $user_obj->business->id); // money account settings
$fmt = new NumberFormatter( 'en_US', NumberFormatter::CURRENCY );   // money formatter

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(isset($_POST['statementSelectField']) && $_POST['statementSelectField'] > 0) {
        $GLOBALS['STATEMENT_ID'] = $_POST['statementSelectField'];
    }
    else if(isset($_POST['statement_id']) &&
        isset($_POST['customer_id']) &&
        isset($_POST['vehicle_parts_desc']) &&
        isset($_POST['parts_expense']) &&
        isset($_POST['vehicle_service_desc']) &&
        isset($_POST['service_revenue']) &&
        isset($_POST['notes'])) {
        $sub_total = $_POST['parts_expense'] + $_POST['service_revenue'];
        $res = $ACCOUNTS->addFinancialAccount([
                'statement_id'          => $_POST['statement_id'],
                'business_id'           => $user_obj->business->id,
                'customer_id'           => $_POST['customer_id'],
                'vehicle_parts_desc'    => $_POST['vehicle_parts_desc'],
                'parts_expense'         => $_POST['parts_expense'],
                'vehicle_service_desc'  => $_POST['vehicle_service_desc'],
                'service_revenue'       => $_POST['service_revenue'],
                'notes'                 => $_POST['notes'],
                'sub_total'             => $sub_total
        ]);
    }
    else if(isset($_POST['statement_id']) && isset($_POST['deletingEntryID'])) {
        $ACCOUNTS->removeFinancialAccount($_POST['statement_id'], $_POST['deletingEntryID']);
    }
    else if(isset($_POST['statement_id']) && isset($_POST['discountField'])) {
        $ACCOUNTS->setDiscount($_POST['discountField'], $_POST['statement_id']);
    }
    else if(isset($_POST['statement_id']) && isset($_POST['deliveryDateField'])) {
        $ACCOUNTS->setEstimateDeliveryDate($_POST['statement_id'], new DateTime($_POST['deliveryDateField']));
        if(isset($_POST['endRepairButton']) && $_POST['endRepairButton'] === 'true') {
            $ACCOUNTS->endRepairsAndDelivery($_POST['statement_id']);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
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
    $invoices_list = $ACCOUNTS->getBusinessStatementsCount();   // returns an array
}
catch (Exception $e) {
    $invoices_list = null;
    throw new Exception($e->getMessage());
}
?>

<div class="card m-3 p-3">
    <div class="row">
        <div class="col">
            <form method="POST">
                <strong>Interventions: </strong>
                <?php echo count($invoices_list); ?>
                <br>
                    <select id="statementSelectField" name="statementSelectField" class="form-control form-select form-select-lg mb-3" style="width: 400px">
                        <option selected disabled>-- Select an option --</option>
                        <?php
                            if($invoices_list) {
                                for($i = 0; $i < count($invoices_list); $i++) {
                                    echo "<option value='{$invoices_list[$i]['statement_id']}'>[" . $i + 1 . "] - {$invoices_list[$i]['date']} - {$invoices_list[$i]['name']} [{$invoices_list[$i]['status']}]</option>";
                                }
                                echo "<input id='statementSelectButton' type='submit' class='btn btn-primary' value='Select' disabled>";
                            }
                            else {
                                echo "<option disabled>-- Empty --</option>";
                            }
                        ?>
                    </select>
            </form>
        </div>
    </div>
</div>

<div class="card m-3 p-3">
    <div class="row h-100">
        <div class="col">
            <?php
            $i = $GLOBALS['STATEMENT_ID'] ?? 0;
            if($i > 0) {
                    $statement = $ACCOUNTS->getBusinessStatement($i);
                    $statement['discount'] *= 100;
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
                    <td colspan="3"><strong>Total Account: </strong>{$fmt->formatCurrency($statement['gross'], 'USD')}</td>
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
                    <th></th>
                </tr>
                </thead>
                <tbody>
ENDL_;
                    $list_accounts = $ACCOUNTS->getFinancialAccounts($i);
                    $count = 0;
                    if(!empty($list_accounts))  // single line conditional
                    foreach($list_accounts as $entry) {
                        $count++;
echo <<< ENDL_
                    <tr class="text-end">
                        <td>{$count}</td>
                        <td>{$entry['date']}</td>
                        <td>{$entry['vehicle_parts_desc']}</td>
                        <td>{$fmt->formatCurrency($entry['parts_expense'], 'USD')}</td>
                        <td>{$entry['vehicle_service_desc']}</td>
                        <td>{$fmt->formatCurrency($entry['service_revenue'], 'USD')}</td>
                        <td>{$entry['notes']}</td>
                        <td>{$fmt->formatCurrency($entry['sub_total'], 'USD')}</td>
ENDL_;
                    if($statement['status'] === 'OPEN' && $entry['vehicle_service_desc'] !== 'START') {
                        echo '<td><input id="' . $entry['id'] . '" class="btn btn-outline-danger" onclick="deleteEntry(' . $i . '}, this.id);" data-bs-toggle="modal" data-bs-target="#deleteModal" type="button" value="Cancel"></td>';
                    }
                    else {
                        echo "<td></td>";
                    }
echo <<< ENDL_
                    </tr>
ENDL_;
                    }
                    if($statement['status'] === 'OPEN') {
echo <<< ENDL_
                    <tr class="table-info">
                        <form method="POST">
                            <input type="hidden" name="statement_id" value="{$i}">
                            <input type="hidden" name="customer_id" value="{$statement['customer_id']}">
                            <td><h4><span class="badge bg-secondary">New...</span></h4></td>
                            <td>Now</td>
                            <td><textarea name="vehicle_parts_desc" class="form-control" rows="1" placeholder="Wheel, Gears, Lights, ..."></textarea></td>
                            <td><input name="parts_expense" class="form-control" type="number" step="0.01" value="0.00" required></td>
                            <td><textarea name="vehicle_service_desc" class="form-control" type="text" rows="1" placeholder="Change, Fix, Turn, ..." required></textarea></td>
                            <td><input name="service_revenue" class="form-control" type="number" step="0.01" value="0.00" required></td>
                            <td><textarea name="notes" class="form-control" rows="1" placeholder="Additional notes..."></textarea></td>
                            <td colspan="2"><input name="addNewAccountButton" class="btn btn-block btn-primary" type="submit" value="Add Entry"></td>
                        </form>
                    </tr>
                </tbody>
            </table>
            
            <table class='table table-striped table-borderless mb-5'>
                <tr>
                    <td colspan="3">Options</td>
                </tr>
                <tr>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="statement_id" value="{$i}">
                            <label for="discountField">Apply discount {0.05 for 5%}<input name="discountField" class="form-control" type="number" step="0.01" min="0" max="1" value="0.00"></label>
                            <input type="submit" class="btn btn-info" value="Apply Discount">
                        </form>
                    </td>
                    <td colspan="2">
                        <form method="POST">
                            <input type="hidden" name="statement_id" value="{$i}">
                            <label for="deliveryDateField">Estimated Delivery: <input name="deliveryDateField" class="form-control" type="date" required></label>
                            <input type="submit" class="btn btn-info" value="Set Date">
                            <!--<button name="interruptButton" class="btn btn-warning"><i class="fa-solid fa-hand"></i> Interrupt Repairs</button>-->
                            <label for="endRepairButton">Close accounts<br><button name="endRepairButton" class="btn btn-lg btn-dark" value="true"><i class="fa-regular fa-credit-card"></i> End Repair</button></label>
                        </form>
                    </td>
                </tr>
            </table>
            <hr class="m-5">
ENDL_;
                }
            }
            /**********************************/
            // Add business to business repair and delivery date
            // Add new page for closing accounts and delivery date selection
            // Add Offer service option in the post page
            else {
                echo "<h3>No data found</h3>";
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

<!-- Modal for deleting entries -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="mdalMsg">Are you sure you want to delete this entry?</p>
            </div>
            <div class="modal-footer">
                <form method="POST" name="modalForm">
                    <input type="hidden" id="deletingEntryID" name="deletingEntryID" value="">
                    <input type="hidden" id="statement_id" name="statement_id" value="">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    let i = <?php echo $GLOBALS['STATEMENT_ID'] ?? 0; ?>;
    let x = document.getElementById('statementSelectField');
    let y = document.getElementById('statementSelectButton');
    x.addEventListener('change', (e)=> {
        y.disabled = e.target.index <= 0 || e.target.index === i;
    });
    <?php if(isset($_POST['statementSelectField'])) echo "x.selectedIndex = " . $_POST['statementSelectField']; ?>

    function deleteEntry(statement_id, id) {
        if(statement_id <= 0) return;
        document.getElementById('statement_id').setAttribute('value', statement_id);
        document.getElementById('deletingEntryID').setAttribute('value', id);
    }

</script>
<script href="<?php echo relativePath(ROOT_DIR . DIRECTORY_SEPARATOR . 'dashboard' . DIRECTORY_SEPARATOR . 'scripts.js'); ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>