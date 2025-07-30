<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Tracking Form</title>
    <style>
        /* ESSENTIAL STYLES - Diperlukan untuk layout dasar */
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            /* margin: 0; */
            padding: 15px;
            background-color: white !important;
            line-height: 1.2;
        }

        .form-container {
            width: 100%;
        }

        /* HEADER STYLES */
        .header {
            text-align: center;
            margin-bottom: 15px;
        }

        .header-title {
            margin: 0 0 3px 0;
            font-size: 12px;
            font-weight: normal;
        }

        .header-subtitle {
            margin: 0;
            font-size: 14px;
            font-weight: bold;
        }

        /* SECTION STYLES */
        .section {
            border: 2px solid black;
            margin-bottom: 8px;
            width: 100%;
        }

        .section-header {
            background-color: black;
            color: white;
            padding: 4px 8px;
            font-weight: bold;
            font-size: 12px !important;
            /* margin: 0; */
        }

        .section-content {
            padding: 8px;
            width: 100% !important;
        }

        /* TABLE STYLES */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
        }

        td {
            padding: 2px 5px;
            vertical-align: middle;
        }

        /* STANDARDIZED LABEL STYLES */
        .label,
        .equipment-label,
        .personnel-label,
        .charge-label,
        .checkbox-label {
            font-size: 12px !important;
            line-height: 16px;
            vertical-align: middle;
            width: 120px !important;
        }

        /* INPUT CONTAINER STYLES */
        .input-container,
        .equipment-input-container,
        .personnel-input-container,
        .charge-input-container {
            display: flex;
            /* border-bottom: 1px solid gray; */
            vertical-align: middle;
        }

        /* STANDARDIZED INPUT STYLES */
        .input-field,
        .equipment-input,
        .personnel-input,
        .charge-input {
            border: none !important;
            background: white !important;
            padding: 1px 2px;
            font-size: 12px !important;
            height: 16px;
            line-height: 16px;
            width: auto;
            display: block;
            outline: none;
        }

        /* INPUT WIDTH VARIATIONS */
        .wide-input {
            width: 250px;
        }

        .medium-input {
            width: 80px;
        }

        .short-input {
            width: 50px;
        }

        /* CHECKBOX STYLES */
        .checkbox {
            width: 10px;
            height: 10px;
            margin-right: 3px;
            vertical-align: middle;
        }

        /* UNIT AND CURRENCY STYLES */
        .unit,
        .currency {
            font-size: 12px !important;
            margin-left: 3px;
            display: inline-block;
            vertical-align: middle;
        }

        /* CHARGES LAYOUT */
        .charges-container {
            width: 100%;
        }

        .charges-left {
            float: left;
            width: 45%;
        }

        .charges-right {
            float: right;
            width: 45%;
        }

        /* TEXT ALIGNMENT UTILITIES */
        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-left {
            text-align: left;
        }

        /* INPUT OVERRIDES FOR MPDF */
        input {
            border: none !important;
            background: white !important;
            color: black !important;
        }
    </style>
    <style>
        .custom-checkbox {
            display: inline-block;
            width: 12px;
            height: 12px;
            border: 1px solid #000;
            margin-right: 5px;
            position: relative;
            vertical-align: middle;
        }

        .custom-checkbox.checked::after {
            content: '✓';
            position: absolute;
            top: -2px;
            left: 1px;
            font-size: 12px;
        }

        .checkbox-text {
            margin-right: 15px;
            vertical-align: middle;
        }

        .w-auto {
            width: auto !important;
        }
    </style>
</head>

