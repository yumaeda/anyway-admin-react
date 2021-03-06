<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit('Only POST is supported.');
}

$requestBody = json_decode(file_get_contents('php://input'));
if (!$requestBody || !isset($requestBody->orderId)) {
    exit('Invalid parameters.');
}

$curDirPath = dirname(__FILE__);
require_once("$curDirPath/../defines.php");
require("$curDirPath/../../../includes/config.inc.php");
require(MYSQL);

$orderId = mysqli_real_escape_string($dbc, $requestBody->orderId);
$result  = mysqli_query($dbc, "CALL get_order_contents('$orderId')");
if ($result !== FALSE)
{
    if (mysqli_num_rows($result) === 1)
    {
        $row       = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $email     = $row['customer_email'];
        $rgstrItem = explode(';', $row['contents']);
        $cItem     = count($rgstrItem);
        for ($i = 0; $i < $cItem; ++$i)
        {
            $rgstrToken = explode('#', $rgstrItem[$i]);
            if (count($rgstrToken) === 2)
            {
                $barcode = $rgstrToken[0];
                $qty     = $rgstrToken[1];

                prepareNextQuery($dbc);
                mysqli_query($dbc, "CALL add_purchased_wine('$orderId', '$email', '$barcode', $qty)");
            }
        }
    }

    mysqli_free_result($result);
}

mysqli_close($dbc);

?>
