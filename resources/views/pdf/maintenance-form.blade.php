<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Repair and Maintenance Request Form</title>
    <style>
        @page {
            margin: 15px 45px;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 8.5pt;
            color: #000;
            line-height: 1.15;
        }
        .bold { font-weight: bold; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .nowrap { white-space: nowrap; }

        .title {
            font-size: 13pt;
            font-weight: bold;
            text-align: center;
            margin-bottom: 6px;
        }

        /* Layout Tables */
        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        td {
            padding: 1px 2px;
            vertical-align: bottom;
        }

        /* Fillable Lines using TD borders */
        .fill-line {
            border-bottom: 1px solid #000;
        }

        /* Checkboxes & Control Box */
        .control-box {
            border: 2px solid #000;
            height: 18px;
            width: 100px;
            float: right;
        }
        .checkbox {
            width: 9px;
            height: 9px;
            border: 1px solid #000;
            display: inline-block;
            vertical-align: middle;
            margin-right: 3px;
        }

        /* Dividers */
        .divider {
            border-top: 1px solid #000;
            margin: 4px 0;
        }

        /* Grid Table for Spare Parts */
        .grid-table {
            border: 2px solid #000;
            margin-top: 3px;
        }
        .grid-table th, .grid-table td {
            border: 1px solid #000;
            padding: 2px 4px;
            vertical-align: middle;
        }
        .grid-table th {
            text-align: left;
            font-weight: bold;
        }

        .footer {
            font-size: 8pt;
            font-weight: bold;
            text-align: right;
            margin-top: 6px;
        }
    </style>
</head>
<body>

    <!-- Header Title -->
    <div class="title">Repair and Maintenance Request Form (RMRF)</div>

    <!-- Control Number -->
    <table style="margin-bottom: 2px;">
        <tr>
            <td style="width: 65%;"></td>
            <td class="nowrap text-right bold" style="width: 18%; vertical-align: middle;">Control Number:</td>
            <td style="width: 17%; text-align: right; vertical-align: middle;">
                <div class="control-box"></div>
            </td>
        </tr>
    </table>

    <!-- Header Fields -->
    <table>
        <tr>
            <td class="nowrap" style="width: 16%;">Requisitioner's name:</td>
            <td class="fill-line" style="width: 42%;"></td>
            <td class="nowrap text-right" style="width: 9%;">Sec/Dept:</td>
            <td class="fill-line" style="width: 13%;"></td>
            <td class="nowrap text-right" style="width: 5%;">Date:</td>
            <td class="fill-line" style="width: 15%;"></td>
        </tr>
    </table>

    <table style="margin-top: 1px;">
        <tr>
            <td class="nowrap" style="width: 19%;">Equipment/Vehicle Type:</td>
            <td class="fill-line" style="width: 31%;"></td>
            <td class="nowrap text-right" style="width: 15%;">Type of request:</td>
            <td class="nowrap text-right" style="width: 35%;">
                <span class="checkbox"></span> Repair &nbsp;
                <span class="checkbox"></span> Fabrication &nbsp;
                <span class="checkbox"></span> Maintenance
            </td>
        </tr>
    </table>

    <table style="margin-top: 1px;">
        <tr>
            <td class="nowrap" style="width: 23%;">Request Description/Plate No.:</td>
            <td class="fill-line" style="width: 77%;"></td>
        </tr>
    </table>

    <!-- Signatures Header -->
    <table style="margin-top: 6px;">
        <tr>
            <td style="width: 50%;">
                Requested by:<br><br>
                <table style="width: 80%;">
                    <tr><td class="fill-line"></td></tr>
                    <tr><td class="bold text-center" style="font-size: 8pt;">Signature</td></tr>
                </table>
            </td>
            <td style="width: 50%;">
                Request approved by:<br><br>
                <table style="width: 80%;">
                    <tr><td class="fill-line"></td></tr>
                    <tr><td class="bold text-center" style="font-size: 8pt;">Department Head</td></tr>
                </table>
            </td>
        </tr>
    </table>

    <div class="divider"></div>

    <!-- Subcontractor Section -->
    <div>
        <span class="checkbox"></span>
        <strong style="font-size: 9pt;">Repair by Subcontractor</strong>
    </div>

    <table style="margin-top: 2px;">
        <tr>
            <!-- Static left label -->
            <td class="nowrap" style="width: 20%; vertical-align: top; padding-top: 2px;">
                Subcontractor Details:
            </td>
            <!-- Nested grid that handles all 4 lines cleanly -->
            <td style="width: 80%; vertical-align: top; padding: 0;">
                <table style="width: 100%;">
                    <tr>
                        <td class="nowrap" style="width: 20%;">Name/Company:</td>
                        <td class="fill-line" style="width: 50%;"></td>
                        <td class="nowrap text-right" style="width: 10%;">Cost:</td>
                        <td class="fill-line" style="width: 20%;"></td>
                    </tr>
                    <tr>
                        <td class="nowrap">Request Document:</td>
                        <td class="fill-line"></td>
                        <td class="nowrap text-right">Date Released:</td>
                        <td class="fill-line"></td>
                    </tr>
                    <tr>
                        <td class="nowrap" style="vertical-align: top;">
                            Details of Activities:<br>
                            <span style="font-size: 7pt;">(Optional)</span>
                        </td>
                        <td class="fill-line" style="vertical-align: top;"></td>
                        <td class="nowrap text-right" style="vertical-align: top;">Date returned:</td>
                        <td class="fill-line" style="vertical-align: top;"></td>
                    </tr>
                    <tr>
                        <td colspan="4" class="fill-line" style="height: 10px;"></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <div class="divider"></div>

    <!-- In House Maintenance -->
    <div>
        <span class="checkbox"></span>
        <strong style="font-size: 9pt;">In House Maintenance</strong>
    </div>
    <table style="margin-top: 2px;">
        <tr>
            <td class="nowrap" style="width: 22%;">Assigned maintenance crew(s)</td>
            <td class="fill-line" style="width: 78%;"></td>
        </tr>
    </table>
    <table style="margin-top: 2px;">
        <tr>
            <td class="nowrap" style="width: 13%;">PRIORITY level:</td>
            <td class="fill-line" style="width: 32%;"></td>
            <td class="nowrap text-right" style="width: 25%;">Estimated day(s) Hour(s):</td>
            <td class="fill-line" style="width: 30%;"></td>
        </tr>
        <tr>
            <td colspan="2" style="font-size: 7pt; vertical-align: top;">(Priority means most important to acomplish)</td>
            <td class="nowrap text-right">Cost:</td>
            <td class="fill-line"></td>
        </tr>
    </table>
    <table style="margin-top: 2px;">
        <tr>
            <td class="nowrap" style="width: 11%;">Instructions: <span style="font-size: 7pt;">(optional)</span></td>
            <td class="fill-line" style="width: 89%;"></td>
        </tr>
        <tr>
            <td colspan="2" class="fill-line" style="height: 10px;"></td>
        </tr>
    </table>

    <div class="divider"></div>

    <!-- Requested Spare Parts -->
    <table>
        <tr>
            <td style="width: 50%;">
                <span class="checkbox"></span>
                <strong style="font-size: 9pt;">Requested Spare Parts:</strong>
            </td>
            <td class="text-right" style="width: 50%;">Others:</td>
        </tr>
    </table>

    <table class="grid-table">
        <thead>
            <tr>
                <th style="width: 26%;">Parts/Supplies</th>
                <th style="width: 56%;">Description & Specification <span style="font-size: 7pt; font-weight: normal;">(Brand name,size,code number, etc.)</span></th>
                <th style="width: 18%;">Quantity</th>
            </tr>
        </thead>
        <tbody>
            <tr><td style="height: 13px;"></td><td></td><td></td></tr>
            <tr><td style="height: 13px;"></td><td></td><td></td></tr>
            <tr><td style="height: 13px;"></td><td></td><td></td></tr>
            <tr><td style="height: 13px;"></td><td></td><td></td></tr>
            <tr><td style="height: 13px;"></td><td></td><td></td></tr>
        </tbody>
    </table>

    <div class="divider"></div>

    <!-- Accomplishment Report -->
    <div class="bold">Accomplishment Report:</div>
    <table style="margin-top: 2px;">
        <tr>
            <td class="nowrap" style="width: 15%;">Date/hour started:</td>
            <td class="fill-line" style="width: 35%;"></td>
            <td class="nowrap text-right" style="width: 15%;">Date/Hour Finished:</td>
            <td class="fill-line" style="width: 35%;"></td>
        </tr>
        <tr>
            <td class="nowrap" style="vertical-align: top;">Detailes (if nay):<br><span style="font-size: 7pt;">(optional)</span></td>
            <td colspan="3" class="fill-line" style="height: 14px;"></td>
        </tr>
    </table>

    <br>

    <table>
        <tr>
            <td style="width: 50%;">
                <table style="width: 90%;">
                    <tr>
                        <td class="nowrap" style="width: 22%;">Inspected by:</td>
                        <td class="fill-line" style="width: 78%;"></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td class="bold text-center" style="font-size: 8pt;">Department Head</td>
                    </tr>
                </table>
            </td>
            <td style="width: 50%;">
                <table style="width: 90%;">
                    <tr>
                        <td class="nowrap" style="width: 22%;">Accepted by:</td>
                        <td class="fill-line" style="width: 78%;"></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td class="bold text-center" style="font-size: 8pt;">Requisitioner /Date</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <div class="divider"></div>

    <!-- Vehicle Repairs and Maintenance -->
    <div>
        <span class="checkbox"></span>
        <strong style="font-size: 9pt;">Vehicle Repairs and Maintenance</strong>
    </div>

    <table style="margin-top: 2px;">
        <tr>
            <!-- Left Column -->
            <td style="width: 48%; vertical-align: top; padding: 0;">
                <table style="width: 100%;">
                    <tr>
                        <td class="nowrap" style="width: 35%;"><span class="checkbox"></span> Minor:</td>
                        <td style="width: 20%;"></td>
                        <td class="fill-line" style="width: 45%;"></td>
                    </tr>
                    <tr>
                        <td class="nowrap"><span class="checkbox"></span> Major:</td>
                        <td></td>
                        <td class="fill-line"></td>
                    </tr>
                    <tr>
                        <td class="nowrap"><span class="checkbox"></span> Change Oil</td>
                        <td></td>
                        <td class="fill-line"></td>
                    </tr>
                    <tr>
                        <td class="nowrap" colspan="2" style="padding-left: 12px;">Last Change Oil Date:</td>
                        <td class="fill-line"></td>
                    </tr>
                    <tr>
                        <td class="nowrap" colspan="2" style="padding-left: 12px;">Meter Reading:</td>
                        <td class="fill-line"></td>
                    </tr>
                </table>
            </td>

            <td style="width: 4%;"></td>

            <!-- Right Column -->
            <td style="width: 48%; vertical-align: top; padding: 0;">
                <table style="width: 100%;">
                    <tr>
                        <td class="nowrap" style="width: 25%;"><span class="checkbox"></span> Insurance</td>
                        <td class="bold nowrap" style="width: 25%;">Expiry Date:</td>
                        <td class="fill-line" style="width: 50%;"></td>
                    </tr>
                    <tr>
                        <td class="nowrap"><span class="checkbox"></span> Registration</td>
                        <td class="bold nowrap">Expiry Date:</td>
                        <td class="fill-line"></td>
                    </tr>
                    <tr>
                        <td class="nowrap"><span class="checkbox"></span> Others:</td>
                        <td></td>
                        <td class="fill-line"></td>
                    </tr>
                    <tr>
                        <td colspan="2"></td>
                        <td class="fill-line" style="height: 10px;"></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <div class="footer">MCITI-RPMN-002 REV.1 2/15/2017</div>

</body>
</html>
