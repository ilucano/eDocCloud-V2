<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
    </head>
    <body style="font-family: Arial, sans-serif;">
        <h3>Company Name:  {{ $company->company_name }}</h3>
        <h3>Total Data Usage: {{ Helpers::bytesToGigabytes($company->todate_data_usage) }}</h3>
        <h4>Last 12 Months Usage:</h4>
        <table>
                <tr>
                       <th>Month</th><th>Number of Files</th><th>Usage</th>
                </tr>

                 @foreach ($company->monthly_data_usage as $key => $dataUsage)
                                    <tr>
                                        <td>{{ $key }}</td>
                                        <td>{{ $company->monthly_number_of_files[$key] }}</td>
                                        <td class="text-right">{{ Helpers::bytesToMegabytes($dataUsage) }}</td>
                                    </tr>
                @endforeach

        </table>
    </body>
</html>
