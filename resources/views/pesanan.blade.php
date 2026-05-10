<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Saya - Apotek App</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style> 
        body { font-family: 'Inter', sans-serif; }
        .glass-effect { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(12px); }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 antialiased">
    
    @include('navbar')

    <main class="md:pl-64 min-h-screen flex flex-col transition-all duration-300">
        
        <header class="glass-effect sticky top-0 z-40 border-b border-slate-200/60 px-6 sm:px-10 py-5 sm:py-6">
            <div class="max-w-4xl mx-auto flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-extrabold tracking-tight text-slate-900">Pesanan Saya</h1>
                    <p class="text-xs text-slate-500 font-medium mt-1">Pantau status dan riwayat pengiriman Anda</p>
                </div>
            </div>
        </header>

        <div class="p-6 sm:p-10 max-w-4xl mx-auto w-full">
            <div class="space-y-6">
                
                @forelse($orders as $order)
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden hover:border-blue-300 transition-colors">
                    <div class="border-b border-slate-100 bg-slate-50/50 px-6 py-4 flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <span class="text-xs font-bold text-slate-500">{{ $order->created_at->format('d M Y') }}</span>
                            <span class="px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-wider 
                                {{ $order->status == 'delivered' ? 'bg-emerald-100 text-emerald-700' : 'bg-orange-100 text-orange-700' }}">
                                {{ $order->status }}
                            </span>
                        </div>
                        <span class="text-sm font-black text-slate-900">Order #{{ $order->biteship_order_id ?? $order->id }}</span>
                    </div>
                    
                    <div class="p-6 flex flex-col sm:flex-row justify-between gap-6">
                        <div class="space-y-4 flex-1">
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Penerima</p>
                                <p class="text-sm font-bold text-slate-900">{{ $order->receiver_name }}</p>
                                <p class="text-xs text-slate-600 mt-0.5">{{ $order->destination_address }}</p>
                            </div>
                            
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Pengiriman</p>
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-bold text-blue-600 uppercase">{{ $order->courier_company }} - {{ $order->courier_type }}</span>
                                </div>
                                <p class="text-xs font-medium text-slate-600 mt-1">
                                    Resi: <span class="font-bold text-slate-900">{{ $order->waybill_id ?? 'Menunggu Pick Up' }}</span>
                                </p>
                            </div>
                        </div>

                        <div class="flex flex-col justify-end gap-3 sm:min-w-[160px]">
                            @if($order->waybill_id)
                                <button onclick="openTrackModal('{{ $order->waybill_id }}', '{{ $order->courier_company }}')" 
                                        class="w-full bg-blue-50 hover:bg-blue-600 hover:text-white text-blue-600 border border-blue-200 px-4 py-2.5 rounded-xl font-bold text-xs transition-all text-center">
                                    Lacak Paket
                                </button>
                            @else
                                <button disabled class="w-full bg-slate-100 text-slate-400 border border-slate-200 px-4 py-2.5 rounded-xl font-bold text-xs cursor-not-allowed">
                                    Belum Ada Resi
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-20 bg-white rounded-2xl border border-slate-200 border-dashed">
                    <p class="text-slate-400 font-bold mb-4">Anda belum melakukan pemesanan apa pun.</p>
                    <a href="/katalog" class="inline-flex bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-bold text-sm shadow-lg shadow-blue-200 transition-all">
                        Belanja Sekarang
                    </a>
                </div>
                @endforelse

            </div>
        </div>
    </main>

    <div id="trackModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="closeTrackModal()"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-lg bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
            
            <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                <h3 class="font-bold text-slate-900">Detail Pelacakan</h3>
                <button onclick="closeTrackModal()" class="text-slate-400 hover:text-red-500 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="p-6 overflow-y-auto">
                <div id="modalLoading" class="flex flex-col items-center justify-center py-10">
                    <div class="animate-spin h-8 w-8 border-4 border-blue-500 border-t-transparent rounded-full mb-4"></div>
                    <p class="text-sm font-medium text-slate-500">Menghubungi kurir...</p>
                </div>

                <div id="modalContent" class="hidden">
                    <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 mb-6">
                        <p class="text-xs font-bold text-blue-800 uppercase mb-1" id="modalStatus"></p>
                        <p class="text-sm font-medium text-blue-600" id="modalResi"></p>
                    </div>

                    <h4 class="text-sm font-bold text-slate-900 mb-4">Riwayat Perjalanan</h4>
                    <div id="modalTimeline" class="relative border-l-2 border-slate-200 ml-2 space-y-6 pb-4">
                        </div>
                </div>

                <div id="modalError" class="hidden text-center py-6">
                    <p class="text-sm font-bold text-red-500">Gagal melacak paket. Resi mungkin belum terdaftar di sistem kurir.</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function openTrackModal(waybill, courier) {
            $('#trackModal').removeClass('hidden');
            $('#modalLoading').removeClass('hidden');
            $('#modalContent, #modalError').addClass('hidden');

            $.ajax({
                url: "/lacak-paket",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    waybill_id: waybill,
                    courier: courier
                },
                success: function(response) {
                    $('#modalLoading').addClass('hidden');
                    
                    if(response.success) {
                        $('#modalContent').removeClass('hidden');
                        $('#modalStatus').text('Status: ' + response.status);
                        $('#modalResi').text('Resi: ' + response.waybill_id + ' (' + response.courier.company.toUpperCase() + ')');

                        let historyHtml = '';
                        if(response.history && response.history.length > 0) {
                            let histories = response.history.reverse(); 
                            
                            histories.forEach(function(item, index) {
                                let dotColor = index === 0 ? 'bg-blue-500 ring-4 ring-blue-100' : 'bg-slate-300';
                                let textColor = index === 0 ? 'text-slate-900 font-bold' : 'text-slate-500 font-medium';
                                let dateStr = new Date(item.updated_at).toLocaleString('id-ID');

                                historyHtml += `
                                    <div class="relative pl-6">
                                        <div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full ${dotColor}"></div>
                                        <p class="text-sm ${textColor}">${item.note}</p>
                                        <p class="text-xs text-slate-400 mt-1">${dateStr}</p>
                                    </div>
                                `;
                            });
                        } else {
                            historyHtml = '<p class="text-sm text-slate-500 pl-6">Belum ada riwayat.</p>';
                        }
                        $('#modalTimeline').html(historyHtml);
                    } else {
                        $('#modalError').removeClass('hidden');
                    }
                },
                error: function() {
                    $('#modalLoading').addClass('hidden');
                    $('#modalError').removeClass('hidden');
                }
            });
        }

        function closeTrackModal() {
            $('#trackModal').addClass('hidden');
            $('#modalTimeline').html('');
        }
    </script>
</body>
</html>