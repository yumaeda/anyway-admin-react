<?php
$curDirPath = dirname(__FILE__);
require_once("$curDirPath/send_common.php");

$result  = mysqli_query($dbc, "CALL get_order_contents('$orderId')");
if ($result !== FALSE) {
    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $deliverTo = $row['name'];
        if ($deliverTo !== '店頭引き取り') {
            $cartContents    = $row['contents'];
            $trackingId      = $row['transaction_id'];
            $trackingId2     = $row['transaction_id2'];
            $postCode        = $row['post_code'];
            $prefecture      = $row['prefecture'];
            $shippingAddress = $row['address'];
            $phoneNumber     = $row['phone'];
            $deliveryCompany = $row['delivery_company'];
            $deliveryDate    = $row['delivery_date'];
            $deliveryTime    = $row['delivery_time'];
            $name            = $row['customer_name'];

            // Send a shipping confirmation email.
            // ---------------------------------
            $email        = $row['customer_email'];
            $subject      = 'Anyway-Grapes: 商品発送のお知らせ';
            $orderMail    = 'order@anyway-grapes.jp';
            $archiveEmail = 'archive@anyway-grapes.jp';

            require_once("$curDirPath/../../../mails/text/shipping_confirmation_mail_body.php");
            require(E_MAIL);
            sendMailAsPlainText(
                $email,
                "$name 様",
                $orderMail,
                $archiveEmail,
                $subject,
                $textMessage
            );
        }
    }

    mysqli_free_result($result);
}

mysqli_close($dbc);

?>