<body>
    <div class="form-container">
        <div class="header">
            <div class="header-title">Coiled Tubing and Nitrogen Services</div>
            <div class="header-subtitle">Job Tracking</div>
        </div>

        <div class="section">
            <div class="section-header">GENERAL INFORMATION</div>
            <div class="section-content">
                <table class="charge-table">
                    <tr>
                        <td class="label">Job Description</td>
                        <td class="content" style="display: flex !important;">
                            <table class="nested-table" border="0">
                                @foreach ($jobTracker->jobDescriptions as $index => $jobDescription)
                                    <tr>
                                        <td class="number-col" valign="top">
                                            {{ ++$index }}.
                                        </td>
                                        <td>
                                            {{ $jobDescription->description }}
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </td>
                        <td class="right-label">Job Start Date</td>
                        <td class="right-content">
                            : {{ $jobTracker->job_start_date ? $jobTracker->job_start_date : '-' }}
                        </td>
                    </tr>
                    <tr>
                        <td class="label">Well Name</td>
                        <td class="content">
                            : {{ $jobTracker->well_name ?? '-' }}
                        </td>
                        <td class="right-label">Job Finish Date</td>
                        <td class="right-content">
                            : {{ $jobTracker->job_finish_date ? $jobTracker->job_finish_date : '-' }}
                        </td>
                    </tr>
                    <tr>
                        <td class="label">Customer</td>
                        <td class="content">
                            : {{ $jobTracker->customer ?? '-' }}
                        </td>
                        <td class="right-label">Job Days</td>
                        <td class="right-content">
                            : {{ $jobTracker->job_days ?? '-' }} Days
                        </td>
                    </tr>
                    <tr>
                        <td class="label">Company Man</td>
                        <td class="content">
                            : {{ $jobTracker->company_man ?? '-' }}
                        </td>
                        <td class="right-label"></td>
                        <td class="right-content"></td>
                    </tr>
                    <tr>
                        <td class="label">COSL Base</td>
                        <td class="content">
                            : {{ $jobTracker->cosl_base ?? '-' }}
                        </td>
                        <td class="right-label"></td>
                        <td class="right-content"></td>
                    </tr>
                    <tr>
                        <td class="label">COSL OCD Representative</td>
                        <td class="content">
                            : {{ $jobTracker->cosl_ocd_representative ?? '-' }}
                        </td>
                        <td class="right-label"></td>
                        <td class="right-content"></td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="section">
            <div class="section-header">WELL INFORMATION</div>
            <div class="section-content">
                <table class="charge-table">
                    <tr>
                        <td class="label">Field Location</td>
                        <td>
                            @foreach ($fieldLocations as $field_location)
                                <input type="checkbox" class="checkbox"
                                    {{ trim($field_location->location_name) == trim($jobTracker->field_location) ? 'checked=checked' : '' }}>
                                <span class="checkbox-label">
                                    {{ $field_location->location_name }}
                                </span>
                            @endforeach
                        </td>
                        <td class="label" style="text-align: right; padding-right: 5px;">Depth MD</td>
                        <td>
                            <div class="input-container medium-input">
                                :&nbsp; {{ $jobTracker->depth_md ? $jobTracker->depth_md : '-' }}
                                {{ $jobTracker->depth_md_unit }}
                            </div>
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="label">Field Type</td>
                        <td>
                            @foreach ($fieldTypes as $field_type)
                                <input type="checkbox" class="checkbox"
                                    {{ trim($field_type->type_name) == trim($jobTracker->field_type) ? 'checked=checked' : '' }}>
                                <span class="checkbox-label">
                                    {{ $field_type->type_name }}
                                </span>
                            @endforeach
                        </td>
                        <td class="label" style="text-align: right; padding-right: 5px;">Depth TVD</td>
                        <td>
                            <div class="input-container medium-input">
                                :&nbsp; {{ $jobTracker->depth_tvd ? $jobTracker->depth_tvd : '-' }}
                                {{ $jobTracker->depth_tvd_unit }}
                            </div>
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="label">Well Status</td>
                        <td>
                            @foreach ($wellStatuses as $well_status)
                                <input type="checkbox" class="checkbox"
                                    {{ trim($well_status->status_name) == trim($jobTracker->well_status) ? 'checked=checked' : '' }}>
                                <span class="checkbox-label">
                                    {{ $well_status->status_name }}
                                </span>
                            @endforeach
                        </td>
                        <td class="label" style="text-align: right; padding-right: 5px;">Casing/Liner Size</td>
                        <td>
                            <div class="input-container medium-input">
                                :&nbsp; {{ $jobTracker->casing_liner_size ? $jobTracker->casing_liner_size : '-' }}
                                {{ $jobTracker->casing_liner_size_unit }}
                            </div>
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="label">Well Type</td>
                        <td>
                            @foreach ($wellTypes as $well_type)
                                <input type="checkbox" class="checkbox"
                                    {{ trim($well_type->type_name) == trim($jobTracker->well_type) ? 'checked=checked' : '' }}>
                                <span class="checkbox-label">
                                    {{ $well_type->type_name }}
                                </span>
                            @endforeach
                        </td>
                        <td class="label" style="text-align: right; padding-right: 5px;">Completion Size</td>
                        <td>
                            <div class="input-container medium-input">
                                :&nbsp; {{ $jobTracker->completion_size ? $jobTracker->completion_size : '-' }}
                                {{ $jobTracker->completion_size_unit }}
                            </div>
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="label">Max Deviation</td>
                        <td>
                            <div class="input-container medium-input">
                                :&nbsp; {{ $jobTracker->max_deviation }}
                            </div>
                        </td>
                        <td class="label" style="text-align: right; padding-right: 5px;">BH Pressure</td>
                        <td>
                            <div class="input-container medium-input">
                                :&nbsp; {{ $jobTracker->bh_pressure ? $jobTracker->bh_pressure : '-' }}
                                {{ $jobTracker->bh_pressure_unit }}
                            </div>
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="label">Wellhead X-Over</td>
                        <td>
                            <div class="input-container">
                                :&nbsp; {{ $jobTracker->wellhead_x_over ? $jobTracker->wellhead_x_over : '-' }}
                            </div>
                        </td>
                        <td class="label" style="text-align: right; padding-right: 5px;">BH Temperature</td>
                        <td>
                            <div class="input-container medium-input">
                                :&nbsp; {{ $jobTracker->bh_temp ? $jobTracker->bh_temp : '-' }}
                                {{ $jobTracker->bh_temp_unit }}
                            </div>
                        </td>
                        <td></td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="section">
            <div class="section-header">EQUIPMENT AND TOOLS</div>
            <div class="section-content">
                <table>
                    <tr>
                        <td class="label">Nozzle Type</td>
                        <td>
                            @foreach ($nozzleTypes as $nozzle_type)
                                <input type="checkbox" class="checkbox"
                                    {{ trim($nozzle_type->type_name) == trim($jobTracker->nozzle_type) ? 'checked=checked' : '' }}>
                                <span class="checkbox-label">
                                    {{ $nozzle_type->type_name }}
                                </span>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <td class="label">CT Grade</td>
                        <td>
                            <div class="input-container medium-input">
                                :&nbsp; {{ $jobTracker->ct_grade ? $jobTracker->ct_grade : '-' }}
                            </div>
                        </td>
                        <td></td>
                        <td class="label" style="text-align: right; padding-right: 5px;">CT Size</td>
                        <td>
                            <div class="input-container medium-input">
                                :&nbsp; {{ $jobTracker->ct_size ? $jobTracker->ct_size : '-' }}
                                {{ $jobTracker->ct_size_unit }}
                            </div>
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="label">Max. Depth</td>
                        <td>
                            @foreach ($currentMaxDepths as $index => $max_depth)
                                <div class="input-container medium-input" style="width: 50% !important;">
                                    &nbsp;&nbsp;&nbsp;{{ ++$index }}. {{ $max_depth->max_depth ?? '-' }}
                                    {{ $max_depth->max_depth_unit ?? '' }}
                                </div>
                            @endforeach
                        </td>
                        <td></td>
                        <td class="label" style="text-align: right; padding-right: 5px;">Max BHA OD</td>
                        <td>
                            <div class="input-container medium-input">
                                :&nbsp; {{ $jobTracker->max_bha_od ? $jobTracker->max_bha_od : '-' }}
                                {{ $jobTracker->max_bha_od_unit }}
                            </div>
                        </td>
                        <td></td>
                    </tr>
                </table>

                <div style="margin-top: 10px; font-weight: bold; font-size: 10px;">Asset Number for the following
                    Equipment</div>

                <table class="equipment-table">
                    <tr>
                        <td class="equipment-label">Control Cabin</td>
                        <td>
                            <div class="equipment-input-container">
                                :&nbsp; {{ $jobTracker->control_cabin ? $jobTracker->control_cabin : '-' }}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="equipment-label">Power Pack</td>
                        <td>
                            <div class="equipment-input-container">
                                :&nbsp; {{ $jobTracker->power_pack ? $jobTracker->power_pack : '-' }}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="equipment-label">Power Reel</td>
                        <td>
                            <div class="equipment-input-container">
                                :&nbsp; {{ $jobTracker->power_reel ? $jobTracker->power_reel : '-' }}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="equipment-label">CT Injector</td>
                        <td>
                            <div class="equipment-input-container">
                                :&nbsp; {{ $jobTracker->ct_injector ? $jobTracker->ct_injector : '-' }}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="equipment-label">BOP</td>
                        <td>
                            <div class="equipment-input-container">
                                :&nbsp; {{ $jobTracker->bop ? $jobTracker->bop : '-' }}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="equipment-label">N2 Converter</td>
                        <td>
                            <div class="equipment-input-container">
                                :&nbsp; {{ $jobTracker->n2_converter ? $jobTracker->n2_converter : '-' }}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="equipment-label">N2 Tank</td>
                        <td>
                            @foreach ($currentN2Tanks as $index => $n2_tank)
                                <div class="equipment-input-container">
                                    &nbsp;&nbsp;&nbsp;{{ ++$index }}. {{ $n2_tank->n2_tank_name ?? '-' }}
                                </div>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <td class="equipment-label">Container</td>
                        <td>
                            @foreach ($currentContainers as $index => $container)
                                <div class="equipment-input-container">
                                    &nbsp;&nbsp;&nbsp;{{ ++$index }}. {{ $container->container_name ?? '-' }}
                                </div>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <td class="equipment-label">Miscellaneous Tool</td>
                        <td>
                            @foreach ($currentMiscellaneousTools as $index => $misc_tool)
                                <div class="equipment-input-container">
                                    &nbsp;&nbsp;&nbsp;{{ ++$index }}. {{ $misc_tool->miscellaneous_tool_name ?? '-' }}
                                </div>
                            @endforeach
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="section">
            <div class="section-header">PERSONNEL</div>
            <div class="section-content">
                <table class="personnel-table">
                    <tr>
                        <td class="personnel-label">CT Supervisor</td>
                        <td>
                            <div class="personnel-input-container">
                                :&nbsp; {{ $jobTracker->ct_supervisor ? $jobTracker->ct_supervisor : '-' }}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="personnel-label">CT Personnel</td>
                        <td>
                            @foreach ($currentCTPersonnels as $index => $ct_personnel)
                                <div class="personnel-input-container">
                                    &nbsp;&nbsp;&nbsp;{{ ++$index }}. {{ $ct_personnel->ct_personnel_name ?? '-' }}
                                </div>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <td class="personnel-label">N2 Supervisor</td>
                        <td>
                            <div class="personnel-input-container">
                                :&nbsp; {{ $jobTracker->nitrogen_supervisor ? $jobTracker->nitrogen_supervisor : '-' }}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="personnel-label">N2 Personnel</td>
                        <td>
                            @foreach ($currentN2Personnels as $index => $n2_personnel)
                                <div class="personnel-input-container">
                                    &nbsp;&nbsp;&nbsp;{{ ++$index }}. {{ $n2_personnel->nitrogen_personnel_name ?? '-' }}
                                </div>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <td class="personnel-label">Pump Supervisor</td>
                        <td>
                            <div class="personnel-input-container">
                                :&nbsp; {{ $jobTracker->pump_supervisor ? $jobTracker->pump_supervisor : '-' }}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="personnel-label">Pump Personnel</td>
                        <td>
                            @foreach ($currentPumpPersonnels as $index => $pump_personnel)
                                <div class="personnel-input-container">
                                    &nbsp;&nbsp;&nbsp;{{ ++$index }}. {{ $pump_personnel->pump_personnel_name ?? '-' }}
                                </div>
                            @endforeach
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="section">
            <div class="section-header">TREATMENT</div>
            <div class="section-content">
                <div class="charges-container">
                    <div class="charges-left">
                        <div style="margin-bottom: 8px; font-weight: bold; font-size: 10px;">Treatment</div>
                        <table class="charges-table">
                            @foreach ($currentAcidTypes as $index => $acid_type)
                                <tr>
                                    @php
                                        $formattedIndex = str_pad($index + 1, 2, '0', STR_PAD_LEFT);
                                    @endphp
                                    <td class="charge-label">Liquid Type {{ $formattedIndex }} </td>
                                    <td>
                                        <div class="input-container">
                                            :&nbsp;{{ $acid_type->acid_type ?? '-' }}
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td class="label">Nitrogen Volume</td>
                                <td>
                                    <div class="input-container medium-input">
                                        :&nbsp;{{ $jobTracker->nitrogen_volume ? $jobTracker->nitrogen_volume : '-' }}
                                        {{ $jobTracker->nitrogen_volume_unit ?? 'Bbls' }}
                                    </div>
                                </td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td class="label">Cement Volume</td>
                                <td>
                                    <div class="input-container medium-input">
                                        :&nbsp;{{ $jobTracker->cement_volume ? $jobTracker->cement_volume : '-' }}
                                        {{ $jobTracker->cement_volume_unit ?? 'Bbls' }}
                                    </div>
                                </td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </table>
                    </div>
                    <div class="charges-right" style="margin-top: 25px;">
                        <table class="charge-table">
                            <tr></tr>
                            <tr></tr>
                            @foreach ($currentAcidVolumes as $index => $acid_volume)
                                <tr>
                                    @php
                                        $formattedIndex = str_pad($index + 1, 2, '0', STR_PAD_LEFT);
                                    @endphp
                                    <td class="charge-label">Liquid Volume {{ $formattedIndex }}</td>
                                    <td>
                                        <div class="input-container">
                                            :&nbsp;{{ $acid_volume->volume ?? '-' }}
                                            {{ $acid_volume->volume_unit ?? 'Bbls' }}
                                        </div>
                                    </td>
                                    <td></td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="section">
            <div class="section-header">CHARGES</div>
            <div class="section-content">
                <div class="charges-container">
                    <div class="charges-left">
                        <div style="margin-bottom: 8px; font-weight: bold; font-size: 10px;">Equipment</div>
                        <table class="charge-table">
                            <tr>
                                <td class="charge-label">Coiled Tubing</td>
                                <td>
                                    <div class="charge-input-container">
                                        :&nbsp;
                                        {{ $jobTracker->revenue_currency }}
                                        {{ $jobTracker->revenue_coiled_tubing ? $jobTracker->revenue_coiled_tubing : '-' }}
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="charge-label">Nitrogen</td>
                                <td>
                                    <div class="charge-input-container">
                                        :&nbsp;
                                        {{ $jobTracker->revenue_currency }}
                                        {{ $jobTracker->revenue_nitrogen_equipment ? $jobTracker->revenue_nitrogen_equipment : '-' }}
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="charge-label">Pumping</td>
                                <td>
                                    <div class="charge-input-container">
                                        :&nbsp;
                                        {{ $jobTracker->revenue_currency }}
                                        {{ $jobTracker->revenue_pumping ? $jobTracker->revenue_pumping : '-' }}
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="charge-label">Special Tools</td>
                                <td>
                                    <div class="charge-input-container">
                                        :&nbsp;
                                        {{ $jobTracker->revenue_currency }}
                                        {{ $jobTracker->revenue_special_tools ? $jobTracker->revenue_special_tools : '-' }}
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="charges-right">
                        <table class="charge-table">
                            <tr>
                                <td class="charge-label">Personnel Charges</td>
                                <td>
                                    <div class="charge-input-container">
                                        :&nbsp;
                                        {{ $jobTracker->revenue_currency }}
                                        {{ $jobTracker->personnel_charges ? $jobTracker->personnel_charges : '-' }}
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="charge-label">Service Charges</td>
                                <td>
                                    <div class="charge-input-container">
                                        :&nbsp;
                                        {{ $jobTracker->revenue_currency }}
                                        {{ $jobTracker->service_charges ? $jobTracker->service_charges : '-' }}
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="charge-label">Materials</td>
                                <td>
                                    <div class="charge-input-container">
                                        :&nbsp;
                                        {{ $jobTracker->revenue_currency }}
                                        {{ $jobTracker->material_charges }}
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="charge-label">Mobilization</td>
                                <td>
                                    <div class="charge-input-container">
                                        :&nbsp;
                                        {{ $jobTracker->revenue_currency }}
                                        {{ $jobTracker->mobilization_charges }}
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="charge-label">Others</td>
                                <td>
                                    <div class="charge-input-container">
                                        :&nbsp;
                                        {{ $jobTracker->revenue_currency }}
                                        {{ $jobTracker->other_charges }}
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
