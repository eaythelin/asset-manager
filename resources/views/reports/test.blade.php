<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .company-name { font-size: 18px; font-weight: bold; }
        .report-title { font-size: 14px; color: #555; }
        .divider { border-top: 2px solid #333; margin: 10px 0; }
        table { width: 100%; border-collapse: collapse; }
        th { background-color: #3b5bdb; color: white; padding: 8px; text-align: left; }
        td { padding: 6px 8px; border-bottom: 1px solid #ddd; }
    </style>
</head>
<body>
    <div class="header">
        <p class="company-name">Master Coating Industrial Technology Incorporated</p>
        <p class="report-title">Asset Report</p>
        <p>Generated: {{ now()->format('M d, Y') }}</p>
    </div>
    <div class="divider"></div>

    <table>
        <thead>
            <tr>
                <th>Asset Code</th>
                <th>Asset Name</th>
                <th>Status</th>
                <th>Department</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>A</td>
                <td>B</td>
                <td>C</td>
                <td>D</td>
            </tr>
        </tbody>
    </table>
</body>
</html>