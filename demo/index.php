<?php
/**
 * Just a demonstration.
 * DON'T USE THIS CODE IN PRODUCTION
 */

require '../vendor/autoload.php';

$result1 = 'fill credentials';
$result2 = 'fill credentials';

$id = $_REQUEST['id'];
$password = $_REQUEST['password'];
$card = $_REQUEST['card'];
$dateFromString = $_REQUEST['date_from'];
$dateToString = $_REQUEST['date_to'];

if ($id && $password) {
    $merchant = new \CyanoFresh\PrivatBankAPI\Merchant($id, $password);

    $result1 = $merchant->getBalanceInfo($card);
    $result2 = $merchant->getStatements($card, new DateTime($dateFromString), new DateTime($dateToString));
}
?>

<form action="" method="get">
    <div>
        <input type="text" name="id" placeholder="ID" value="<?= $id ?>">
    </div>
    <div>
        <input type="text" name="password" placeholder="password" value="<?= $password ?>">
    </div>
    <div>
        <input type="text" name="card" placeholder="card" value="<?= $card ?>">
    </div>
    <div>
        <input type="text" name="date_from" placeholder="date from (dd.mm.yyyy)" value="<?= $dateFromString ?>">
    </div>
    <div>
        <input type="text" name="date_to" placeholder="date to (dd.mm.yyyy)" value="<?= $dateToString ?>">
    </div>
    <button type="submit">Send</button>
</form>

<h2>balance:</h2>

<pre><?php print_r($result1) ?></pre>

<h2>statements:</h2>

<pre><?php print_r($result2) ?></pre>
