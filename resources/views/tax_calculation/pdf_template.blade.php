<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bản Quyết Toán Thuế TNCN Năm {{ $taxDeclaration->declaration_year }}</title>
    <style>
        /* Basic styling for PDF - keep it simple for better rendering */
        body {
            font-family: 'DejaVu Sans', sans-serif; /* Fallback for Vietnamese characters */
            margin: 40px;
            line-height: 1.6;
            color: #333;
        }
        h1, h2, h3, h4 {
            color: #2c5282; /* Tailwind's blue-800 */
        }
        h1 { font-size: 24px; text-align: center; margin-bottom: 20px; }
        h2 { font-size: 20px; border-bottom: 1px solid #eee; padding-bottom: 5px; margin-top: 30px; margin-bottom: 15px; }
        h3 { font-size: 18px; margin-bottom: 10px; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .mb-4 { margin-bottom: 16px; }
        .mt-4 { margin-top: 16px; }
        .text-red-700 { color: #c53030; } /* Tailwind's red-700 */
        .text-blue-700 { color: #2b6cb0; } /* Tailwind's blue-700 */
        .text-green-700 { color: #2f855a; } /* Tailwind's green-700 */
    </style>
    </head>
<body>
    <h1>BẢN QUYẾT TOÁN THUẾ THU NHẬP CÁ NHÂN</h1>
    <p style="text-align: center;">Năm tính thuế: <span class="font-bold">{{ $taxDeclaration->declaration_year }}</span></p>
    <p style="text-align: center;">Người khai báo: <span class="font-bold">{{ $taxDeclaration->user->name }}</span></p>
    <p style="text-align: center;">Ngày tạo: {{ \Carbon\Carbon::parse($taxDeclaration->created_at)->format('d/m/Y H:i:s') }}</p>

    <h2>I. Tổng hợp Thu nhập và Giảm trừ</h2>
    <table>
        <tr>
            <td>Tổng thu nhập gộp (chịu thuế)</td>
            <td class="text-right font-bold">{{ number_format($taxDeclaration->total_gross_income, 0, ',', '.') }} VND</td>
        </tr>
        <tr>
            <td>Tổng các khoản giảm trừ gia cảnh</td>
            <td class="text-right font-bold">{{ number_format($taxDeclaration->total_deductions, 0, ',', '.') }} VND</td>
        </tr>
        <tr>
            <td style="padding-left: 20px;">- Giảm trừ bản thân</td>
            <td class="text-right">{{ number_format($taxDeclaration->personal_deduction, 0, ',', '.') }} VND</td>
        </tr>
        <tr>
            <td style="padding-left: 20px;">- Giảm trừ người phụ thuộc</td>
            <td class="text-right">{{ number_format($taxDeclaration->dependent_deduction, 0, ',', '.') }} VND</td>
        </tr>
        <tr>
            <td style="padding-left: 20px;">- Giảm trừ bảo hiểm bắt buộc</td>
            <td class="text-right">{{ number_format($taxDeclaration->insurance_deduction, 0, ',', '.') }} VND</td>
        </tr>
        <tr>
            <td style="padding-left: 20px;">- Giảm trừ từ thiện, nhân đạo, khuyến học</td>
            <td class="text-right">{{ number_format($taxDeclaration->charitable_deduction, 0, ',', '.') }} VND</td>
        </tr>
        <tr>
            <td>Thu nhập tính thuế (sau giảm trừ)</td>
            <td class="text-right font-bold">{{ number_format($taxDeclaration->tax_base_income, 0, ',', '.') }} VND</td>
        </tr>
    </table>

    <h2>II. Kết quả Thuế</h2>
    <table>
        <tr>
            <td>Tổng số thuế phải nộp trong năm</td>
            <td class="text-right font-bold text-green-700">{{ number_format($taxDeclaration->total_tax_payable, 0, ',', '.') }} VND</td>
        </tr>
        <tr>
            <td>Tổng số thuế đã khấu trừ tại nguồn</td>
            <td class="text-right font-bold">{{ number_format($taxDeclaration->total_tax_withheld, 0, ',', '.') }} VND</td>
        </tr>
        <tr>
            <td>Chênh lệch thuế (Nộp thêm / Hoàn lại)</td>
            <td class="text-right font-bold">
                @if ($taxDeclaration->tax_difference > 0)
                    <span class="text-red-700">{{ number_format($taxDeclaration->tax_difference, 0, ',', '.') }} VND (Phải nộp thêm)</span>
                @elseif ($taxDeclaration->tax_difference < 0)
                    <span class="text-blue-700">{{ number_format(abs($taxDeclaration->tax_difference), 0, ',', '.') }} VND (Được hoàn lại)</span>
                @else
                    <span>{{ number_format($taxDeclaration->tax_difference, 0, ',', '.') }} VND (Không có chênh lệch)</span>
                @endif
            </td>
        </tr>
    </table>

    @if (!empty($details))
        <h2>III. Chi tiết các Nguồn Thu nhập</h2>
        <table>
            <thead>
                <tr>
                    <th>Nguồn Thu nhập</th>
                    <th>Loại</th>
                    <th>Thu nhập chịu thuế</th>
                    <th>Thuế đã khấu trừ</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($details as $item)
                    <tr>
                        <td>{{ $item['source_name'] }}</td>
                        <td>{{ str_replace('_', ' ', $item['type']) }}</td>
                        <td class="text-right">{{ number_format($item['total_taxable_income'], 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($item['tax_withheld'], 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <p style="font-style: italic; font-size: 12px; margin-top: 30px;">
        Đây là bản quyết toán thuế thu nhập cá nhân mang tính tham khảo. Vui lòng kiểm tra lại thông tin và tham khảo ý kiến chuyên gia thuế để có kết quả chính xác nhất theo quy định pháp luật hiện hành.
    </p>
</body>
</html>