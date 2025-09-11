<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: 'tajawal', sans-serif;
            direction: rtl;
            text-align: right;
            color: #0f172a;
            font-weight: 500
        }

        table {
            width: 100%;
            border-collapse: collapse
        }

        td,
        th {
            padding: 5px;
            vertical-align: top;
        }

        h5,
        p,
        strong {
            margin: 0
        }

        .text-primary {
            color: #0f172a;
        }

        .text-secondary {
            color: #94a3b8;
        }

        .line-items-table th,
        .line-items-table td {
            border-bottom: 1px solid #94a3b8
        }
    </style>
</head>

<body>
    {{-- Head --}}
    <table>
        <tbody>
            <tr>
                <td align="right">
                    <h5 style="font-size:xx-large" class="text-primary">قيد يدوي</h5>
                    <h5 style="font-size:x-large" class="text-secondary">Manual Journal</h5>

                    <div>
                        <br />
                        <strong>الرقم التسلسلي</strong>
                        <p class="text-secondary">Serial Number</p>
                        <p>{{ $id }}</p>
                    </div>
                    <div>
                        <br />
                        <strong>التاريخ</strong>
                        <p class="text-secondary">Date</p>
                        <p>{{ $date }}</p>
                    </div>
                </td>
                <td align="left">

                    <br />
                    <br />
                    <strong style="font-size:large">{{ $company['name'] }}</strong>
                    <br />

                    @if ($company['building_number'] && $company['street'] && $company['district'])
                        <p>{{ $company['building_number'] }}, {{ $company['street'] }}, {{ $company['district'] }}</p>
                    @endif

                    @if ($company['city'] && $company['postal_code'])
                        <p>{{ $company['city'] }}, {{ $company['postal_code'] }}</p>
                    @endif

                    <p>المملكة العربية السعودية</p>

                    @if ($company['email'])
                        <p>{{ $company['email'] }}</p>
                    @endif

                    @if ($company['phone'])
                        <p>{{ $company['phone'] }}</p>
                    @endif

                    @if ($company['tax_number'])
                        <strong>رقم التسجيل الضريبي</strong>
                        <p class="text-secondary">VAT Number</p>
                        <p>{{ $company['tax_number'] }}</p>
                    @endif

                    @if ($company['commercial_number'])
                        <strong>رقم السجل التجاري</strong>
                        <p class="text-secondary">Commercial Registration Number</p>
                        <p>{{ $company['commercial_number'] }}</p>
                    @endif
                </td>
            </tr>
        </tbody>
    </table>

    <br />
    <br />


    {{-- Line Items --}}
    <table class="line-items-table" style="width:100%">
        <thead>
            <tr>
                <th>
                    <strong>الوصف</strong>
                    <p class="text-secondary" style="font-weight: 400">Description</p>
                </th>
                <th>
                    <strong>الحساب</strong>
                    <p class="text-secondary" style="font-weight: 400">Account</p>
                </th>
                <th>
                    <strong>القيمة المضافة</strong>
                    <p class="text-secondary" style="font-weight: 400">VAT</p>
                </th>
                <th>
                    <strong>العملة</strong>
                    <p class="text-secondary" style="font-weight: 400">Currency</p>
                </th>
                <th>
                    <strong>مدين (ريال سعودي)</strong>
                    <p class="text-secondary" style="font-weight: 400">Debit </p>
                </th>
                <th>
                    <strong>دائن (ريال سعودي)</strong>
                    <p class="text-secondary" style="font-weight: 400">Credit </p>
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($line_items as $item)
                <tr>
                    <td>{{ $item['description'] ?? '—' }}</td>
                    <td>{{ $item['account_id'] ?? '—' }}</td>
                    <td>{{ $item['vat'] ?? '—' }}</td>
                    <td>{{ $item['currency'] ?? '—' }}</td>
                    <td>{{ $item['debit_dc'] ?? '—' }}</td>
                    <td>{{ $item['credit_dc'] ?? '—' }}</td>
                </tr>
            @endforeach

            <tr>
                <td style="border: none"></td>
                <td style="border: none"></td>
                <td style="border: none"></td>
                <td style="border: none">
                    <div> <strong>الإجمالي</strong>
                    </div>
                    <strong class="text-secondary">Total</strong>
                </td>
                <td style="border: none">{{ number_format($total_debit, 2) }}</td>
                <td style="border: none">{{ number_format($total_credit, 2) }}</td>
            </tr>
            <tr>
                <td style="border: none"></td>
                <td style="border: none"></td>
                <td style="border: none"></td>
                <td style="border: none">
                    <div> <strong>الرصيد</strong>
                    </div>
                    <strong class="text-secondary">Balance</strong>
                </td>
                <td style="border: none">0.00</td>
                <td style="border: none">0.00</td>
            </tr>
        </tbody>
    </table>

    <br />
    <br />

    {{-- Notes --}}
    <div>
        <strong>ملاحظات</strong>
        <p class="text-secondary">Notes</p>
        <p>
            {{ $notes }}
        </p>
    </div>

</body>

</html>
