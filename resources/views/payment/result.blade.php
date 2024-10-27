<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Payment Status</title>

    <link href="<?= url("/css/bootstrap.css") ?>" rel="stylesheet">
    <link href="<?= url("/css/payment.css") ?>" rel="stylesheet">
</head>
<body class="container">
<div class="container">
    <div class="py-5 text-center">
        <img class="d-block mx-auto mb-4" src="<?= url("/images/success-status.jpg") ?>"  alt="">
        <h2 class="text-green font-weight-bold">支付成功</h2>
        <h2 class="text-green font-weight-bold">Payment Successful</h2>
        <p class="lead font-weight-bold">
            Thank you! Your payment has been received
        </p>

        <section class="container payment-bg">
            <h5>Payment Details</h5>
            <div class="row px-5">
                <hr class="dash-line"></hr>
            </div>
            <div class="row px-5">
                <div class="col-6 text-left">
                    Order Id:
                </div>
                <div class="col-6 text-right">
                    <?= $transactionId ?>
                </div>
            </div>
            <br>
            <div class="row px-5">
                <div class="col-6 text-left">
                    Total Amount:
                </div>
                <div class="col-6 text-right">
                    ₱ <?= $transactionAmount ?> PHP
                </div>
            </div>
        </section>
    </div>
</div>
</body>

