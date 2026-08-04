<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>RMRF Form</title>
    <style>
        @page {
            margin-top: 12mm;
            margin-bottom: 12mm;
            margin-left: 16mm;
            margin-right: 16mm;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10px;
            color: #000;
            line-height: 1.2;
        }
        .header-title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 12px;
        }

        /* Utility styles */
        table {
            width: 100%;
            border-collapse: collapse;
        }
        td {
            padding: 2px 2px;
            vertical-align: bottom;
        }
        .line {
            border-bottom: 1px solid #000;
        }
        .bold { font-weight: bold; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }

        /* Control number box */
        .box-title {
            border: 1.5px solid #000 !important;
            text-align: center;
            font-weight: bold;
            font-size: 12px;
            padding: 2px 4px;
        }

        /* Native Symbol Checkboxes (No CSS box collapsing) */
        .checkbox-symbol {
            font-size: 17px !important; /* Scaled up from 13px */
            line-height: 1;
            vertical-align: -2px;       /* Aligns the bottom of the box neatly with text */
            margin-right: 2px;
        }

        .section-divider {
            border-top: 1.5px solid #000;
            margin-top: 8px;
            margin-bottom: 6px;
        }

        /* Spare parts table */
        .parts-table {
            border: 1.5px solid #000;
            margin-top: 4px;
        }
        .parts-table th, .parts-table td {
            border: 1px solid #000;
            padding: 3px;
            height: 16px;
        }
        .parts-table th {
            font-weight: bold;
            background-color: #ffffff;
        }

        .sub-label {
            font-size: 8px;
            color: #333;
        }

        .footer-code {
            text-align: right;
            font-weight: bold;
            font-size: 9px;
            margin-top: 10px;
        }
    </style>
