<div style="float: left; width: 100%;  border-bottom: 1px solid gray; margin-bottom: 15px;">
    <div style="float: left; width: 30%; display: inline;"><p style="font-size: 24px; font-weight: bold;"><?php echo $this->site_setting->site_name; ?><sub style="color: lightgrey; margin-left: 10px;">Invoice</sub></p></div>
    <div style="float: left; width: 70%; text-align: right; display: inline-block; font-weight: bold;">Date: <?php echo date('d M, Y'); ?></div>
</div>
<table style="float: left;width: 100%;">
    <tr style="float:left; width: 100%;">
        <td style="font-size: 13px; line-height: 18px;">
            From <br>
            <strong>Admin, Office.</strong><br>
            <?php echo str_replace(",", ", <br />", $admin_data->Address); ?><br>
            Phone: <?php echo $admin_data->MobileNo; ?><br>
            Email: <?php echo $admin_data->Email;    ?>
        </td>
        <td style="font-size: 13px; line-height: 18px; float: right;">
            To <br>
            <strong><?php echo ucfirst($client_data->Name); ?></strong><br>
            <?php echo str_replace(",", ", <br />", $client_data->Address); ?><br>
            Phone: <?php echo $client_data->MobileNo; ?><br>
            Email: <?php echo $client_data->Email;    ?>
        </td>
    </tr>
</table>
<hr>
<div style="font-size: 14px;font-weight: 600;">
    Name : <?php echo ucfirst($client_data->Name); ?>
</div>
<hr>
<table style="float: left;width: 100%; margin-top: 15px; text-align: center !important" border="0">
    <tr style="background: gainsboro; ">
        <th style="padding: 10px;">#</th>
        <th style="padding: 10px;">Order Date</th>
        <th style="padding: 10px;">Product</th>
        <th style="padding: 10px;">Category</th>
        <th style="padding: 10px;">Price</th>
        <th style="padding: 10px;">Qty</th>
        <th style="padding: 10px;">Subtotal</th>
    </tr>
    <?php
    $total ='';
    $i = 1;
    foreach ($order_data as $r) {
        $total = $total + $r->Total;
        ?>
        <tr style=" background: ghostwhite;">
            <td style="padding: 5px; text-align:center; font-size: 12px;"><?php echo $i; ?></td>
            <td style="padding: 5px; text-align:center; font-size: 12px;"><?php echo date('d M, Y',strtotime($r->CreatedDate)); ?></td>
            <td style="padding: 5px; text-align:center; font-size: 12px;"><?php echo $r->ProductName; ?></td>
            <td style="padding: 5px; text-align:center; font-size: 12px;"><?php echo $r->CategoryName; ?></td>
            <td style="padding: 5px; text-align:center; font-size: 12px;">Rs. <?php echo number_format($r->Price,2) ; ?></td>
            <td style="padding: 5px; text-align:center; font-size: 12px;"><?php echo $r->Qty; ?></td>
            <td style="padding: 5px; text-align:center; font-size: 12px;">Rs. <?php echo number_format($r->Total,2); ?></td>
        </tr>
        <?php
        $i++;
    }
    ?>
</table>
<table style="float: right;width: 100%; margin-top: 18px; text-align: center !important line-height: 20px;font-size: 12px;"  border="0">
    <tr>
        <td style="width:85%; text-align: right;">Subtotal :</td>
        <td>Rs. <?php echo number_format($total,2); ?></td>
    </tr>
    <tr >
        <td style="width:85%; text-align: right;">Tax (0.0%) :</td>
        <td>Rs. 0.00</td>
    </tr>
    <tr>
        <td style="width:85%; text-align: right;">Shipping :</td>
        <td>Rs. 0.00</td>
    </tr>
    <tr>
        <td style="width:83%; text-align: right;">Total :</td>
        <td><strong>Rs.  <?php echo number_format($total,2); ?></strong></td>
    </tr>
</table>






