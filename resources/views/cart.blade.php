@extends('layouts.app')

@section('title', 'Keranjang Belanja - JeBook')

@section('content')
<div class="bg-gray-50 py-10 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <h1 class="text-2xl font-bold text-dark mb-6"><i class="fa-solid fa-cart-shopping mr-2 text-gray-400"></i>Keranjang Belanja</h1>

        @if(empty($cart))
        <!-- Empty Cart -->
        <div class="text-center py-16 bg-white rounded-lg border border-gray-200">
            <i class="fa-solid fa-cart-shopping text-5xl text-gray-200 mb-4"></i>
            <h3 class="text-lg font-semibold text-gray-500 mb-2">Keranjang Anda kosong</h3>
            <p class="text-gray-400 text-sm mb-5">Yuk, mulai belanja buku favorit Anda!</p>
            <a href="{{ route('katalog') }}" class="bg-primary text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-blue-800 transition">
                <i class="fa-solid fa-book mr-1"></i> Jelajahi Buku
            </a>
        </div>
        @else
        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Cart Items -->
            <div class="lg:w-2/3">
                <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">

                    <!-- Header: Pilih Semua -->
                    <div class="px-5 py-3 border-b border-gray-100 bg-gray-50 flex items-center gap-3">
                        <input type="checkbox" id="select-all"
                               class="h-4 w-4 text-primary border-gray-300 rounded focus:ring-primary cursor-pointer">
                        <label for="select-all" class="text-sm font-medium text-gray-600 cursor-pointer select-none">
                            Pilih Semua
                        </label>
                        <span id="selected-count" class="ml-auto text-xs text-gray-400"></span>
                    </div>

                    @foreach($cart as $id => $item)
                    <div class="p-5 border-b border-gray-100 last:border-b-0 flex flex-col sm:flex-row items-center gap-4 cart-row"
                         data-id="{{ $id }}"
                         data-price="{{ $item['price'] }}"
                         data-qty="{{ $item['quantity'] }}">

                        <!-- Checkbox -->
                        <input type="checkbox"
                               class="item-checkbox h-4 w-4 text-primary border-gray-300 rounded focus:ring-primary cursor-pointer flex-shrink-0"
                               data-id="{{ $id }}"
                               data-price="{{ $item['price'] }}"
                               data-qty="{{ $item['quantity'] }}"
                               checked>

                        <img src="{{ $item['image_url'] ?: 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?q=80&w=200' }}"
                             alt="{{ $item['title'] }}" class="w-16 h-22 object-cover rounded-lg flex-shrink-0">

                        <div class="flex-grow text-center sm:text-left">
                            <h3 class="text-sm font-semibold text-dark">{{ $item['title'] }}</h3>
                            <p class="text-xs text-gray-400 mb-1">{{ $item['author'] }}</p>
                            <span class="text-primary text-sm font-semibold">Rp {{ number_format((float)$item['price'], 0, ',', '.') }}</span>
                        </div>

                        <div class="flex items-center gap-3">
                            <!-- Update Quantity -->
                            <form action="{{ route('cart.update') }}" method="POST" class="flex items-center border border-gray-200 rounded-lg overflow-hidden">
                                @csrf
                                <input type="hidden" name="book_id" value="{{ $id }}">
                                <button type="submit" name="quantity" value="{{ max(1, $item['quantity'] - 1) }}"
                                        class="px-2.5 py-1.5 text-gray-500 hover:bg-gray-50 transition text-sm font-bold">−</button>
                                <span class="w-8 text-center text-sm font-medium border-x border-gray-200 py-1.5">{{ $item['quantity'] }}</span>
                                <button type="submit" name="quantity" value="{{ $item['quantity'] + 1 }}"
                                        class="px-2.5 py-1.5 text-gray-500 hover:bg-gray-50 transition text-sm font-bold">+</button>
                            </form>

                            <!-- Subtotal per item -->
                            <span class="text-sm font-semibold text-gray-700 w-24 text-right">
                                Rp {{ number_format((float)$item['price'] * $item['quantity'], 0, ',', '.') }}
                            </span>

                            <!-- Remove -->
                            <form action="{{ route('cart.remove') }}" method="POST">
                                @csrf
                                <input type="hidden" name="book_id" value="{{ $id }}">
                                <button type="submit" class="text-gray-400 hover:text-red-500 p-1.5 rounded transition" title="Hapus">
                                    <i class="fa-solid fa-trash-can text-sm"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>

                <a href="{{ route('katalog') }}" class="inline-flex items-center text-secondary hover:text-blue-700 text-sm font-medium mt-4 transition">
                    <i class="fa-solid fa-arrow-left mr-1.5"></i> Lanjut Belanja
                </a>
            </div>

            <!-- Summary & Checkout -->
            @php
                $shipping = 0;
                $fullSubtotal = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);
                $tax = $fullSubtotal * 0.12;
                $fullTotal = $fullSubtotal + $tax + $shipping;
                $totalQty = array_sum(array_column($cart, 'quantity'));
            @endphp
            <div class="lg:w-1/3">
                <div class="bg-white rounded-lg border border-gray-200 p-5 sticky top-24">
                    <h3 class="font-bold text-dark mb-4">Ringkasan Belanja</h3>

                    <div class="space-y-2 text-sm text-gray-600 mb-5 border-b border-gray-100 pb-5">
                        <div class="flex justify-between">
                            <span>Dipilih: <span id="summary-qty" class="font-medium text-dark">{{ $totalQty }}</span> buku</span>
                            <span id="summary-subtotal" class="font-medium text-dark">Rp {{ number_format((float)$fullSubtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Biaya Pengiriman</span>
                            <span class="font-medium text-dark">Rp {{ number_format((float)$shipping, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between mt-1">
                            <span>Pajak (12%)</span>
                            <span id="summary-tax" class="font-medium text-dark">Rp {{ number_format((float)$tax, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="flex justify-between items-end mb-5">
                        <span class="text-sm font-semibold text-dark">Total Tagihan</span>
                        <span id="summary-total" class="text-xl font-bold text-primary">Rp {{ number_format((float)$fullTotal, 0, ',', '.') }}</span>
                    </div>

                    <button id="btn-checkout" onclick="goCheckout()"
                            class="w-full bg-primary hover:bg-blue-800 text-white font-medium py-3 px-4 rounded-lg transition text-sm">
                        <i class="fa-solid fa-bag-shopping mr-1.5"></i> Lanjut ke Checkout
                    </button>

                    <p id="no-selection-warning" class="hidden text-xs text-red-500 text-center mt-2">
                        Pilih minimal 1 buku untuk checkout.
                    </p>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<script>
    const shipping = {{ $shipping ?? 0 }};
    const checkboxes = document.querySelectorAll('.item-checkbox');
    const selectAll  = document.getElementById('select-all');

    function formatRupiah(n) {
        return 'Rp ' + n.toLocaleString('id-ID');
    }

    function updateSummary() {
        let subtotal = 0;
        let qty = 0;
        let checkedCount = 0;

        checkboxes.forEach(cb => {
            if (cb.checked) {
                const price = parseFloat(cb.dataset.price);
                const q     = parseInt(cb.dataset.qty);
                subtotal += price * q;
                qty      += q;
                checkedCount++;
            }
        });

        const tax = subtotal * 0.12;

        document.getElementById('summary-subtotal').textContent = formatRupiah(subtotal);
        document.getElementById('summary-tax').textContent = formatRupiah(tax);
        document.getElementById('summary-qty').textContent = qty;
        document.getElementById('summary-total').textContent = formatRupiah(subtotal + tax + shipping);
        document.getElementById('selected-count').textContent =
            checkedCount > 0 ? checkedCount + ' item dipilih' : '';

        document.querySelectorAll('.cart-row').forEach(row => {
            const cb = row.querySelector('.item-checkbox');
            row.style.opacity = cb.checked ? '1' : '0.45';
        });

        selectAll.checked = checkedCount === checkboxes.length;
        selectAll.indeterminate = checkedCount > 0 && checkedCount < checkboxes.length;

        const warning = document.getElementById('no-selection-warning');
        const btn = document.getElementById('btn-checkout');
        if (checkedCount === 0) {
            btn.classList.add('opacity-50', 'cursor-not-allowed');
            warning.classList.remove('hidden');
        } else {
            btn.classList.remove('opacity-50', 'cursor-not-allowed');
            warning.classList.add('hidden');
        }
    }

    selectAll.addEventListener('change', () => {
        checkboxes.forEach(cb => cb.checked = selectAll.checked);
        updateSummary();
    });

    checkboxes.forEach(cb => cb.addEventListener('change', updateSummary));

    function goCheckout() {
        const selected = [];
        checkboxes.forEach(cb => { if (cb.checked) selected.push(cb.dataset.id); });
        if (selected.length === 0) {
            document.getElementById('no-selection-warning').classList.remove('hidden');
            return;
        }
        window.location.href = '{{ route("checkout") }}?items=' + selected.join(',');
    }

    updateSummary();
</script>
@endsection
