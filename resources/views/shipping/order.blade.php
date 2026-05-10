<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Pengiriman - Apotek App</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style> 
        body { font-family: 'Inter', sans-serif; }
        .glass-effect { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(12px); }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 antialiased">
    
    @include('navbar')

    <main class="md:pl-64 min-h-screen flex flex-col">
        <header class="glass-effect sticky top-0 z-40 border-b border-slate-200/60 px-6 sm:px-10 py-5 sm:py-6">
            <div class="max-w-4xl mx-auto">
                <h1 class="text-xl font-extrabold text-slate-900">Buat Pengiriman Baru</h1>
                <p class="text-xs text-slate-500 font-medium mt-1">Isi formulir di bawah untuk memproses pesanan</p>
            </div>
        </header>

        <div class="p-6 sm:p-10 max-w-3xl mx-auto w-full">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 sm:p-8">
                <form id="orderForm" class="space-y-8">
                    @csrf
                    
                    <div class="space-y-4">
                        <h3 class="text-xs font-black text-blue-600 uppercase tracking-[0.2em]">Data Penerima</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-1">
                                <label class="text-[11px] font-bold text-slate-400 uppercase ml-1">Nama Lengkap</label>
                                <input type="text" name="dest_name" placeholder="Contoh: Budi Santoso" class="w-full px-4 py-3 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-blue-500 transition-all" required>
                            </div>
                            <div class="space-y-1">
                                <label class="text-[11px] font-bold text-slate-400 uppercase ml-1">Nomor WhatsApp</label>
                                <input type="text" name="dest_phone" placeholder="0812xxxx" class="w-full px-4 py-3 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-blue-500 transition-all" required>
                            </div>
                        </div>
                        
                        <div class="space-y-1">
                            <label class="text-[11px] font-bold text-slate-400 uppercase ml-1">Alamat Lengkap</label>
                            <textarea name="dest_address" placeholder="Nama jalan, nomor rumah, RT/RW..." rows="3" class="w-full px-4 py-3 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-blue-500 transition-all" required></textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-1">
                                <label class="text-[11px] font-bold text-slate-400 uppercase ml-1">Kode Pos</label>
                                <input type="number" name="dest_postal" placeholder="12345" class="w-full px-4 py-3 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-blue-500 transition-all" required>
                            </div>
                            <div class="space-y-1">
                                <label class="text-[11px] font-bold text-slate-400 uppercase ml-1">Catatan (Opsional)</label>
                                <input type="text" name="dest_note" placeholder="Pagar hitam / Depan warung" class="w-full px-4 py-3 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                            </div>
                        </div>
                    </div>

                    <div class="h-px bg-slate-100 w-full"></div>

                    <div class="space-y-4">
                        <h3 class="text-xs font-black text-blue-600 uppercase tracking-[0.2em]">Metode Pengiriman</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-1">
                                <label class="text-[11px] font-bold text-slate-400 uppercase ml-1">Pilih Kurir</label>
                                <select name="courier_company" class="w-full px-4 py-3 rounded-xl border border-slate-200 outline-none bg-white appearance-none cursor-pointer">
                                    <option value="jne">JNE</option>
                                    <option value="jnt">J&T Express</option>
                                    <option value="sicepat">SiCepat</option>
                                    <option value="tiki">TIKI</option>
                                </select>
                            </div>
                            <div class="space-y-1">
                                <label class="text-[11px] font-bold text-slate-400 uppercase ml-1">Pilih Layanan</label>
                                <select name="courier_type" class="w-full px-4 py-3 rounded-xl border border-slate-200 outline-none bg-white appearance-none cursor-pointer">
                                    <option value="reg">Reguler (Standard)</option>
                                    <option value="eco">Ekonomi (Hemat)</option>
                                    <option value="ons">Next Day (Cepat)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <button type="submit" id="btnSubmit" class="w-full bg-slate-900 hover:bg-blue-600 text-white px-6 py-4 rounded-xl font-bold text-sm shadow-xl shadow-slate-200 transition-all flex items-center justify-center gap-3 mt-4">
                        <span id="btnText">Konfirmasi Pesanan</span>
                        <div id="loading-state" class="hidden animate-spin h-5 w-5 border-2 border-white border-t-transparent rounded-full"></div>
                    </button>
                </form>
            </div>
        </div>
    </main>

    <div id="completionModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-md"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-sm bg-white rounded-[2rem] shadow-2xl p-10 text-center scale-95 opacity-0 transition-all duration-300" id="modalBox">
            <div class="w-20 h-20 bg-emerald-50 text-emerald-500 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h3 class="text-2xl font-black text-slate-900 mb-2">Sukses!</h3>
            <p class="text-slate-500 text-sm mb-10 leading-relaxed font-medium">Pesanan Anda berhasil dikirim ke sistem kurir dan tersimpan di riwayat.</p>
            
            <div class="space-y-3">
                <a href="/pesanan" class="block w-full bg-blue-600 hover:bg-blue-700 text-white px-6 py-4 rounded-2xl font-bold text-sm transition-all shadow-lg shadow-blue-100">
                    Buka Pesanan Saya
                </a>
                <button onclick="location.reload()" class="block w-full text-slate-400 hover:text-slate-600 px-6 py-2 rounded-2xl font-bold text-xs transition-all">
                    Buat Order Baru
                </button>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $('#orderForm').on('submit', function(e) {
            e.preventDefault();
            
            $('#btnText').text('Sedang Memproses...');
            $('#loading-state').removeClass('hidden');
            $('#btnSubmit').prop('disabled', true);

            $.ajax({
                url: "/buat-pengiriman",
                method: "POST",
                data: $(this).serialize(),
                success: function(response) {
                    $('#loading-state').addClass('hidden');
                    
                    if(response.success || response.id) {
                        $('#completionModal').removeClass('hidden');
                        setTimeout(() => {
                            $('#modalBox').removeClass('scale-95 opacity-0').addClass('scale-100 opacity-100');
                        }, 50);
                        $('body').addClass('overflow-hidden');
                    } else {
                        alert('Gagal: ' + (response.message || 'Terjadi kesalahan'));
                        $('#btnText').text('Konfirmasi & Buat Pesanan');
                        $('#btnSubmit').prop('disabled', false);
                    }
                },
                error: function() {
                    alert('Gagal menghubungi server.');
                    $('#btnText').text('Konfirmasi & Buat Pesanan');
                    $('#loading-state').addClass('hidden');
                    $('#btnSubmit').prop('disabled', false);
                }
            });
        });
    </script>
</body>
</html>