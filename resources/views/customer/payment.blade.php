@extends('layouts.customer')

@section('content')
@php
    $packageName = $payment->package_name ?? 'Your Wedding Package';
    $totalAmount = number_format($payment->amount, 0, ',', '.');
@endphp

<div class="min-h-screen bg-[#f9f8f3] font-sans text-zinc-900">
    <main id="main-content" class="max-w-4xl mx-auto px-6 py-16">
        
        <div id="payment-instructions">
            <div class="flex items-center gap-4 mb-12">
                <a href="#" class="p-2 hover:bg-white rounded-full transition-colors decoration-none text-xl">
                    ←
                </a>
                <h1 class="text-4xl font-serif text-[#2d4a22]">Payment Instructions</h1>
            </div>

            <div class="space-y-8">
                <section class="bg-[#2d4a22] rounded-[32px] p-8 text-white flex items-center justify-between shadow-xl shadow-[#2d4a22]/20">
                    <div class="flex items-center gap-4">
                        <span class="text-3xl opacity-70">🕒</span>
                        <div>
                            <p class="text-xs uppercase tracking-widest opacity-70 mb-1">Package: {{ $packageName }}</p>
                            <p class="text-3xl font-bold font-mono">23:59:59</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-xs uppercase tracking-widest opacity-70 mb-1">Total Amount</p>
                        <p class="text-3xl font-bold">IDR {{ $totalAmount }}</p>
                    </div>
                </section>

                <section class="bg-white rounded-[32px] p-10 border border-zinc-100 shadow-sm">
                    <h2 class="text-xl font-bold mb-8 flex items-center gap-2">
                        <span class="text-[#2d4a22]">🛡️</span>
                        Bank Transfer Details
                    </h2>

                    <div class="space-y-8">
                        <div class="flex items-center justify-between p-6 bg-zinc-50 rounded-2xl border border-zinc-100">
                            <div>
                                <p class="text-[10px] text-zinc-400 font-bold uppercase tracking-widest mb-2">BANK NAME</p>
                                <p class="text-xl font-bold">Bank Central Asia (BCA)</p>
                            </div>
                            <div class="w-16 h-10 bg-white rounded flex items-center justify-center font-bold text-blue-800 text-xs shadow-sm border border-zinc-100">BCA</div>
                        </div>

                        <div class="flex items-center justify-between p-6 bg-zinc-50 rounded-2xl border border-zinc-100">
                            <div>
                                <p class="text-[10px] text-zinc-400 font-bold uppercase tracking-widest mb-2">ACCOUNT NUMBER</p>
                                <p id="account-number" class="text-2xl font-mono font-bold">8141 2163 4197</p>
                            </div>
                            <button onclick="copyToClipboard()" class="p-3 bg-white text-[#2d4a22] rounded-xl shadow-sm hover:shadow-md transition-all border border-zinc-100 cursor-pointer outline-none">
                                📋
                            </button>
                        </div>
                    </div>
                </section>

                <section class="bg-white rounded-[32px] p-10 border border-zinc-100 shadow-sm text-center">
                    <h3 class="text-xl font-bold mb-4">Already transferred?</h3>
                    <p class="text-zinc-500 mb-8 max-w-md mx-auto leading-relaxed">
                        Please upload your transfer receipt below.
                    </p>

                    <form id="paymentForm" enctype="multipart/form-data" class="max-w-md mx-auto">
                        @csrf <input type="hidden" name="booking_id" value="{{ $payment->booking_id }}">
                        
                        <div class="mb-8">
                            <input type="file" name="payment_proof" id="fileInput" class="hidden" accept="image/*" required>
                            <label for="fileInput" class="cursor-pointer group">
                                <div class="border-2 border-dashed border-zinc-200 rounded-2xl p-8 transition-all group-hover:border-[#2d4a22] group-hover:bg-zinc-50">
                                    <span id="fileName" class="text-sm text-zinc-400 font-medium group-hover:text-[#2d4a22]">
                                        📸 Click to select transfer receipt
                                    </span>
                                </div>
                            </label>
                        </div>

                        <button type="submit" id="confirm-btn" class="w-full py-6 bg-[#2d4a22] text-white rounded-2xl font-bold text-xl shadow-xl hover:bg-[#1e3317] transition-all flex items-center justify-center gap-3 cursor-pointer outline-none">
                            <span id="btn-text">Submit Confirmation</span>
                            <div id="loader" class="hidden w-6 h-6 border-4 border-white/30 border-t-white rounded-full animate-spin"></div>
                        </button>
                    </form>
                </section>
            </div>
        </div>

        <div id="success-message" class="hidden flex flex-col items-center justify-center text-center py-20">
            <div class="bg-white rounded-[48px] p-16 max-w-2xl w-full shadow-2xl border border-zinc-100">
                <div class="w-24 h-24 bg-[#2d4a22]/10 rounded-full flex items-center justify-center mx-auto mb-10">
                    <span class="text-4xl text-[#2d4a22]">✅</span>
                </div>
                <h1 class="text-5xl font-serif text-[#2d4a22] mb-6">Payment Confirmed</h1>
                <p class="text-zinc-500 text-lg mb-12">
                    Your booking for <span class="text-zinc-900 font-bold">{{ $packageName }}</span> has been processed.
                </p>
                <a href="{{ route('customer.bookings.history') }}" class="w-full inline-block py-6 bg-[#2d4a22] text-white rounded-2xl font-bold text-xl hover:bg-[#1e3317] transition-all decoration-none">
                    Go to Booking History →
                </a>
            </div>
        </div>
    </main>
</div>

<script>
    // Menampilkan Interaksi Nama Dokumen Unggahan
    document.getElementById('fileInput').onchange = function() {
        if (this.files && this.files[0]) { 
            document.getElementById('fileName').innerHTML = `Selected: <strong class="text-[#2d4a22]">${this.files[0].name}</strong>`;
        } else {
            document.getElementById('fileName').innerHTML = `📸 Click to select transfer receipt`;
        }
    };

    // Fungsi Salin Rekening ke Clipboard
    function copyToClipboard() {
        const accNum = document.getElementById('account-number').innerText;
        navigator.clipboard.writeText(accNum);
        alert("Account Number Copied!");
    }

    // Penanganan Aksi Kirim Bukti Transfer via AJAX Fetch API
    document.getElementById('paymentForm').onsubmit = async function(e) {
        e.preventDefault();
        
        const btn = document.getElementById('confirm-btn');
        const btnText = document.getElementById('btn-text');
        const loader = document.getElementById('loader');

        btn.disabled = true;
        btnText.innerText = "Uploading Receipt...";
        loader.classList.remove('hidden');

        try {
            const formData = new FormData(this);
            // Mengarahkan ke rute POST Laravel Berorientasi Token
            const response = await fetch("{{ route('customer.payment.submit') }}", {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const hasil = await response.json();

            if (hasil.status === 'success') {
                setTimeout(() => {
                    document.getElementById('payment-instructions').classList.add('hidden');
                    document.getElementById('success-message').classList.remove('hidden');
                    window.scrollTo(0, 0);
                }, 1500);
            } else {
                alert("Error: " + hasil.message);
                resetButton();
            }
        } catch (error) {
            console.error("Error detail:", error);
            alert("Gagal memproses unggah bukti transfer. Pastikan file berupa gambar berukuran maksimal 2MB.");
            resetButton();
        }
    };

    function resetButton() {
        const btn = document.getElementById('confirm-btn');
        btn.disabled = false;
        document.getElementById('btn-text').innerText = "Submit Confirmation";
        document.getElementById('loader').classList.add('hidden');
    }
</script>
@endsection
