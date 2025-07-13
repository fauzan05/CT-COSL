<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Toolstring Reporting</title>
    <style>
        @page {
            margin: 20px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            table-layout: fixed;
        }

        th,
        td {
            border: 1px solid #ccc;
            word-wrap: break-word;
        }

        thead th {
            padding: 5px;
            text-align: left;
            background-color: #f9f9f9;
        }

        tbody td {
            padding: 0;
            text-align: center;
            vertical-align: middle;
        }

        .header-top {
            margin-bottom: 10px;
            border: none;
        }

        .header-top td {
            border: none;
            vertical-align: middle;
        }

        .title {
            font-size: 18px;
            font-weight: bold;
            text-align: left;
        }

        img {
            max-width: 100%;
            height: auto;
            display: block;
        }

        td:first-child,
        th:first-child {
            width: 100px;
        }

        .total-row td {
            padding-top: 10px;
            padding-bottom: 10px;
        }

        .header-info {
            margin-bottom: 20px;
            width: 40%;
        }

        .header-info td:first-child {
            width: 80px !important;
            max-width: 80px !important;
            white-space: nowrap;
            padding-right: 5px;
            text-align: left !important;
            border: none;
        }

        .header-info td:nth-child(2) {
            width: auto !important;
            text-align: left !important;
            padding-left: 0;
            border: none;
        }
    </style>
</head>

<body>
    <!-- HEADER TOP: TITLE & LOGO -->
    <table class="header-top">
        <tr>
            <td>
                <!-- HEADER INFO: CLIENT / WELL / DATE -->
                <table class="header-info">
                    <tr>
                        <td style="text-align:left; font-size: 18px; font-weight: bold;">{{ $reportingHistory->title }}</td>
                    </tr>
                    <tr>
                        <td style="width:80px; white-space:nowrap; padding-right:5px;"><strong>CLIENT</strong></td>
                        <td style="text-align:left;">{{$reportingHistory->client}}</td>
                    </tr>
                    <tr>
                        <td style="width:80px; white-space:nowrap; padding-right:5px;"><strong>WELL</strong></td>
                        <td style="text-align:left;">{{$reportingHistory->well}}</td>
                    </tr>
                    <tr>
                        <td style="width:80px; white-space:nowrap; padding-right:5px;"><strong>DATE</strong></td>
                        <td style="text-align:left;">{{$formattedDate}}</td>
                    </tr>
                </table>
            </td>
            <td style="text-align: right;">
                <img src="{{ $company_logo }}" style="width: 250px; padding-bottom: 20px;">
            </td>
        </tr>
    </table>

    <!-- MAIN TABLE -->
    <table>
        <thead>
            <tr>
                <th></th>
                <th>Description</th>
                <th>OD ({{ $odUnit }})</th>
                <th>ID ({{ $idUnit }})</th>
                <th>Top Connection</th>
                <th>Bottom Connection</th>
                <th>Length ({{ $lengthUnit }})</th>
            </tr>
        </thead>
        <tbody>
            @php $total_length = 0; @endphp
            @foreach($components as $component)
                @php
                    $length = $component['dimension']['length']['value'] ?? 0;
                    $total_length += $length;
                @endphp
                <tr>
                    <td>
                        @if ($component['image_base64'])
                            <img src="{{ $component['image_base64'] }}">
                        @endif
                    </td>
                    <td>{{ $component['description'] }}</td>
                    <td>
                        {{ $component['dimension']['outer_diameter']['value'] ?? 'N/A' }}
                    </td>
                    <td>
                        {{ $component['dimension']['inner_diameter']['value'] ?? 'N/A' }}
                    </td>
                    <td>{{ $component['thread_size']['top_connection'] ?? 'N/A' }}</td>
                    <td>{{ $component['thread_size']['bottom_connection'] ?? 'N/A' }}</td>
                    <td>
                        {{ $length ?? 'NA' }}
                    </td>
                </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="6" style="text-align: right; padding-right: 10px;"><strong>Total length =</strong></td>
                <td><strong>{{ $total_length }}</strong></td>
            </tr>
        </tbody>
        
    </table>
</body>

</html>
