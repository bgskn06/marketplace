<x-app-layout>
    <main class="max-w-4xl mx-auto p-6">
        <div class="bg-white p-6 rounded shadow">
            <h2 class="text-lg font-semibold">Informasi Pembayaran</h2>
            <div class="mt-4">
                <p>Order: <strong>{{ $order->order_number }}</strong></p>
                <p>Jumlah: <strong>Rp{{ number_format($order->total,0,',','.') }}</strong></p>
                <p>Metode: <strong>{{ strtoupper(str_replace("_", " ", $order->payment_method)) }}</strong></p>
            </div>

            @if($order->payment_method === 'bank_transfer')
            <div class="mt-4 p-4 border rounded bg-gray-50">
                <p class="text-sm text-gray-600">Silakan transfer ke rekening kami dengan kode pembayaran berikut:</p>
                <div class="mt-3 bg-white p-3 rounded flex items-center justify-between">
                    <div>
                        <div class="text-xs text-gray-500">Kode Pembayaran</div>
                        <div class="text-lg font-mono font-semibold">{{ $order->payment_code }}</div>
                    </div>
                    <div>
                        <button id="copy-code" class="btn btn-sm btn-outline">Salin</button>
                    </div>
                </div>

                <div class="mt-3">
                    <p class="text-sm text-gray-600">Sisa waktu pembayaran:</p>
                    <div id="countdown" class="text-lg font-semibold text-indigo-700 mt-1"></div>
                </div>

                <div class="mt-4 text-sm text-gray-700">
                    <p>Setelah 24 jam dari pembuatan, pesanan akan dibatalkan jika pembayaran tidak diterima.</p>
                </div>
            </div>
            @endif

            <div class="mt-6">
                <a href="{{ route('buyer.orders') }}" class="btn btn-secondary">Kembali ke Pesanan</a>
            </div>
        </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const copyBtn = document.getElementById('copy-code');
                if (copyBtn) {
                    copyBtn.addEventListener('click', function() {
                        navigator.clipboard.writeText('{{ $order->payment_code }}').then(() => {
                            alert('Kode pembayaran disalin');
                        });
                    });
                }

                const expiresAt = '{{ $order->payment_expires_at }}';
                if (expiresAt) {
                    function updateCountdown() {
                        const then = new Date(expiresAt);
                        const now = new Date();
                        const diff = then - now;
                        if (diff <= 0) {
                            document.getElementById('countdown').textContent = 'Waktu pembayaran telah habis';
                            return;
                        }
                        const hours = Math.floor(diff / (1000 * 60 * 60));
                        const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                        const seconds = Math.floor((diff % (1000 * 60)) / 1000);
                        document.getElementById('countdown').textContent = `${hours}h ${minutes}m ${seconds}s`;
                    }
                    updateCountdown();
                    setInterval(updateCountdown, 1000);
                }
            });

        </script>
</x-app-layout>
