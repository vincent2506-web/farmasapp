<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Pesanan - Admin Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style> 
        body { font-family: 'Inter', sans-serif; } 
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 antialiased flex h-screen overflow-hidden selection:bg-blue-100 selection:text-blue-900">

    <aside class="w-64 bg-white border-r border-slate-100 flex flex-col z-40 flex-shrink-0 hidden md:flex">
        <div class="h-20 flex items-center px-8 border-b border-slate-50">
            <a href="#" class="font-extrabold text-xl tracking-tight text-slate-900 flex items-center gap-3">
                <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center text-white text-sm shadow-lg shadow-blue-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
                FarmasiApp
            </a>
        </div>

        <nav class="flex-1 px-4 py-8 space-y-1.5 overflow-y-auto">
            <p class="px-4 text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Menu Utama</p>
            <a href="/admin/dashboard" class="flex items-center gap-3 px-4 py-3.5 rounded-2xl font-bold transition-all duration-200 text-slate-500 hover:bg-slate-50 hover:text-slate-900">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                Dashboard
            </a>
            <a href="/admin/obat" class="flex items-center gap-3 px-4 py-3.5 rounded-2xl font-bold transition-all duration-200 text-slate-500 hover:bg-slate-50 hover:text-slate-900">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                Katalog Obat
            </a>
            <a href="/admin/pesanan" class="flex items-center justify-between px-4 py-3.5 rounded-2xl font-bold transition-all duration-200 bg-blue-50 text-blue-600 shadow-sm border border-blue-100/50">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    Pesanan Masuk
                </div>
            </a>
        </nav>
        <div class="p-6 border-t border-slate-100 bg-white">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 font-bold border border-slate-200">
                    {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                </div>
                <div>
                    <p class="text-sm font-bold text-slate-900">{{ Auth::user()->name ?? 'Admin Farmasi' }}</p>
                    <p class="text-xs font-medium text-slate-400">Super Admin</p>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST" class="m-0">
                @csrf
                <button type="submit" class="w-full bg-slate-50 border border-slate-200 hover:border-red-200 hover:text-red-600 hover:bg-red-50 text-slate-600 px-4 py-3 rounded-xl font-bold text-sm transition-all flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Keluar Sistem
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1 flex flex-col h-screen overflow-hidden relative bg-slate-50/50">
        
        <header class="h-20 bg-white/80 backdrop-blur-xl border-b border-slate-200/50 px-8 flex items-center justify-between flex-shrink-0 z-30">
            <div>
                <h1 class="text-xl font-black text-slate-900">Manajemen Pesanan</h1>
                <p class="text-xs font-semibold text-slate-400">Proses transaksi dan pengiriman pelanggan</p>
            </div>
        </header>

        <div class="flex-1 overflow-x-hidden overflow-y-auto p-6 lg:p-8 relative">
            <div class="max-w-7xl mx-auto">
                
                @if(session('success'))
                    <div class="mb-6 bg-emerald-50 border border-emerald-100 text-emerald-600 px-6 py-4 rounded-2xl text-sm font-bold shadow-sm">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="bg-white rounded-[2.5rem] shadow-[0_4px_20px_-5px_rgba(0,0,0,0.05)] border border-slate-100 pb-4">
                    <div class="w-full">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50/50 border-b border-slate-100 text-[11px] uppercase tracking-widest text-slate-400 font-black">
                                    <th class="px-8 py-5 rounded-tl-[2.5rem]">No. Pesanan</th>
                                    <th class="px-8 py-5">Pelanggan</th>
                                    <th class="px-8 py-5">Kurir</th>
                                    <th class="px-8 py-5">Total Bayar</th>
                                    <th class="px-8 py-5 text-right rounded-tr-[2.5rem]">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                <tr class="group hover:bg-slate-50/50 transition-colors">
                                    <td class="px-8 py-5">
                                        <span class="text-xs font-black text-slate-400 mb-1 block">ORDER ID</span>
                                        <span class="text-sm font-bold text-slate-900">#{{ $order->biteship_order_id ?? $order->id }}</span>
                                    </td>
                                    <td class="px-8 py-5">
                                        <div class="flex flex-col">
                                            <span class="text-sm font-bold text-slate-900">{{ $order->receiver_name }}</span>
                                            <span class="text-[11px] font-medium text-slate-400 mt-0.5">{{ $order->receiver_phone }}</span>
                                        </div>
                                    </td>
                                    <td class="px-8 py-5">
                                        <div class="flex flex-col">
                                            <span class="text-xs font-bold text-blue-600 uppercase">{{ $order->courier_company }} - {{ $order->courier_type }}</span>
                                            <span class="text-[11px] font-medium text-slate-400 mt-0.5">Resi: {{ $order->waybill_id ?? 'Menunggu Pick Up' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-8 py-5 text-end">
                                        <span class="text-sm font-black text-slate-900">Rp {{ number_format($order->price, 0, ',', '.') }}</span>
                                    </td>
                                    <td class="px-8 py-5">
                                        @php
                                            $statusColor = [
                                                'placed' => 'bg-blue-100 text-blue-700',
                                                'confirmed' => 'bg-emerald-100 text-emerald-700',
                                                'picking_up' => 'bg-orange-100 text-orange-700',
                                                'delivered' => 'bg-green-100 text-green-700'
                                            ][$order->status] ?? 'bg-slate-100 text-slate-600';
                                        @endphp
                                        <span class="px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-wider {{ $statusColor }}">
                                            {{ $order->status }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-8 py-20 text-center">
                                        <div class="flex flex-col items-center">
                                            <p class="text-slate-400 font-bold">Belum ada pesanan masuk dari pengiriman.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </main>

</body>
</html>