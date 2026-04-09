<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $order->order_code }}</title>
    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#1E3A8A',
                        secondary: '#3B82F6',
                        dark: '#1F2937',
                    }
                }
            }
        }
    </script>
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { background-color: #f9fafb; font-family: 'Inter', sans-serif; }
        .invoice-box { max-width: 800px; margin: 40px auto; padding: 40px; background: white; border: 1px solid #eee; border-radius: 8px; }
        @media print {
            body { background-color: white; margin: 0; padding: 0; }
            .invoice-box { border: none; box-shadow: none; margin: 0 auto; padding: 20px; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body class="text-gray-800">

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 mt-5 mb-5 no-print flex justify-between items-center">
    <button onclick="window.history.back()" class="text-gray-500 hover:text-gray-800 transition">
        <i class="fa-solid fa-arrow-left mr-2"></i> Kembali
    </button>
    <button onclick="window.print()" class="bg-primary hover:bg-blue-800 text-white px-5 py-2 rounded-lg font-medium transition flex items-center shadow">
        <i class="fa-solid fa-print mr-2"></i> Cetak Invoice
    </button>
</div>

<div class="invoice-box shadow-sm">
    <!-- Header Invoice -->
    <div class="flex justify-between items-start border-b border-gray-200 pb-8 mb-8">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <i class="fa-solid fa-book-open text-primary text-3xl"></i>
                <span class="text-2xl font-bold text-dark">JeBook</span>
            </div>
            <p class="text-gray-500 text-sm">Jl. Pendidikan No. 123, Kota Mahasiswa</p>
            <p class="text-gray-500 text-sm">Email: support@JeBook.com | WA: +62 812-3456-7890</p>
        </div>
        <div class="text-right">
            <h1 class="text-3xl font-bold text-dark mb-1">INVOICE</h1>
            <p class="text-gray-500 font-mono">#{{ $order->order_code }}</p>
            <span class="mt-2 inline-flex items-center px-2.5 py-1 rounded text-xs font-bold bg-gray-100 text-gray-700 uppercase tracking-wide">
                {{ $order->status_label }}
            </span>
        </div>
    </div>

    <!-- Info Pelanggan & Pesanan -->
    <div class="flex flex-col md:flex-row justify-between mb-8 gap-8">
        <div>
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-2">Ditagihkan Kepada:</h3>
            <p class="font-bold text-dark">{{ $order->user->name }}</p>
            <p class="text-gray-600 text-sm mt-1 mb-1">{{ $order->shipping_address }}</p>
            <p class="text-gray-600 text-sm"><i class="fa-solid fa-envelope text-gray-400 mr-1 w-4"></i> {{ $order->user->email }}</p>
            @if($order->user->phone)
            <p class="text-gray-600 text-sm"><i class="fa-solid fa-phone text-gray-400 mr-1 w-4"></i> {{ $order->user->phone }}</p>
            @endif
        </div>
        <div class="md:text-right">
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-2">Detail Pesanan:</h3>
            <div class="grid grid-cols-2 gap-x-4 gap-y-2 text-sm text-right md:text-right">
                <span class="text-gray-500">Tanggal Order:</span>
                <span class="text-dark font-medium">{{ $order->created_at->format('d M Y, H:i') }}</span>
                <span class="text-gray-500">Metode Bayar:</span>
                <span class="text-dark font-medium">{{ $order->payment_method === 'cod' ? 'COD (Bayar di Tempat)' : 'Transfer Bank' }}</span>
                @if($order->payment_method === 'transfer' && $order->transfer_bank_name)
                <span class="text-gray-500">Bank Pengirim:</span>
                <span class="text-dark font-medium">{{ $order->transfer_bank_name }}</span>
                @endif
            </div>
        </div>
    </div>

    <!-- Daftar Item -->
    <div class="mb-8 overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b-2 border-gray-200">
                    <th class="py-3 text-sm font-bold text-gray-700 uppercase tracking-wide">Deskripsi Buku</th>
                    <th class="py-3 text-sm font-bold text-gray-700 uppercase tracking-wide text-center">Qty</th>
                    <th class="py-3 text-sm font-bold text-gray-700 uppercase tracking-wide text-right">Harga Satuan</th>
                    <th class="py-3 text-sm font-bold text-gray-700 uppercase tracking-wide text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                @foreach($order->items as $item)
                <tr class="border-b border-gray-100">
                    <td class="py-4">
                        <p class="font-bold text-dark">{{ $item->book->title }}</p>
                        <p class="text-xs text-gray-500">{{ $item->book->author }}</p>
                    </td>
                    <td class="py-4 text-center text-gray-600">{{ $item->quantity }}</td>
                    <td class="py-4 text-right text-gray-600">Rp {{ number_format((float)$item->price, 0, ',', '.') }}</td>
                    <td class="py-4 text-right font-semibold text-dark">Rp {{ number_format((float)$item->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Ringkasan Pembayaran -->
    <div class="flex flex-col md:flex-row justify-between items-start">
        <div class="mb-6 md:mb-0 max-w-sm">
            @if($order->notes)
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-2">Catatan Pesanan:</h3>
            <p class="text-sm text-gray-600 bg-gray-50 p-3 rounded border border-gray-100 italic">{{ $order->notes }}</p>
            @endif
        </div>
        
        <div class="w-full md:w-1/2 lg:w-1/3">
            <div class="space-y-3 text-sm">
                <div class="flex justify-between text-gray-600">
                    <span>Subtotal</span>
                    <span>Rp {{ number_format((float)$order->total_price, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-gray-600">
                    <span>Pajak (12%)</span>
                    <span>Rp {{ number_format((float)$order->tax, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-gray-600 pb-3 border-b border-gray-200">
                    <span>Ongkos Kirim</span>
                    <span>Rp {{ number_format((float)$order->shipping_cost, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center pt-1">
                    <span class="font-bold text-dark">Total Pembayaran</span>
                    <span class="text-xl font-bold text-primary">Rp {{ number_format((float)$order->grand_total, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="mt-16 pt-8 border-t border-gray-200 text-center text-gray-400 text-xs">
        <p>Terima kasih telah berbelanja di JeBook!</p>
        <p>Invoice ini sah dan diproses oleh komputer, tidak memerlukan tanda tangan basah.</p>
    </div>
</div>

</body>
</html>
