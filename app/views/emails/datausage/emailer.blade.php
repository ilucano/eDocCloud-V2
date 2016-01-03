<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
    </head>
    <body style="font-family: Arial, sans-serif;">
        <p>Your monthly usage reports for {{ $month_name }} are as below</p>
        <p>
            <b> Company Name:</b>  {{ $company->company_name }}<br/>
            <b> Base Price ($):</b> {{ $usage->base_price }}<br/>
            <b> User Charges:</b> {{ $usage->user_charges }}<br/>
            <b> Storage Charges:</b> {{ $usage->storage_charges }}<br/>
            <b> Own Scan Charges:</b>  {{ $usage->own_scan_charges }}<br/>
            <b> Plan Scan Charges:</b>  {{ $usage->plan_scan_charges }}<br/>
            <b> Total Amount($):</b>  {{ $usage->current_charges }}<br/>
        </p>
    <br/>
    To view more details, kindly login to your eDocCloud account.
    <br/>
    <br/>
    eDocCloud Team
    </body>
</html>
