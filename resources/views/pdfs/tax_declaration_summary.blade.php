<!DOCTYPE html>
<html>
<head>
    <title>Khai Báo Thuế TNCN Tháng {{ $declaration->declaration_month }}/{{ $declaration->declaration_year }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        /* Define your PDF specific CSS here */
        body {
            font-family: 'DejaVu Sans', sans-serif; /* Quan trọng cho việc hiển thị tiếng Việt */
            font-size: 12px;
            line-height: 1.6;
            margin: 20px;
        }
        h1, h2, h3 {
            color: #333;
            text-align: center;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header img {
            max-width: 150px;
            margin-bottom: 10px;
        }
        .info-section {
            border: 1px solid #eee;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            background-color: #f9f9f9;
        }
        .info-item {
            margin-bottom: 5px;
        }
        .info-item strong {
            display: inline-block;
            width: 150px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .summary-table {
            width: 60%;
            margin: 0 auto; /* Center the table */
        }
        .summary-table td:first-child {
            font-weight: bold;
        }
        .summary-table td:last-child {
            text-align: right;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            font-size: 10px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="header">
        {{-- <img src="{{ public_path('images/logo.png') }}" alt="Company Logo"> --}}
        <h1>Báo Cáo Thuế Thu Nhập Cá Nhân</h1>
        <h2>Kỳ Khai Báo: Tháng {{ $declaration->declaration_month }}/{{ $declaration->declaration_year }}</h2>
    </div>

    <div class="info-section">
        <h3>Thông tin người nộp thuế</h3>
        <div class="info-item"><strong>Họ và tên:</strong> {{ $declaration->user->name }}</div>
        <div class="info-item"><strong>Email:</strong> {{ $declaration->user->email }}</div>
        {{-- <div class="info-item"><strong>Mã số thuế cá nhân:</strong> {{ $declaration->user->personal_tax_code ?? 'N/A' }}</div> --}}
    </div>

    <div class="info-section">
        <h3>Thông tin khai báo</h3>
        <table class="summary-table">
            <tr>
                <td>Tổng thu nhập chịu thuế:</td>
                <td>{{ number_format($declaration->total_income, 0, ',', '.') }} VNĐ</td>
            </tr>
            <tr>
                <td>Tổng các khoản giảm trừ:</td>
                <td>{{ number_format($declaration->total_deductions, 0, ',', '.') }} VNĐ</td>
            </tr>
            <tr>
                <td>Số người phụ thuộc:</td>
                <td>{{ $declaration->user->dependents()->count() }} người</td>
            </tr>
            <tr>
                <td>Thu nhập tính thuế:</td>
                <td>{{ number_format($declaration->taxable_income, 0, ',', '.') }} VNĐ</td>
            </tr>
            <tr>
                <td><strong>Thuế TNCN phải nộp:</strong></td>
                <td><strong>{{ number_format($declaration->calculated_tax, 0, ',', '.') }} VNĐ</strong></td>
            </tr>
        </table>
    </div>

    <h3>Chi tiết thu nhập trong tháng</h3>
    @if($incomeSources->isNotEmpty())
        <table>
            <thead>
                <tr>
                    <th>Tên nguồn thu nhập</th>
                    <th>Số tiền (VNĐ/tháng)</th>
                    <th>Mô tả</th>
                </tr>
            </thead>
            <tbody>
                @foreach($incomeSources as $source)
                    <tr>
                        <td>{{ $source->name }}</td>
                        <td>{{ number_format($source->amount, 0, ',', '.') }}</td>
                        <td>{{ $source->description ?? 'Không có' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>Không có nguồn thu nhập nào được khai báo trong tháng này.</p>
    @endif

    <h3>Chi tiết người phụ thuộc</h3>
    @if($dependents->isNotEmpty())
        <table>
            <thead>
                <tr>
                    <th>Họ và tên</th>
                    <th>Ngày sinh</th>
                    <th>Quan hệ</th>
                    <th>Mã số thuế (nếu có)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($dependents as $dependent)
                    <tr>
                        <td>{{ $dependent->name }}</td>
                        <td>{{ $dependent->date_of_birth->format('d/m/Y') }}</td>
                        <td>{{ $dependent->relationship }}</td>
                        <td>{{ $dependent->tax_code ?? 'N/A' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>Không có người phụ thuộc nào được khai báo.</p>
    @endif

    <div class="footer">
        <p>Báo cáo này được tạo tự động bởi ứng dụng Thuế TNCN của bạn vào ngày {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}.</p>
    </div>
</body>
</html>