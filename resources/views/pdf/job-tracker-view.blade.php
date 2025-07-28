<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Job Tracking Form</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            line-height: 1.2;
        }

        .container {
            width: 100%;
            max-width: 210mm;
            margin: 0 auto;
        }

        /* Header */
        .header {
            text-align: center;
            border: 2px solid black;
            padding: 8px;
            margin-bottom: 0;
        }

        .company-name {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 2px;
        }

        .form-title {
            font-size: 14px;
            font-weight: bold;
            text-decoration: underline;
        }

        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            border: 2px solid black;
        }

        td,
        th {
            border: 1px solid black;
            padding: 3px 5px;
            vertical-align: top;
        }

        .section-header {
            background-color: #f0f0f0;
            font-weight: bold;
            font-size: 11px;
            text-align: left;
            padding: 5px;
        }

        .label-cell {
            font-weight: bold;
            text-align: right;
            width: 15%;
            background-color: #f8f8f8;
        }

        .input-cell {
            border-bottom: 1px dotted black;
            min-height: 20px;
            width: 35%;
        }

        .checkbox-group {
            display: inline-block;
            margin-right: 15px;
        }

        .checkbox {
            width: 12px;
            height: 12px;
            border: 1px solid black;
            display: inline-block;
            margin-right: 3px;
            vertical-align: middle;
        }

        .checkbox.checked {
            background-color: black;
        }

        .unit {
            font-size: 9px;
            font-style: italic;
        }

        .wide-input {
            width: 85%;
        }

        .medium-input {
            width: 50%;
        }

        .small-input {
            width: 25%;
        }

        .equipment-list {
            font-size: 9px;
        }

        .treatment-row td {
            height: 25px;
        }

        .charges-section .input-cell {
            text-align: left;
            padding-left: 20px;
        }

        .currency {
            font-size: 9px;
            font-weight: bold;
        }

        /* Specific styling for different sections */
        .general-info .input-cell {
            min-height: 18px;
        }

        .well-info-checkbox {
            font-size: 9px;
        }

        .personnel-row td {
            height: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="company-name">Coiled Tubing and Nitrogen Services</div>
            <div class="form-title">Job Tracking</div>
        </div>

        <!-- Main Form Table -->
        <table>
            <!-- General Information Section -->
            <tr>
                <td colspan="8" class="section-header">GENERAL INFORMATION</td>
            </tr>
            <tr class="general-info">
                <td class="label-cell">Job Description</td>
                <td colspan="7" class="input-cell wide-input">{{ $data['job_description'] ?? '' }}</td>
            </tr>
            <tr class="general-info">
                <td class="label-cell">Well Name</td>
                <td colspan="3" class="input-cell">{{ $data['well_name'] ?? '' }}</td>
                <td class="label-cell">Job Start Date</td>
                <td colspan="3" class="input-cell">{{ $data['job_start_date'] ?? '' }}</td>
            </tr>
            <tr class="general-info">
                <td class="label-cell">Customer</td>
                <td class="input-cell">{{ $data['customer'] ?? '' }}</td>
                <td class="label-cell">Company Man</td>
                <td class="input-cell">{{ $data['company_man'] ?? '' }}</td>
                <td class="label-cell">Job Finish Date</td>
                <td colspan="3" class="input-cell">{{ $data['job_finish_date'] ?? '' }}</td>
            </tr>
            <tr class="general-info">
                <td class="label-cell">District</td>
                <td class="input-cell">{{ $data['district'] ?? '' }}</td>
                <td class="label-cell">BJ Representative</td>
                <td class="input-cell">{{ $data['bj_representative'] ?? '' }}</td>
                <td class="label-cell">Job Days</td>
                <td colspan="2" class="input-cell">{{ $data['job_days'] ?? '' }}</td>
                <td class="unit">Days</td>
            </tr>

            <!-- Well Information Section -->
            <tr>
                <td colspan="8" class="section-header">WELL INFORMATION</td>
            </tr>
            <tr>
                <td class="label-cell">Field Location</td>
                <td colspan="3" class="well-info-checkbox">
                    <span class="checkbox-group">
                        <span
                            class="checkbox {{ isset($data['field_location']) && $data['field_location'] == 'onshore' ? 'checked' : '' }}"></span>Onshore
                    </span>
                    <span class="checkbox-group">
                        <span
                            class="checkbox {{ isset($data['field_location']) && $data['field_location'] == 'shore' ? 'checked' : '' }}"></span>Shore
                    </span>
                </td>
                <td class="label-cell">Depth MD</td>
                <td colspan="2" class="input-cell">{{ $data['depth_md'] ?? '' }}</td>
                <td class="unit">ft</td>
            </tr>
            <tr>
                <td class="label-cell">Field Type</td>
                <td colspan="3" class="well-info-checkbox">
                    <span class="checkbox-group">
                        <span
                            class="checkbox {{ isset($data['field_type']) && $data['field_type'] == 'oil' ? 'checked' : '' }}"></span>Oil
                    </span>
                    <span class="checkbox-group">
                        <span
                            class="checkbox {{ isset($data['field_type']) && $data['field_type'] == 'gas' ? 'checked' : '' }}"></span>Gas
                    </span>
                    <span class="checkbox-group">
                        <span
                            class="checkbox {{ isset($data['field_type']) && $data['field_type'] == 'gas_thermal' ? 'checked' : '' }}"></span>Gas
                    </span>
                    <span class="checkbox-group">
                        <span
                            class="checkbox {{ isset($data['field_type']) && $data['field_type'] == 'geothermal' ? 'checked' : '' }}"></span>Geothermal
                    </span>
                </td>
                <td class="label-cell">Depth TVD</td>
                <td colspan="2" class="input-cell">{{ $data['depth_tvd'] ?? '' }}</td>
                <td class="unit">ft</td>
            </tr>
            <tr>
                <td class="label-cell">Well Status</td>
                <td colspan="3" class="well-info-checkbox">
                    <span class="checkbox-group">
                        <span
                            class="checkbox {{ isset($data['well_status']) && $data['well_status'] == 'expl' ? 'checked' : '' }}"></span>Expl.
                    </span>
                    <span class="checkbox-group">
                        <span
                            class="checkbox {{ isset($data['well_status']) && $data['well_status'] == 'dev_inj' ? 'checked' : '' }}"></span>Dev.-Inj.
                    </span>
                    <span class="checkbox-group">
                        <span
                            class="checkbox {{ isset($data['well_status']) && $data['well_status'] == 'prod' ? 'checked' : '' }}"></span>Prod.
                    </span>
                </td>
                <td class="label-cell">Casing/Liner Size</td>
                <td colspan="2" class="input-cell">{{ $data['casing_liner_size'] ?? '' }}</td>
                <td class="unit">inch</td>
            </tr>
            <tr>
                <td class="label-cell">Well Type</td>
                <td colspan="3" class="well-info-checkbox">
                    <span class="checkbox-group">
                        <span
                            class="checkbox {{ isset($data['well_type']) && $data['well_type'] == 'vertical' ? 'checked' : '' }}"></span>Vertical
                    </span>
                    <span class="checkbox-group">
                        <span
                            class="checkbox {{ isset($data['well_type']) && $data['well_type'] == 'directional' ? 'checked' : '' }}"></span>Directional
                    </span>
                    <span class="checkbox-group">
                        <span
                            class="checkbox {{ isset($data['well_type']) && $data['well_type'] == 'horizontal' ? 'checked' : '' }}"></span>Horizontal
                    </span>
                </td>
                <td class="label-cell">Completion Size</td>
                <td colspan="2" class="input-cell">{{ $data['completion_size'] ?? '' }}</td>
                <td class="unit">inch</td>
            </tr>
            <tr>
                <td class="label-cell">Max Deviation</td>
                <td class="input-cell">{{ $data['max_deviation'] ?? '' }}</td>
                <td class="label-cell">Wellhead X-Over</td>
                <td class="input-cell">{{ $data['wellhead_xover'] ?? '' }}</td>
                <td class="label-cell">BH Pressure</td>
                <td colspan="2" class="input-cell">{{ $data['bh_pressure'] ?? '' }}</td>
                <td class="unit">psi</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td class="label-cell">BH Temperature</td>
                <td colspan="2" class="input-cell">{{ $data['bh_temperature'] ?? '' }}</td>
                <td class="unit">oF</td>
            </tr>

            <!-- Equipment and Tools Section -->
            <tr>
                <td colspan="8" class="section-header">EQUIPMENT AND TOOLS</td>
            </tr>
            <tr>
                <td class="label-cell">Nozzle Type</td>
                <td colspan="3" class="well-info-checkbox">
                    <span class="checkbox-group">
                        <span
                            class="checkbox {{ isset($data['nozzle_type']) && $data['nozzle_type'] == 'regular' ? 'checked' : '' }}"></span>Regular
                        Nozzle
                    </span>
                    <span class="checkbox-group">
                        <span
                            class="checkbox {{ isset($data['nozzle_type']) && $data['nozzle_type'] == 'jetting' ? 'checked' : '' }}"></span>Jetting
                    </span>
                    <span class="checkbox-group">
                        <span
                            class="checkbox {{ isset($data['nozzle_type']) && $data['nozzle_type'] == 'foaming' ? 'checked' : '' }}"></span>Foaming
                    </span>
                    <span class="checkbox-group">
                        <span
                            class="checkbox {{ isset($data['nozzle_type']) && $data['nozzle_type'] == 'rotating' ? 'checked' : '' }}"></span>Rotating
                        Nozzle
                    </span>
                    <span class="checkbox"></span>
                </td>
                <td class="label-cell">CT Size</td>
                <td colspan="2" class="input-cell">{{ $data['ct_size'] ?? '' }}</td>
                <td class="unit">inch</td>
            </tr>
            <tr>
                <td class="label-cell">CT Grade</td>
                <td class="input-cell">{{ $data['ct_grade'] ?? '' }}</td>
                <td class="label-cell">Max. Depth</td>
                <td class="input-cell">{{ $data['max_depth'] ?? '' }}</td>
                <td class="unit">ft</td>
                <td class="label-cell">Max BHA OD</td>
                <td colspan="2" class="input-cell">{{ $data['max_bha_od'] ?? '' }}</td>
                <td class="unit">inch</td>
            </tr>
            <tr>
                <td colspan="8" class="equipment-list">
                    <strong>Asset Number for the following Equipment</strong>
                </td>
            </tr>
            <tr>
                <td class="label-cell">Control Cabin</td>
                <td colspan="7" class="input-cell">{{ $data['control_cabin'] ?? '' }}</td>
            </tr>
            <tr>
                <td class="label-cell">Power Pack</td>
                <td colspan="7" class="input-cell">{{ $data['power_pack'] ?? '' }}</td>
            </tr>
            <tr>
                <td class="label-cell">Power Reel</td>
                <td colspan="7" class="input-cell">{{ $data['power_reel'] ?? '' }}</td>
            </tr>
            <tr>
                <td class="label-cell">CT Injector</td>
                <td colspan="7" class="input-cell">{{ $data['ct_injector'] ?? '' }}</td>
            </tr>
            <tr>
                <td class="label-cell">BOP</td>
                <td colspan="7" class="input-cell">{{ $data['bop'] ?? '' }}</td>
            </tr>
            <tr>
                <td class="label-cell">N2 Converter</td>
                <td colspan="7" class="input-cell">{{ $data['n2_converter'] ?? '' }}</td>
            </tr>
            <tr>
                <td class="label-cell">N2 Tank</td>
                <td colspan="7" class="input-cell">{{ $data['n2_tank'] ?? '' }}</td>
            </tr>
            <tr>
                <td class="label-cell">Container</td>
                <td colspan="7" class="input-cell">{{ $data['container'] ?? '' }}</td>
            </tr>
            <tr>
                <td class="label-cell">Miscellaneous Tool</td>
                <td colspan="7" class="input-cell">{{ $data['miscellaneous_tool'] ?? '' }}</td>
            </tr>

            <!-- Personnel Section -->
            <tr>
                <td colspan="8" class="section-header">PERSONNEL</td>
            </tr>
            <tr class="personnel-row">
                <td class="label-cell">CT Supervisor</td>
                <td colspan="7" class="input-cell">{{ $data['ct_supervisor'] ?? '' }}</td>
            </tr>
            <tr class="personnel-row">
                <td class="label-cell">CT Personnel</td>
                <td colspan="7" class="input-cell">{{ $data['ct_personnel'] ?? '' }}</td>
            </tr>
            <tr class="personnel-row">
                <td class="label-cell">N2 Supervisor</td>
                <td colspan="7" class="input-cell">{{ $data['n2_supervisor'] ?? '' }}</td>
            </tr>
            <tr class="personnel-row">
                <td class="label-cell">N2 Personnel</td>
                <td colspan="7" class="input-cell">{{ $data['n2_personnel'] ?? '' }}</td>
            </tr>
            <tr class="personnel-row">
                <td class="label-cell">Pump Supervisor</td>
                <td colspan="7" class="input-cell">{{ $data['pump_supervisor'] ?? '' }}</td>
            </tr>
            <tr class="personnel-row">
                <td class="label-cell">Pump Personnel</td>
                <td colspan="7" class="input-cell">{{ $data['pump_personnel'] ?? '' }}</td>
            </tr>

            <!-- Treatment Section -->
            <tr>
                <td colspan="8" class="section-header">TREATMENT</td>
            </tr>
            <tr class="treatment-row">
                <td class="label-cell">Treatment</td>
                <td colspan="3" class="input-cell">{{ $data['treatment'] ?? '' }}</td>
                <td class="label-cell">Volume</td>
                <td colspan="2" class="input-cell">{{ $data['treatment_volume'] ?? '' }}</td>
                <td class="unit">Bbls</td>
            </tr>
            <tr class="treatment-row">
                <td class="label-cell">Liquid Type 01</td>
                <td colspan="3" class="input-cell">{{ $data['liquid_type_01'] ?? '' }}</td>
                <td class="label-cell">Volume</td>
                <td colspan="2" class="input-cell">{{ $data['liquid_volume_01'] ?? '' }}</td>
                <td class="unit">Bbls</td>
            </tr>
            <tr class="treatment-row">
                <td class="label-cell">Liquid Type 02</td>
                <td colspan="3" class="input-cell">{{ $data['liquid_type_02'] ?? '' }}</td>
                <td class="label-cell">Volume</td>
                <td colspan="2" class="input-cell">{{ $data['liquid_volume_02'] ?? '' }}</td>
                <td class="unit">Bbls</td>
            </tr>
            <tr class="treatment-row">
                <td class="label-cell">Liquid Type 03</td>
                <td colspan="3" class="input-cell">{{ $data['liquid_type_03'] ?? '' }}</td>
                <td class="label-cell">Volume</td>
                <td colspan="2" class="input-cell">{{ $data['liquid_volume_03'] ?? '' }}</td>
                <td class="unit">Bbls</td>
            </tr>
            <tr class="treatment-row">
                <td class="label-cell">Nitrogen Volume</td>
                <td colspan="3" class="input-cell">{{ $data['nitrogen_volume'] ?? '' }}</td>
                <td class="unit">Gals</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr class="treatment-row">
                <td class="label-cell">Cement Volume</td>
                <td colspan="3" class="input-cell">{{ $data['cement_volume'] ?? '' }}</td>
                <td class="unit">Bbls</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>

            <!-- Charges Section -->
            <tr>
                <td colspan="8" class="section-header">CHARGES</td>
            </tr>
            <tr>
                <td class="label-cell">Equipment</td>
                <td colspan="3" class="charges-section">
                    <div class="input-cell">
                        <span class="currency">US$</span> {{ $data['equipment_charge'] ?? '' }}
                    </div>
                </td>
                <td class="label-cell">Personnel Charges</td>
                <td colspan="3" class="charges-section">
                    <div class="input-cell">
                        <span class="currency">US$</span> {{ $data['personnel_charges'] ?? '' }}
                    </div>
                </td>
            </tr>
            <tr>
                <td class="label-cell">Coiled Tubing</td>
                <td colspan="3" class="charges-section">
                    <div class="input-cell">
                        <span class="currency">US$</span> {{ $data['coiled_tubing_charge'] ?? '' }}
                    </div>
                </td>
                <td class="label-cell">Service Charges</td>
                <td colspan="3" class="charges-section">
                    <div class="input-cell">
                        <span class="currency">US$</span> {{ $data['service_charges'] ?? '' }}
                    </div>
                </td>
            </tr>
            <tr>
                <td class="label-cell">Nitrogen</td>
                <td colspan="3" class="charges-section">
                    <div class="input-cell">
                        <span class="currency">US$</span> {{ $data['nitrogen_charge'] ?? '' }}
                    </div>
                </td>
                <td class="label-cell">Materials</td>
                <td colspan="3" class="charges-section">
                    <div class="input-cell">
                        <span class="currency">US$</span> {{ $data['materials_charge'] ?? '' }}
                    </div>
                </td>
            </tr>
            <tr>
                <td class="label-cell">Pumping</td>
                <td colspan="3" class="charges-section">
                    <div class="input-cell">
                        <span class="currency">US$</span> {{ $data['pumping_charge'] ?? '' }}
                    </div>
                </td>
                <td class="label-cell">Mobilization</td>
                <td colspan="3" class="charges-section">
                    <div class="input-cell">
                        <span class="currency">US$</span> {{ $data['mobilization_charge'] ?? '' }}
                    </div>
                </td>
            </tr>
            <tr>
                <td class="label-cell">Special Tools</td>
                <td colspan="3" class="charges-section">
                    <div class="input-cell">
                        <span class="currency">US$</span> {{ $data['special_tools_charge'] ?? '' }}
                    </div>
                </td>
                <td class="label-cell">Others</td>
                <td colspan="3" class="charges-section">
                    <div class="input-cell">
                        <span class="currency">US$</span> {{ $data['others_charge'] ?? '' }}
                    </div>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
