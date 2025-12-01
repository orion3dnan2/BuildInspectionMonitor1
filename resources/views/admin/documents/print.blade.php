<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $document->document_number }} - {{ $document->title }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Tajawal', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            padding: 40px;
            background: white;
            color: #1e293b;
            line-height: 2;
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e2e8f0;
        }
        .header h1 {
            font-size: 24px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 10px;
        }
        .header p {
            color: #64748b;
            font-size: 14px;
        }
        .meta {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            padding: 20px;
            background: #f8fafc;
            border-radius: 8px;
        }
        .meta-item {
            text-align: center;
        }
        .meta-item label {
            display: block;
            font-size: 12px;
            color: #64748b;
            margin-bottom: 4px;
        }
        .meta-item value {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #1e293b;
        }
        .content {
            margin-bottom: 40px;
            padding: 20px;
            white-space: pre-wrap;
            font-size: 16px;
            line-height: 2.2;
        }
        .signature-section {
            margin-top: 60px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
        }
        .signature-section h3 {
            font-size: 14px;
            color: #64748b;
            margin-bottom: 10px;
        }
        .signature-image {
            max-width: 200px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 10px;
            background: white;
        }
        .signature-info {
            margin-top: 10px;
            font-size: 14px;
            color: #64748b;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .status-approved {
            background: #d1fae5;
            color: #065f46;
        }
        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }
        .status-rejected {
            background: #fee2e2;
            color: #991b1b;
        }
        @media print {
            body {
                padding: 20px;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    <div class="no-print" style="position: fixed; top: 20px; left: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #0ea5e9; color: white; border: none; border-radius: 8px; cursor: pointer; font-family: inherit;">
            طباعة
        </button>
        <button onclick="window.close()" style="padding: 10px 20px; background: #64748b; color: white; border: none; border-radius: 8px; cursor: pointer; font-family: inherit; margin-right: 10px;">
            إغلاق
        </button>
    </div>

    <div class="header">
        <h1>{{ $document->title }}</h1>
        <p>{{ $document->type_label }}</p>
    </div>

    <div class="meta">
        <div class="meta-item">
            <label>رقم المستند</label>
            <value>{{ $document->document_number }}</value>
        </div>
        <div class="meta-item">
            <label>التاريخ</label>
            <value>{{ $document->created_at->format('Y/m/d') }}</value>
        </div>
        <div class="meta-item">
            <label>القسم</label>
            <value>{{ $document->department?->name ?? 'غير محدد' }}</value>
        </div>
        <div class="meta-item">
            <label>الحالة</label>
            <value>
                <span class="status-badge {{ $document->status === 'approved' ? 'status-approved' : ($document->status === 'rejected' ? 'status-rejected' : 'status-pending') }}">
                    {{ $document->status_label }}
                </span>
            </value>
        </div>
    </div>

    <div class="content">{{ $document->content }}</div>

    @if($document->signature_data && $document->status === 'approved')
    <div class="signature-section">
        <h3>توقيع المدير</h3>
        <img src="{{ $document->signature_data }}" alt="التوقيع" class="signature-image">
        <div class="signature-info">
            <p>{{ $document->approver?->name }}</p>
            <p>{{ $document->approved_at?->format('Y/m/d H:i') }}</p>
        </div>
    </div>
    @endif
</body>
</html>
