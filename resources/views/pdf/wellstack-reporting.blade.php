<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Well Stack Schematic</title>
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        body {
            margin: 0;
            padding: 20px;
            background-color: #ffffff;
            color: #1f2937;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 24px;
            background-color: #ffffff;
            border-radius: 12px;
        }

        .title {
            text-align: center;
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 24px;
            color: #0369a1;
            /* Biru muda yang elegant */
            letter-spacing: -0.5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
            border: 1px solid #e5e7eb;
        }

        th,
        td {
            padding: 12px 16px;
            font-size: 13px;
            border: 1px solid #e5e7eb;
            line-height: 1.5;
        }

        th {
            background-color: #0ea5e9;
            /* Biru muda untuk header */
            color: #ffffff;
            font-weight: 500;
            text-align: left;
            white-space: nowrap;
        }

        .header-section td {
            background-color: #f8fafc;
            padding: 12px 16px;
            font-size: 13px;
            color: #475569;
            border: none;
        }

        .data-row td {
            background-color: #ffffff;
        }

        .data-row:nth-child(even) td {
            background-color: #f9fafb;
        }

        .totals-section td {
            font-weight: 600;
            background-color: #f0f9ff;
            /* Light blue background */
            color: #0369a1;
        }

        .spacer-row td {
            border: none;
            height: 8px;
            background-color: transparent;
        }

        .footer {
            margin-top: 16px;
            text-align: right;
            font-size: 12px;
            color: #64748b;
        }

        /* Modern styling untuk angka */
        td:has(> number),
        td[align="right"] {
            font-feature-settings: "tnum";
            font-variant-numeric: tabular-nums;
            text-align: right;
        }

        /* Kolom width */
        .col-a {
            width: 8%;
        }

        .col-b {
            width: 15%;
        }

        .col-c {
            width: 15%;
        }

        .col-d {
            width: 8%;
        }

        .col-e {
            width: 8%;
        }

        .col-f {
            width: 8%;
        }

        .col-g {
            width: 10%;
        }

        .col-h {
            width: 10%;
        }

        .col-i {
            width: 5%;
        }

        .col-j {
            width: 5%;
        }

        .col-k {
            width: 5%;
        }

        .col-l {
            width: 5%;
        }

        .col-m {
            width: 5%;
        }

        .col-n {
            width: 3%;
        }

        /* Print-specific styles */
        @media print {
            body {
                padding: 0;
                background: #ffffff;
            }

            .container {
                padding: 0;
                box-shadow: none;
            }

            table {
                page-break-inside: auto;
            }

            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }

            thead {
                display: table-header-group;
            }

            tfoot {
                display: table-footer-group;
            }
        }

        .header-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 30px;
            background-color: #f8fafc;
            border-radius: 8px;
            padding: 20px;
            border: 1px solid #e5e7eb;
        }

        .header-item {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .header-label {
            color: #64748b;
            font-size: 12px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .header-value {
            color: #1e293b;
            font-size: 14px;
            font-weight: 500;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e5e7eb;
        }

        .company-logo {
            height: 50px;
            object-fit: contain;
        }

        .title {
            font-size: 24px;
            color: #0369a1;
            font-weight: 600;
            margin: 0;
        }

        /* Style untuk header teknikal */
        .technical-header {
            margin-bottom: 30px;
            border: 2px solid #0ea5e9;
            border-radius: 4px;
            background: #ffffff;
        }

        .technical-header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 40px;
            background: #0ea5e9;
            color: white;
        }

        .document-title {
            font-size: 20px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 0;
        }

        .company-logo {
            height: 40px;
        }

        .technical-info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0;
            border-top: 1px solid #e5e7eb;
        }

        .info-column {
            padding: 15px 20px;
        }

        .info-column:first-child {
            border-right: 1px solid #e5e7eb;
        }

        .info-row {
            display: flex;
            margin-bottom: 8px;
            align-items: center;
        }

        .info-label {
            width: 140px;
            color: #1e293b;
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .info-value {
            flex: 1;
            color: #0369a1;
            font-size: 13px;
            font-weight: 500;
            font-family: 'Consolas', monospace;
        }

        .technical-metadata {
            background: #f8fafc;
            padding: 10px 20px;
            border-top: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            color: #64748b;
            font-weight: 500;
        }

        .header-title-section {
            flex: 1;
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }

        .document-title {
            font-size: 20px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 0;
        }

        .company-logo {
            height: 40px;
            object-fit: contain;
        }

        td.col-n {
            padding-top: 0 !important;
            padding-bottom: 0 !important;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="technical-header">
            <!-- Header Top -->
            <table width="100%" border="0" cellspacing="0" cellpadding="0"
                style="background-color: #0ea5e9; color: white; border: 2px solid #0ea5e9; border-radius: 4px; border-collapse: collapse;">
                <tr>
                    <td
                        style="padding: 15px 20px; text-align: left; font-size: 20px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; border: none;">
                        Well Stack Schematic
                    </td>
                    <td style="padding: 15px 20px; text-align: right; border: none;">
                        <img src="{{ $company_logo }}" alt="Company Logo" style="height: 40px; object-fit: contain;">
                    </td>
                </tr>
            </table>

            <!-- Technical Info Grid -->
            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse; width: 100%; height: 100%;">
                <tr>
                  <!-- Kolom Kiri -->
                  <td valign="top" style="padding: 15px 20px; border-right: 1px solid #e5e7eb; width: 50%;">
                    <div class="info-row">
                      <span class="info-label">Client:</span>
                      <span class="info-value">{{ $reportingHistory->client }}</span>
                    </div>
                    <div class="info-row">
                      <span class="info-label">Field:</span>
                      <span class="info-value">{{ $reportingHistory->field }}</span>
                    </div>
                    <div class="info-row">
                      <span class="info-label">Well Name & No:</span>
                      <span class="info-value">{{ $reportingHistory->well_name_number }}</span>
                    </div>
                    <div class="info-row">
                      <span class="info-label">Min. Restriction:</span>
                      <span class="info-value">{{ $reportingHistory->min_restriction }}</span>
                    </div>
                    <div class="info-row">
                      <span class="info-label">KOP:</span>
                      <span class="info-value">{{ $reportingHistory->kop }}</span>
                    </div>
                    <div class="info-row">
                      <span class="info-label">Category:</span>
                      <span class="info-value">{{ $reportingHistory->category }}</span>
                    </div>
                  </td>
              
                  <!-- Kolom Kanan -->
                  <td valign="top" style="padding: 15px 20px; width: 50%;">
                    <div class="info-row">
                      <span class="info-label">BHP:</span>
                      <span class="info-value">{{ $reportingHistory->bhp }}</span>
                    </div>
                    <div class="info-row">
                      <span class="info-label">BHST:</span>
                      <span class="info-value">{{ $reportingHistory->bhst }}</span>
                    </div>
                    <div class="info-row">
                      <span class="info-label">S/O:</span>
                      <span class="info-value">{{ $reportingHistory->so }}</span>
                    </div>
                    <div class="info-row">
                      <span class="info-label">Supplier:</span>
                      <span class="info-value">{{ $reportingHistory->supplier }}</span>
                    </div>
                    <div class="info-row">
                      <span class="info-label">Date Drawn:</span>
                      <span class="info-value">{{ $reportingHistory->date_drawn->toDateString() ?? '-' }}</span>
                    </div>
                    <div class="info-row">
                      <span class="info-label">Drawn By:</span>
                      <span class="info-value">{{ $reportingHistory->drawn_by }}</span>
                    </div>
                  </td>
                </tr>
              </table>
              
        </div>
        <table>
            <!-- Table Header -->
            <tr>
                <th class="col-a">Item</th>
                <th class="col-b">Description</th>
                <th class="col-c">Serial Number</th>
                <th class="col-d">Height (ft)</th>
                <th class="col-e">Weight (lbs)</th>
                <th class="col-f">Pressure (psi)</th>
                <th class="col-g">BHI or 3rd Party</th>
                <th class="col-n">Image</th>
            </tr>
            <!-- Data rows -->
            @foreach ($components as $component)
                <tr class="data-row">
                    <td class="col-a">{{ $loop->iteration }}</td>
                    <td class="col-b">{{ $component['description'] }}</td>
                    <td class="col-c">{{ $component['serial_number'] }}</td>
                    <td class="col-d">{{ $component['height'] }}</td>
                    <td class="col-e">{{ $component['weight'] }}</td>
                    <td class="col-f">{{ $component['pressure_rating'] }}</td>
                    <td class="col-g">{{ $component['owner'] }}</td>
                    <td class="col-n">
                        @if (!empty($component['image_base64']))
                            <img src="{{ $component['image_base64'] }}"
                                style="width: 50px; height: 100%; object-fit: contain;">
                        @else
                            N/A
                        @endif
                    </td>
                </tr>
            @endforeach
            <!-- Calculation rows -->
            <tr class="totals-section">
                <td class="col-a">Distance from Lower Shear</td>
                <td class="col-b">{{ $distance_from_lower_shear }}</td>
                <td class="col-c">Total Height:</td>
                <td class="col-d">{{ $total_height }}</td>
                <td class="col-e">ft</td>
                <td class="col-f"></td>
                <td class="col-g"></td>
                <td class="col-n"></td>
            </tr>
            <tr class="totals-section">
                <td class="col-a">Distance from Upper Shear</td>
                <td class="col-b">{{ $distance_from_upper_shear }}</td>
                <td class="col-c">Total Weight:</td>
                <td class="col-d"></td>
                <td class="col-e">{{ $total_weight }}</td>
                <td class="col-f">TonUS</td>
                <td class="col-g"></td>
                <td class="col-n"></td>
            </tr>
            <tr class="totals-section">
                <td class="col-a"></td>
                <td class="col-b"></td>
                <td class="col-c">Min PSI:</td>
                <td class="col-d"></td>
                <td class="col-e"></td>
                <td class="col-f">{{ $min_psi }}</td>
                <td class="col-g">psi</td>
                <td class="col-n"></td>
            </tr>
            <tr class="totals-section">
                <td class="col-a"></td>
                <td class="col-b"></td>
                <td class="col-c"></td>
                <td class="col-d"></td>
                <td class="col-e"></td>
                <td class="col-f"></td>
                <td class="col-g"></td>
                <td class="col-n"></td>
            </tr>
        </table>
    </div>
</body>

</html>
