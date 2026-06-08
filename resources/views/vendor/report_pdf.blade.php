<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>NaturaWed Finansial Report - {{ $vendorProfile->business_name ?? 'Studio' }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #1a1a1a;
            font-size: 12px;
            line-height: 1.5;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-b: 2px solid #2d3e2d;
            padding-bottom: 15px;
        }
        .header h1 {
            font-size: 24px;
            color: #2d3e2d;
            margin: 0 0 5px 0;
            font-weight: normal;
        }
        .header p {
            margin: 0;
            color: #666;
            font-size: 11px;
        }
        .meta-info {
            margin-bottom: 25px;
            width: 100%;
        }
        .meta-info td {
            padding: 2px 0;
        }
        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .report-table th {
            background-color: #2d3e2d;
            color: #ffffff;
            text-align: left;
            padding: 10px;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .report-table td {
            padding: 10px;
            border-bottom: 1px solid #e0e0e0;
        }
        .status-paid {
            color: #2d4a22;
            font-weight: bold;
        }
        .summary-box {
            width: 40%;
            margin-left: auto;
            border-top: 2px solid #2d3e2d;
            padding-top: 10px;
        }
        .summary-row {
            width: 100%;
            margin-bottom: 5px;
        }
        .summary-row td {
            padding: 4px 0;
        }
        .summary-total {
            font-size: 14px;
            font-weight: bold;
            color: #2d3e2d;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>NATURAWED</h1>
        <p>Bespoke Sustainable Wedding Curation Platform</p>
        <p style="margin-top: 5px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px;">Studio Financial Report</p>
    </div>

    <table class="meta-info">
        <tr>
            <td style="width: 15%; font-weight: bold;">Studio Name</td>
            <td style="width: 45%;">: {{ Auth::user()->name }}</td>
            <td style="width: 15%; font-weight: bold;">Export Date</td>
            <td style="width: 25%;">: {{ \Carbon\Carbon::now()->format('d M Y H:i') }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Address</td>
            <td>: {{ $vendorProfile->address ?? '-' }}</td>
            <td style="font-weight: bold;">Period</td>
            <td>: Year {{ \Carbon\Carbon::now()->year }}</td>
        </tr>
    </table>

    <table class="report-table">
        <thead>
            <tr>
                <th style="width: 12%;">ID Booking</th>
                <th style="width: 18%;">Event Date</th>
                <th style="width: 20%;">Customer Name</th>
                <th style="width: 20%;">Selected Package</th>
                <th style="width: 15%;">Status</th>
                <th style="width: 15%; text-align: right;">Amount</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $tx)
                <tr>
                    <td>#BKG-{{ $tx->booking_id }}</td>
                    <td>{{ \Carbon\Carbon::parse($tx->event_date)->format('d M Y') }}</td>
                    <td>{{ $tx->customer_name ?? 'NaturaWed Client' }}</td>
                    <td>{{ $tx->package_name }}</td>
                    <td class="status-paid">{{ strtoupper($tx->payment_status) }}</td>
                    <td style="text-align: right;">Rp {{ number_format($tx->amount, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: #999; padding: 30px;">No success transactions recorded in this period.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="summary-box">
        <table style="width: 100%;">
            <tr>
                <td style="width: 60%; color: #666;">Total Successful Events</td>
                <td style="width: 40%; text-align: right; font-weight: bold;">{{ $totalEvents }}</td>
            </tr>
            <tr class="summary-total">
                <td style="padding-top: 10px; border-top: 1px dashed #ccc;">Gross Revenue</td>
                <td style="padding-top: 10px; border-top: 1px dashed #ccc; text-align: right;">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

</body>
</html>