<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sub-Lease Agreement</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            padding: 40px;
        }
        h1 {
            text-align: center;
            font-size: 18px;
            margin-bottom: 10px;
        }
        .subtitle {
            text-align: center;
            font-size: 11px;
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #000;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        .section-title {
            background-color: #f0f0f0;
            font-weight: bold;
            padding: 8px;
            margin-top: 20px;
        }
        .footer {
            margin-top: 40px;
            font-size: 10px;
            text-align: center;
        }
        .signature-section {
            margin-top: 50px;
        }
        .signature-box {
            display: inline-block;
            width: 45%;
            vertical-align: top;
        }
    </style>
</head>
<body>
    <h1>SUB-LEASE AGREEMENT</h1>
    <p class="subtitle">
        This Addendum shall be part of Tenancy Contract dated - for the Unit # 
        <?= htmlspecialchars($contract['lease_details']['unit_name'] ?? 'N/A') ?> located at 
        <?= htmlspecialchars($contract['lease_details']['property_address'] ?? 'CITY AVENUE PORT SAEED CENTER L.L.C') ?>
    </p>

    <!-- First Party -->
    <div class="section-title">First Party</div>
    <table>
        <tr>
            <th width="30%">Landlord</th>
            <td><?= htmlspecialchars($contract['tenant']['owner_name'] ?? 'Ahmed Khan') ?></td>
        </tr>
        <tr>
            <th>Trade Lic No./Initial Approval</th>
            <td><?= htmlspecialchars($contract['trade_lic_no']) ?></td>
        </tr>
        <tr>
            <th>Email</th>
            <td><?= htmlspecialchars($contract['tenant']['owner_email'] ?? $contract['email']) ?></td>
        </tr>
    </table>

    <!-- Second Party -->
    <div class="section-title">Second Party</div>
    <table>
        <tr>
            <th width="30%">Tenant</th>
            <td><?= htmlspecialchars($contract['first_name']) ?></td>
        </tr>
        <tr>
            <th>Trade Lic No./Initial Approval</th>
            <td><?= htmlspecialchars($contract['trade_lic_no']) ?></td>
        </tr>
        <tr>
            <th>Represented By</th>
            <td><?= htmlspecialchars($contract['first_name']) ?></td>
        </tr>
        <tr>
            <th>Passport No/Emirates Id</th>
            <td><?= htmlspecialchars($contract['passport_no']) ?></td>
        </tr>
        <tr>
            <th>Nationality</th>
            <td><?= htmlspecialchars($contract['nationality']) ?></td>
        </tr>
        <tr>
            <th>P.O Box no.</th>
            <td><?= htmlspecialchars($contract['po_box']) ?></td>
        </tr>
        <tr>
            <th>TRN No.</th>
            <td><?= htmlspecialchars($contract['trn_no'] ?? 'N/A') ?></td>
        </tr>
        <tr>
            <th>Mobile</th>
            <td><?= htmlspecialchars($contract['mobile']) ?></td>
        </tr>
    </table>

    <!-- Property & Lease Details -->
    <table>
        <tr>
            <th width="30%">A. PROPERTY DETAILS</th>
            <td>
                <?= htmlspecialchars($contract['lease_details']['property_address'] ?? 'Office Unit B-1203') ?><br>
                <?= htmlspecialchars($contract['lease_details']['palm_avenue'] ?? '45 Palm Avenue') ?>
            </td>
        </tr>
        <tr>
            <th>B. Lease Period (Extension)</th>
            <td>
                Commencing Date: <?= htmlspecialchars($contract['lease_details']['commencing_date'] ?? 'N/A') ?><br>
                Expiration Date: <?= date('d/m/Y', strtotime($contract['contract_end'])) ?>
            </td>
        </tr>
    </table>

    <!-- Payment & Charges -->
    <div class="section-title">PAYMENT AND CHARGES</div>
    <table>
        <tr>
            <th width="30%">C. Rent Amount</th>
            <td><?= htmlspecialchars($contract['rental_details']['rent_amount'] ?? '12000/- AED / (DIRHAMS ONLY) 1 year') ?></td>
        </tr>
        <tr>
            <th>D. Special Discount</th>
            <td><?= htmlspecialchars($contract['rental_details']['discount'] ?? '-AED / (DIRHAMS ONLY) 1 year') ?></td>
        </tr>
        <tr>
            <th>E. Contract Value</th>
            <td><?= htmlspecialchars($contract['rental_details']['contract_value'] ?? '12,000 AED / (DIRHAMS ONLY) 1 year') ?></td>
        </tr>
        <tr>
            <th>F. VAT</th>
            <td><?= htmlspecialchars($contract['rental_details']['vat'] ?? '601 AED') ?></td>
        </tr>
        <tr>
            <th>G. No. of Payment</th>
            <td><?= htmlspecialchars($contract['rental_details']['payment_count'] ?? '0') ?></td>
        </tr>
        <tr>
            <th>H. Mode of Payment</th>
            <td><?= htmlspecialchars($contract['rental_details']['payment_mode'] ?? 'CASH ☐ CHEQUE ☐ BANK ☐') ?></td>
        </tr>
    </table>

    <!-- Terms of Payment -->
    <div class="section-title">I. Terms of Payment</div>
    <table>
        <tr>
            <th width="30%">J. Management Fee</th>
            <td>
                cash | null | Date: <?= htmlspecialchars($contract['rental_details']['mgmt_fee_date_1'] ?? '25/11/2025') ?> | 
                Amount: <?= htmlspecialchars($contract['rental_details']['mgmt_fee_amount_1'] ?? 'AED') ?> | 10/-
            </td>
        </tr>
        <tr>
            <th>K. Contract Charges</th>
            <td>
                cash | null | Date: <?= htmlspecialchars($contract['rental_details']['contract_charge_date'] ?? '25/11/2025') ?> | 
                Amount: <?= htmlspecialchars($contract['rental_details']['contract_charge_amount'] ?? 'AED') ?> | 10/-
            </td>
        </tr>
        <tr>
            <th>L. Security Deposit</th>
            <td><?= htmlspecialchars($contract['rental_details']['security_deposit'] ?? '10 AED / (DIRHAMS ONLY)') ?></td>
        </tr>
    </table>

    <p style="margin-top: 30px; font-size: 11px;">
        The parties further knowledge and accept all terms as if set forth herein, and that this agreement 
        shall be binding the binding the Party's successors, assignees and representatives.
    </p>

    <p style="font-size: 11px;">
        Ahmed Khan shall not be responsible in any financial liabilities of the tenant. This contract is 
        between Ahmed Khan & <?= htmlspecialchars($contract['first_name']) ?>
    </p>

    <!-- Signature Section -->
    <div class="signature-section">
        <table style="border: none;">
            <tr>
                <td width="50%" style="border: none;">
                    <strong>Landlord:</strong> <?= htmlspecialchars($contract['tenant']['owner_name'] ?? 'Ahmed Khan') ?><br><br>
                    <strong>Signature:</strong> _________________<br>
                    <strong>Date:</strong> _________________
                </td>
                <td width="50%" style="border: none;">
                    <strong>Tenant:</strong> <?= htmlspecialchars($contract['first_name']) ?><br><br>
                    <strong>Signature:</strong> _________________<br>
                    <strong>Date:</strong> _________________
                </td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>Contract Status: <strong><?= htmlspecialchars($contract['status']) ?></strong></p>
        <p>Generated on <?= date('d/m/Y H:i:s') ?></p>
    </div>
</body>
</html>