</head>
<body>

    <!-- Header Title -->
    <div class="header-title">
        Repair and Maintenance Request Form (RMRF)
    </div>

    <!-- Control Number Section -->
    <table style="width: 100%; margin-bottom: 15px;">
        <tr>
            <!-- Left empty space to push everything to the right -->
            <td style="width: 60%;"></td>

            <!-- Label right next to the box -->
            <td style="width: 15%; white-space: nowrap; text-align: right; padding-right: 8px;" class="bold">
                Control Number:
            </td>

            <!-- Boxed value with increased width and horizontal padding -->
            <td style="width: 25%; border: 1px solid black; text-align: center; padding: 3px 15px;" class="bold">
                {{ $workorder->request->control_number ?? '' }}
            </td>
        </tr>
    </table>

    <!-- Main Request Details -->
    <table>
        <tr>
            <td style="width: 18%; white-space: nowrap;" class="bold">Requisitioner's name:</td>
            <td style="width: 32%; white-space: nowrap;" class="line">{{ $workorder->request->requestedBy->name ?? '' }}</td>
            <td style="width: 8%; white-space: nowrap;" class="bold text-right">Sec/Dept:</td>
            <td style="width: 20%; white-space: nowrap;" class="line">{{ $workorder->request->department->name ?? '' }}</td>
            <td style="width: 6%; white-space: nowrap;" class="bold text-right">Date:</td>
            <td style="width: 16%; white-space: nowrap;" class="line">{{ $workorder->request->created_at->format('M d, Y') }}</td>
        </tr>
    </table>

    <table style="margin-top: 3px;">
        <tr>
            <td style="width: 20%; white-space: nowrap;" class="bold">Equipment/Vehicle Type:</td>
            <td style="width: 28%; white-space: nowrap;" class="line">{{ $workorder->request->asset->name ?? '' }}</td>
            <td style="width: 12%; white-space: nowrap;" class="bold text-right">Type of request:</td>
            <td style="width: 40%; white-space: nowrap;">
                <span class="checkbox-symbol">{!! $workorder->request->request_type === \App\Enums\RequestTypes::REPAIR ? '&#9745;' : '&#9744;' !!}</span> Repair &nbsp;&nbsp;
                <span class="checkbox-symbol">{!! $workorder->request->request_type === \App\Enums\RequestTypes::FABRICATION ? '&#9745;' : '&#9744;' !!}</span> Fabrication &nbsp;&nbsp;
                <span class="checkbox-symbol">{!! $workorder->request->request_type === \App\Enums\RequestTypes::MAINTENANCE ? '&#9745;' : '&#9744;' !!}</span> Maintenance
            </td>
        </tr>
    </table>

    <table style="margin-top: 3px;">
        <tr>
            <td style="width: 22%; white-space: nowrap;" class="bold">Request Description/Plate No.:</td>
            <td style="width: 33%;" class="line">{{ $workorder->request->description ?? '' }}</td>
            <td style="width: 45%;"></td> <!-- Empty spacer to shorten the line -->
        </tr>
    </table>

    <!-- Signature Block (Flat Structure) -->
    <table style="width: 100%; margin-top: 15px;">
        <!-- Row 1: Labels -->
        <tr>
            <td style="width: 32%;" class="bold">Requested by:</td>
            <td style="width: 36%;"></td>
            <td style="width: 32%;" class="bold">Request approved by:</td>
        </tr>

        <!-- Row 2: Name & Line -->
        <tr>
            <!-- Left Line with side margin -->
            <td style="width: 32%;">
                <table style="width: 100%;">
                    <tr>
                        <td class="line text-center" style="height: 14px; vertical-align: bottom;">
                            {{ $workorder->request->requestedBy->name ?? '' }}
                        </td>
                    </tr>
                </table>
            </td>

            <!-- Middle Gap Spacer -->
            <td style="width: 36%;"></td>

            <!-- Right Line with side margin -->
            <td style="width: 32%;">
                <table style="width: 100%;">
                    <tr>
                        <td class="line text-center" style="height: 14px; vertical-align: bottom;">
                            {{ $workorder->request->approvedBy->name ?? '' }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <!-- Row 3: Sub-labels -->
        <tr>
            <td class="text-center bold" style="padding-top: 2px;">Signature</td>
            <td></td>
            <td class="text-center bold" style="padding-top: 2px;">Department Head</td>
        </tr>
    </table>

    <!-- Section 1: Subcontractor -->
    <div class="section-divider"></div>
    <table style="margin-left: 25px;">
        <tr>
            <td colspan="4" class="bold">
                <span class="checkbox-symbol">{!! $workorder->type === \App\Enums\WorkorderType::SUBCONTRACTOR ? '&#9745;' : '&#9744;' !!}</span>
                Repair by Subcontractor
            </td>
        </tr>

        <!-- Subcontractor Details Header -->
        <tr>
            <td colspan="2" style="padding-left: 5px; white-space: nowrap;">Subcontractor Details:</td>
            <td style="width: 15%; text-align: right; white-space: nowrap; padding-left: 10px;">Cost:</td>
            <td style="width: 35%;" class="line">{{ $workorder->sub_cost }}</td>
        </tr>

        <!-- Reduced Indent to 6px & Column Width to 20% -->
        <tr>
            <td style="width: 20%; text-align: right; white-space: nowrap; padding-left: 6px;">Name/Company:</td>
            <td style="width: 30%;" class="line">{{ $workorder->sub_name ?? '' }}</td>

            <td style="text-align: right; white-space: nowrap; padding-left: 10px;">Date Released:</td>
            <td class="line">{{ $workorder->sub_date_released?->format('M d, Y') ?? '' }}</td>
        </tr>

        <tr>
            <td style="text-align: right; white-space: nowrap; padding-left: 6px;">Request Document:</td>
            <td class="line">{{ $workorder->sub_document ?? '' }}</td>

            <td style="text-align: right; white-space: nowrap; padding-left: 10px;">Date Returned:</td>
            <td class="line">{{ $workorder->sub_date_returned?->format('M d, Y') ?? '' }}</td>
        </tr>

        @php
            // Top line fits around 65-70 chars before wrapping; second line gets the rest.
            // wordwrap() guarantees words are NOT broken in half.
            $wrappedText = wordwrap($workorder->sub_details ?? '', 100, "\n");
            $lines = explode("\n", $wrappedText, 2);
        @endphp

        <tr>
            <td style="text-align: right; white-space: nowrap; padding-left: 6px;">Details of Activities:</td>
            <td class="line" colspan="3">
                {{ $lines[0] ?? '' }}
            </td>
        </tr>
        <tr>
            <td style="text-align: right; padding-left: 6px;" class="sub-label">(Optional)</td>
            <td class="line" colspan="3">
                {{ $lines[1] ?? '' }}
            </td>
        </tr>
    </table>

    <!-- Section 2: In House Maintenance -->
    <div class="section-divider"></div>
    <table style="width: 100%; margin-left: 25px;">
        <tr>
            <td colspan="4" class="bold">
                <span class="checkbox-symbol">{!! $workorder->type === \App\Enums\WorkorderType::IN_HOUSE ? '&#9745;' : '&#9744;' !!}</span> In House Maintenance
            </td>
        </tr>
        <tr ma>
            <!-- Left Label -->
            <td style="width: 20%; padding-left: 15px; white-space: nowrap; vertical-align: bottom;">
                Assigned maintenance crew(s)
            </td>

            <!-- First Crew Member Line -->
            <td style="width: 35%; height: 18px; vertical-align: bottom;" class="line text-center">
                {{ $workorder->assignedMaintenanceCrew->get(0)->name ?? '' }}
            </td>

            <!-- Gap Spacer -->
            <td style="width: 5%;"></td>

            <!-- Second Crew Member Line -->
            <td style="width: 40%; height: 18px; vertical-align: bottom;" class="line text-center">
                {{ $workorder->assignedMaintenanceCrew->get(1)->name ?? '' }}
            </td>
        </tr>
    </table>
    <!-- Priority, Duration & Cost Section (Right-Aligned Edges) -->
    <table style="width: 100%; margin-left:35px">
        <!-- Row 1: Priority Level & Estimated Time -->
        <tr>
            <td style="width: 12%; white-space: nowrap;">PRIORITY level:</td>
            <td style="width: 38%;" class="line text-center">
                {{ $workorder->priority_level->label() ?? '' }}
            </td>

            <td style="width: 25%; white-space: nowrap; text-align: left; padding-left: 10px;" class="bold">
                Estimated day(s) Hour(s):
            </td>
            <td style="width: 25%;" class="line">
                {{ $workorder->estimated_duration ?? '' }}
            </td>
        </tr>

        <!-- Row 2: Sub-label & Cost Line extending back -->
        <tr>
            <td class="sub-label" colspan="2">(Priority means most important to accomplish)</td>

            <td style="white-space: nowrap; text-align: left; padding-left: 10px;" class="bold">
                Cost:
            </td>
            <!-- Combining width to let the Cost line stretch and align with top right edge -->
            <td class="line">
                {{ $workorder->inhouse_cost }}
            </td>
        </tr>
    </table>
    @php
        // ~85 characters per line across 3 lines = ~255 total max length
        $wrappedInstructions = wordwrap($workorder->instructions ?? '', 130, "\n");
        $instructionLines = explode("\n", $wrappedInstructions, 3);
    @endphp

    <!-- Instructions Block -->
    <table style="width: 100%; margin-top: 10px; margin-left: 35px; margin-bottom: 15px;">
        <!-- Label Row -->
        <tr>
            <td style="padding-bottom: 4px;">
                <span class="bold">Instructions:</span>
                <span style="font-size: 10px;">(optional)</span>
            </td>
        </tr>

        <!-- Line 1 -->
        <tr>
            <td style="height: 18px; vertical-align: bottom;" class="line">
                {{ $instructionLines[0] ?? '' }}
            </td>
        </tr>

        <!-- Line 2 -->
        <tr>
            <td style="height: 18px; vertical-align: bottom;" class="line">
                {{ $instructionLines[1] ?? '' }}
            </td>
        </tr>

        <!-- Line 3 -->
        <tr>
            <td style="height: 18px; vertical-align: bottom;" class="line">
                {{ $instructionLines[2] ?? '' }}
            </td>
        </tr>
    </table>

    <!-- Section 3: Spare Parts -->
    <div class="section-divider"></div>
    <table>
        <tr>
            <td class="bold" style="width: 80%;">
                <span class="checkbox-symbol">&#9744;</span> Requested Spare Parts:
            </td>
            <td class="text-right bold" style="width: 20%;">Others: _________</td>
        </tr>
    </table>

    <table class="parts-table">
        <thead>
            <tr>
                <th style="width: 28%;">Parts/Supplies</th>
                <th style="width: 52%;">Description & Specification <span style="font-weight:normal; font-size: 8px;">(Brand name,size,code number, etc.)</span></th>
                <th style="width: 20%;">Quantity</th>
            </tr>
        </thead>
        <tbody>
            @php
                $spareParts = is_array($workorder->spare_parts)
                    ? $workorder->spare_parts
                    : json_decode($workorder->spare_parts ?? '[]', true);

                $maxRows = 5;
                $partsCount = count($spareParts ?? []);
            @endphp

            {{-- Loop through JSON items --}}
            @foreach ($spareParts as $part)
                <tr>
                    <td>{{ $part['name'] ?? $part['part'] ?? '' }}</td>
                    <td>{{ $part['description'] ?? $part['desc'] ?? '' }}</td>
                    <td class="text-center">{{ $part['quantity'] ?? $part['qty'] ?? '' }}</td>
                </tr>
            @endforeach

            {{-- Pad remaining empty rows to maintain Dompdf table height --}}
            @for ($i = $partsCount; $i < $maxRows; $i++)
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td class="text-center">&nbsp;</td>
                </tr>
            @endfor
        </tbody>
    </table>

    <!-- Section: Accomplishment Report -->
    <div class="section-divider"></div>
    <table style="width: 100%;">
        <tr>
            <td colspan="4" class="bold" style="padding-bottom: 20px">Accomplishment Report:</td>
        </tr>

        <!-- Dates Row -->
        <tr>
            <td style="width: 18%; white-space: nowrap; padding-left: 40px;">Date/hour started:</td>
            <td style="width: 32%;" class="line">{{ $workorder->started_at?->format('M d, Y h:i A') }}</td>

            <td style="width: 18%; white-space: nowrap; text-align: right; padding-right: 5px;">Date/Hour Finished:</td>
            <td style="width: 32%;" class="line">{{ $workorder->finished_at?->format('M d, Y h:i A') }}</td>
        </tr>

        @php
            $wrappedDetails = wordwrap($workorder->accomplishment_details ?? '', 120, "\n");
            $DetailsLines = explode("\n", $wrappedDetails, 2);
        @endphp

        <!-- Details Row -->
        <tr>
            <td style="white-space: nowrap; padding-left: 40px">Details (if any):</td>
            <td class="line" colspan="3">{{ $DetailsLines[0] ?? '' }}</td>
        </tr>
        <tr>
            <td style="padding-left: 40px" class="sub-label">(optional)</td>
            <td class="line" colspan="3">{{ $DetailsLines[1] ?? '' }}</td>
        </tr>
    </table>

    <br>

    <!-- Signatures Row (Fixed Alignment) -->
    <table style="width: 100%; margin-top: 10px;">
        <!-- Row 1: Labels & Lines -->
        <tr>
            <td style="width: 12%; white-space: nowrap; vertical-align: bottom;">Inspected by:</td>
            <td style="width: 32%; height: 18px; vertical-align: bottom;" class="line text-center">
                {{ $workorder->completedBy?->name }}
            </td>

            <td style="width: 12%;"></td> <!-- Middle gap -->

            <td style="width: 12%; white-space: nowrap; vertical-align: bottom;">Accepted by:</td>
            <td style="width: 32%; height: 18px; vertical-align: bottom;" class="line text-center">
                {{ $workorder->status->value === 'completed' ? $workorder->request?->requestedBy?->name : '' }}
            </td>
        </tr>

        <!-- Row 2: Sub-labels directly under lines -->
        <tr>
            <td></td> <!-- Spacer under 'Inspected by:' -->
            <td class="text-center bold" style="padding-top: 2px; white-space: nowrap;">Department Head</td>

            <td></td> <!-- Spacer under middle gap -->

            <td></td> <!-- Spacer under 'Accepted by:' -->
            <td class="text-center bold" style="padding-top: 2px; white-space: nowrap;">Requisitioner / Date</td>
        </tr>
    </table>

    <!-- Section 5: Vehicle Repairs -->
    <div class="section-divider"></div>
    <div class="bold" style="margin-bottom: 4px; margin-left: 35px;">
        <span class="checkbox-symbol">{!! $workorder->has_vehicle ? '&#9745;' : '&#9744;' !!}</span>
        Vehicle Repairs and Maintenance
    </div>

    <table style="width: 100%; margin-left: 35px;">
        <tr>
            <!-- Left sub-block (48%) -->
            <td style="width: 48%; vertical-align: top;">
                <table style="width: 100%; table-layout: fixed;">
                    <tr>
                        <td style="width: 35%;"><span class="checkbox-symbol">{!! $workorder->has_minor ? '&#9745;' : '&#9744;' !!}</span> Minor:</td>
                        <td style="width: 65%;" class="line">{{ $workorder->vehicle_minor_details ?? '' }}</td>
                    </tr>
                    <tr>
                        <td><span class="checkbox-symbol">{!! $workorder->has_major ? '&#9745;' : '&#9744;' !!}</span> Major:</td>
                        <td class="line">{{ $workorder->vehicle_major_details ?? '' }}</td>
                    </tr>
                    <tr>
                        <td colspan="2"><span class="checkbox-symbol">{!! $workorder->has_change_oil ? '&#9745;' : '&#9744;' !!}</span> Change Oil</td>
                    </tr>
                    <tr>
                        <td style="padding-left: 15px; white-space: nowrap;">Last Change Oil Date:</td>
                        <td class="line">{{ $workorder->last_change_oil_date?->format('M d, Y') }}</td>
                    </tr>
                    <tr>
                        <td style="padding-left: 15px; white-space: nowrap;">Meter Reading:</td>
                        <td class="line">{{ $workorder->meter_reading }}</td>
                    </tr>
                </table>
            </td>

            <!-- Spacer Gap -->
            <td style="width: 4%;"></td>

            <!-- Right sub-block (48%) -->
            <td style="width: 48%; vertical-align: top;">
                <table style="width: 100%; table-layout: fixed;">
                    <tr>
                        <td style="width: 30%;"><span class="checkbox-symbol">{!! $workorder->has_insurance ? '&#9745;' : '&#9744;' !!}</span> Insurance</td>
                        <td style="width: 30%; white-space: nowrap;" class="bold">Expiry Date:</td>
                        <td style="width: 40%;" class="line">{{ $workorder->insurance_date?->format('M d, Y') }}</td>
                    </tr>
                    <tr>
                        <td><span class="checkbox-symbol">{!! $workorder->has_registration ? '&#9745;' : '&#9744;' !!}</span> Registration</td>
                        <td style="white-space: nowrap;" class="bold">Expiry Date:</td>
                        <td class="line">{{ $workorder->registration_date?->format('M d, Y') }}</td>
                    </tr>

                    @php
                        // Pass true as the 4th parameter so wordwrap forces long strings to break
                        $wrappedOther = wordwrap($workorder->other_details ?? '', 35, "\n", true);
                        $otherLines = explode("\n", $wrappedOther, 2);
                    @endphp

                    <tr>
                        <td style="white-space: nowrap;"><span class="checkbox-symbol">{!! $workorder->has_other ? '&#9745;' : '&#9744;' !!}</span> Others:</td>
                        <td class="line" colspan="2" style="word-wrap: break-word; word-break: break-all;">{{ $otherLines[0] ?? '' }}</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td class="line" colspan="2" style="word-wrap: break-word; word-break: break-all;">{{ $otherLines[1] ?? '' }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- Footer Code -->
    <div class="footer-code">
        MCITI-RPMN-002 REV.1 2/15/2017
    </div>

</body>
</html>
