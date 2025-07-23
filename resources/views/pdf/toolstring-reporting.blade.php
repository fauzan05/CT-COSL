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

        /* FIXED WIDTH UNTUK SETIAP KOLOM */
        table {
            border-collapse: collapse;
            width: 100%;
            table-layout: fixed; /* Penting untuk fixed width */
        }

        /* Kolom 1: Image */
        th:nth-child(1), td:nth-child(1) {
            width: 120px;
            min-width: 120px;
            max-width: 120px;
        }

        /* Kolom 2: Description */
        th:nth-child(2), td:nth-child(2) {
            width: 200px;
            min-width: 200px;
            max-width: 200px;
        }

        /* Kolom 3: OD */
        th:nth-child(3), td:nth-child(3) {
            width: 80px;
            min-width: 80px;
            max-width: 80px;
        }

        /* Kolom 4: ID */
        th:nth-child(4), td:nth-child(4) {
            width: 80px;
            min-width: 80px;
            max-width: 80px;
        }

        /* Kolom 5: Top Connection */
        th:nth-child(5), td:nth-child(5) {
            width: 120px;
            min-width: 120px;
            max-width: 120px;
        }

        /* Kolom 6: Bottom Connection */
        th:nth-child(6), td:nth-child(6) {
            width: 120px;
            min-width: 120px;
            max-width: 120px;
        }

        /* Kolom 7: Length */
        th:nth-child(7), td:nth-child(7) {
            width: 100px;
            min-width: 100px;
            max-width: 100px;
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

        /* Image styling - simple dan straightforward */
        img {
            width: 110px; /* Sedikit lebih kecil dari kolom 120px */
            height: auto;
            display: block;
            margin: 0 auto;
            object-fit: contain;
        }

        /* PERBAIKAN UNTUK TD YANG BERISI GAMBAR - Style seperti mPDF */
        td:first-child {
            width: 120px; /* Sesuaikan dengan kebutuhan */
            min-width: 120px;
            padding: 0 !important;
            margin: 0 !important;
            vertical-align: middle !important; /* Middle seperti di contoh mPDF */
            text-align: center !important;
        }

        /* ALTERNATIVE: Style mPDF approach untuk image cell */
        .image-cell {
            width: 120px;
            min-width: 120px;
            padding: 2px !important; /* Sedikit padding seperti mPDF */
            margin: 0 !important;
            border: 1px solid #ccc;
            vertical-align: middle !important; /* Middle alignment */
            text-align: center !important;
            height: auto;
        }

        .image-cell img {
            width: 100px; /* Fixed size seperti mPDF */
            height: auto;
            display: block;
            margin: 0 auto;
            padding: 0;
            vertical-align: middle;
            border: none;
            outline: none;
            object-fit: contain; /* Menjaga aspect ratio */
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

        /* TAMBAHAN: Reset untuk menghilangkan default spacing */
        tbody tr {
            line-height: normal;
        }

        tbody td:not(:first-child) {
            padding: 5px;
            line-height: normal;
            font-size: 12px;
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
                        <td style="text-align:left; font-size: 18px; font-weight: bold;">{{ $reportingHistory->title }}
                        </td>
                    </tr>
                    <tr>
                        <td style="width:80px; white-space:nowrap; padding-right:5px;"><strong>CLIENT</strong></td>
                        <td style="text-align:left;">{{ $reportingHistory->client }}</td>
                    </tr>
                    <tr>
                        <td style="width:80px; white-space:nowrap; padding-right:5px;"><strong>WELL</strong></td>
                        <td style="text-align:left;">{{ $reportingHistory->well }}</td>
                    </tr>
                    <tr>
                        <td style="width:80px; white-space:nowrap; padding-right:5px;"><strong>DATE</strong></td>
                        <td style="text-align:left;">{{ $formattedDate }}</td>
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
            @foreach ($components as $component)
                @php
                    $length = $component['dimension']['length']['value'] ?? 0;
                    $total_length += $length;
                @endphp
                <tr>
                    <!-- Simple image cell -->
                    <td>
                        @if ($component['image_url'])
                            <img src="{{ asset($component['image_url']) }}" alt="Component Image">
                        @endif
                    </td>
                    <td>{{ $component['item_name'] }}</td>
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