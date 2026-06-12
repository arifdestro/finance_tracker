<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Keuangan</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; padding: 0; font-size: 20px; }
        .header p { margin: 5px 0 0 0; color: #666; }
        
        .summary-container { width: 100%; margin-bottom: 20px; }
        .summary-box { display: inline-block; width: 48%; border: 1px solid #ddd; padding: 15px; text-align: center; box-sizing: border-box; }
        .summary-box h3 { margin: 0 0 10px 0; font-size: 14px; color: #555; font-weight: normal; }
        .summary-box h2 { margin: 0; font-size: 18px; }
        .text-green { color: #10b981; }
        .text-red { color: #f43f5e; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f9fafb; font-weight: bold; color: #555; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        
        .section-title { font-size: 16px; margin-bottom: 10px; border-bottom: 2px solid #eee; padding-bottom: 5px;}
    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan Keuangan</h2>
        <p>Periode: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</p>
    </div>

    <div class="summary-container">
        <div class="summary-box" style="margin-right: 2%;">
            <h3>Total Pemasukan</h3>
            <h2 class="text-green">Rp {{ number_format($totalIncome, 0, ',', '.') }}</h2>
        </div>
        <div class="summary-box">
            <h3>Total Pengeluaran</h3>
            <h2 class="text-red">Rp {{ number_format($totalExpense, 0, ',', '.') }}</h2>
        </div>
    </div>

    <div class="section-title">Ringkasan Pengeluaran Kategori</div>
    <table>
        <thead>
            <tr>
                <th>Kategori</th>
                <th class="text-right">Total (Rp)</th>
                <th class="text-right">% dari Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($expenseByCategory as $expense)
            <tr>
                <td>{{ $expense->category?->name ?? 'Lain-lain' }}</td>
                <td class="text-right">{{ number_format($expense->total, 0, ',', '.') }}</td>
                <td class="text-right">{{ $totalExpense > 0 ? number_format(($expense->total / $totalExpense) * 100, 1) : 0 }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">Rincian Transaksi</div>
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Kategori / Tipe</th>
                <th>Dompet</th>
                <th>Deskripsi</th>
                <th class="text-right">Jumlah (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $trx)
            <tr>
                <td>{{ $trx->transaction_date->format('d/m/Y') }}</td>
                <td>{{ $trx->type == 'transfer' ? 'Transfer' : ($trx->category?->name ?? 'Tanpa Kategori') }}</td>
                <td>{{ $trx->type == 'transfer' ? $trx->wallet->name . ' -> ' . $trx->toWallet?->name : $trx->wallet->name }}</td>
                <td>{{ $trx->description }}</td>
                <td class="text-right {{ $trx->type == 'income' ? 'text-green' : ($trx->type == 'expense' ? 'text-red' : '') }}">
                    {{ $trx->type == 'expense' ? '-' : '' }}{{ number_format($trx->amount, 0, ',', '.') }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">Tidak ada transaksi pada periode ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
