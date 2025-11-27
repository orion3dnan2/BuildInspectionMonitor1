<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طباعة القيد - {{ $book->book_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            direction: rtl;
            background: white;
            color: #1a202c;
            padding: 20mm;
            font-size: 14px;
            line-height: 1.6;
        }
        .header {
            text-align: center;
            border-bottom: 3px double #1a365d;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            font-size: 24px;
            color: #1a365d;
            margin-bottom: 10px;
        }
        .header p {
            color: #4a5568;
        }
        .document-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            padding: 15px;
            background: #f7fafc;
            border-radius: 8px;
        }
        .document-info div {
            text-align: center;
        }
        .document-info label {
            display: block;
            font-size: 12px;
            color: #718096;
            margin-bottom: 5px;
        }
        .document-info span {
            font-weight: bold;
            color: #1a365d;
        }
        .content-section {
            margin-bottom: 25px;
        }
        .content-section h3 {
            font-size: 16px;
            color: #1a365d;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 8px;
            margin-bottom: 15px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }
        .info-item {
            padding: 10px;
            background: #f7fafc;
            border-radius: 6px;
        }
        .info-item label {
            display: block;
            font-size: 12px;
            color: #718096;
            margin-bottom: 3px;
        }
        .info-item span {
            color: #1a202c;
        }
        .full-width {
            grid-column: span 2;
        }
        .description-box {
            padding: 15px;
            background: #f7fafc;
            border-radius: 6px;
            white-space: pre-wrap;
            min-height: 100px;
        }
        .approval-section {
            margin-top: 40px;
            padding: 20px;
            border: 2px solid #38a169;
            border-radius: 8px;
            background: #f0fff4;
        }
        .approval-section h3 {
            color: #22543d;
            margin-bottom: 15px;
        }
        .signature-box {
            display: flex;
            align-items: flex-start;
            gap: 30px;
        }
        .signature-details {
            flex: 1;
        }
        .signature-details p {
            margin-bottom: 8px;
        }
        .signature-details label {
            color: #718096;
            font-size: 12px;
        }
        .signature-image {
            text-align: center;
        }
        .signature-image img {
            max-width: 200px;
            border: 1px solid #c6f6d5;
            border-radius: 4px;
            padding: 10px;
            background: white;
        }
        .signature-image p {
            font-size: 11px;
            color: #718096;
            margin-top: 5px;
        }
        .verification-code {
            margin-top: 15px;
            padding: 10px;
            background: #e6fffa;
            border-radius: 4px;
            font-family: monospace;
            font-size: 11px;
            color: #234e52;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            font-size: 11px;
            color: #a0aec0;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
        }
        .status-approved {
            background: #c6f6d5;
            color: #22543d;
        }
        @media print {
            body {
                padding: 10mm;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 20px; text-align: center;">
        <button onclick="window.print()" style="padding: 10px 30px; background: #1a365d; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">
            طباعة
        </button>
        <button onclick="window.close()" style="padding: 10px 30px; background: #718096; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; margin-right: 10px;">
            إغلاق
        </button>
    </div>

    <div class="header">
        <h1>نظام إدارة الرقابة والتفتيش</h1>
        <p>دفتر القيد - وثيقة رسمية</p>
    </div>

    <div class="document-info">
        <div>
            <label>رقم القيد</label>
            <span>{{ $book->book_number }}</span>
        </div>
        <div>
            <label>نوع الكتاب</label>
            <span>{{ $book->book_type_label }}</span>
        </div>
        <div>
            <label>تاريخ الكتابة</label>
            <span>{{ $book->date_written->format('Y/m/d') }}</span>
        </div>
        <div>
            <label>الحالة</label>
            <span class="status-badge status-approved">{{ $book->status_label }}</span>
        </div>
    </div>

    <div class="content-section">
        <h3>بيانات الكتاب</h3>
        <div class="info-grid">
            <div class="info-item full-width">
                <label>عنوان الكتاب</label>
                <span>{{ $book->book_title }}</span>
            </div>
            <div class="info-item">
                <label>اسم الكاتب</label>
                <span>{{ $book->writer_name }}</span>
            </div>
            <div class="info-item">
                <label>رتبة الكاتب</label>
                <span>{{ $book->writer_rank ?? '-' }}</span>
            </div>
            <div class="info-item">
                <label>مكتب الكاتب</label>
                <span>{{ $book->writer_office ?? '-' }}</span>
            </div>
            <div class="info-item">
                <label>تاريخ الإنشاء</label>
                <span>{{ $book->created_at->format('Y/m/d H:i') }}</span>
            </div>
        </div>
    </div>

    @if($book->description)
    <div class="content-section">
        <h3>الوصف</h3>
        <div class="description-box">{{ $book->description }}</div>
    </div>
    @endif

    @if($book->status == 'approved' && $book->latestSignature)
    <div class="approval-section">
        <h3>الاعتماد والتوقيع الإلكتروني</h3>
        <div class="signature-box">
            <div class="signature-details">
                <p>
                    <label>معتمد بواسطة:</label><br>
                    <strong>{{ $book->approver->name ?? 'غير معروف' }}</strong>
                </p>
                <p>
                    <label>تاريخ الاعتماد:</label><br>
                    {{ $book->approved_at?->format('Y/m/d H:i') }}
                </p>
                @if($book->manager_comment)
                <p>
                    <label>ملاحظات:</label><br>
                    {{ $book->manager_comment }}
                </p>
                @endif
            </div>
            @if($book->latestSignature->signature_data)
            <div class="signature-image">
                <img src="{{ $book->latestSignature->signature_data }}" alt="التوقيع الإلكتروني">
                <p>التوقيع الإلكتروني</p>
            </div>
            @endif
        </div>
        <div class="verification-code">
            رمز التحقق: {{ $book->latestSignature->signature_hash }}
        </div>
    </div>
    @endif

    <div class="footer">
        <p>تم إنشاء هذه الوثيقة إلكترونياً من نظام إدارة الرقابة والتفتيش</p>
        <p>تاريخ الطباعة: {{ now()->format('Y/m/d H:i:s') }}</p>
    </div>
</body>
</html>
