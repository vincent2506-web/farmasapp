<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\BiteshipService;
use Illuminate\Support\Facades\Log; // Tambahkan ini untuk mencatat error

class ShippingController extends Controller
{
    protected $biteship;

    // PASTIKAN CONSTRUCTOR INI ADA
    public function __construct(BiteshipService $biteship)
    {
        $this->biteship = $biteship;
    }

    public function index()
    {
        return view('shipping.index');
    }

    public function search(Request $request)
    {
        try {
            $query = $request->get('q');
            
            // Panggil fungsi searchArea dari service
            $result = $this->biteship->searchArea($query);

            $formatted = [];
            
            // Pastikan data 'areas' benar-benar ada dari Biteship
            if (isset($result['areas']) && is_array($result['areas'])) {
                foreach ($result['areas'] as $area) {
                    // Gunakan null coalescing (??) agar tidak error jika ada data kota yang kosong
                    $name = $area['name'] ?? '';
                    $city = $area['city_name'] ?? '';
                    $prov = $area['administrative_division_name'] ?? '';
                    $zip = $area['postal_code'] ?? '';

                    $formatted[] = [
                        'id' => $area['id'],
                        'text' => strtoupper("$name, $city, $prov, $zip")
                    ];
                }
            }

            return response()->json($formatted);

        } catch (\Exception $e) {
            // Jika ada error, catat di log laravel dan kembalikan response kosong
            Log::error("Biteship Search Error: " . $e->getMessage());
            return response()->json([]);
        }
    }

    public function check(Request $request)
    {
        $request->validate([
            'origin' => 'required',
            'destination' => 'required',
            'weight' => 'required|numeric|min:1'
        ]);

        $items = [[
            'name' => 'Barang Farmasi',
            'value' => 50000,
            'weight' => (int) $request->weight,
            'quantity' => 1
        ]];

        $rates = $this->biteship->getRates($request->origin, $request->destination, $items);

        // Sekarang kita kembalikan JSON agar bisa dibaca oleh JavaScript (AJAX)
        return response()->json($rates);
    }


    public function orderIndex()
    {
        return view('shipping.order');
    }    

    public function storeOrder(Request $request)
    {
        // 1. Tangkap data dari form dengan nilai default (pengamanan agar tidak error 'Missing')
        $courierCompany = strtolower($request->input('courier_company', 'jne'));
        $courierType    = strtolower($request->input('courier_type', 'reg'));

        // 2. Susun Payload API persis seperti yang diminta Biteship
        $payload = [
            "shipper_contact_name" => "Apotek Sehat Pro",
            "shipper_contact_phone" => "081234567890",
            "shipper_contact_email" => "admin@apotek.com",
            "shipper_organization" => "Apotek Sehat",
            
            "origin_contact_name" => "Admin Apotek",
            "origin_contact_phone" => "081234567890",
            "origin_address" => "Jl. Farmasi No. 123, Jakarta Pusat",
            "origin_note" => "Dekat Alfamart",
            "origin_postal_code" => 10110,
            
            "destination_contact_name" => $request->input('dest_name', 'Penerima Default'),
            "destination_contact_phone" => $request->input('dest_phone', '08123456789'),
            "destination_contact_email" => $request->input('dest_email', 'customer@email.com'),
            "destination_address" => $request->input('dest_address', 'Alamat Tujuan'),
            "destination_postal_code" => (int) $request->input('dest_postal', 12345),
            "destination_note" => $request->input('dest_note', '-'),
            
            // INI YANG MEMBUAT ERROR SEBELUMNYA, SEKARANG SUDAH AMAN
            "courier_company" => $courierCompany,
            "courier_type"    => $courierType,
            "delivery_type"   => "now",
            
            "items" => [
                [
                    "name" => "Paket Obat Farmasi",
                    "description" => "Obat-obatan dan Vitamin",
                    "value" => 50000,
                    "quantity" => 1,
                    "weight" => 1000
                ]
            ]
        ];

        // 3. Tembak API Biteship
        $result = $this->biteship->createOrder($payload);

        // 4. KODE BARU: Simpan ke Database HANYA JIKA sukses
        if (isset($result['success']) && $result['success'] === true) {
            // Di dalam method storeOrder, saat Shipment::create:
                \App\Models\Shipment::create([
                    'user_id'             => auth()->id(), // Simpan ID User yang login
                    'biteship_order_id'   => $result['id'] ?? null,
                    'waybill_id'          => $result['courier']['waybill_id'] ?? null,
                    'courier_company'     => $courierCompany,
                    'courier_type'        => $courierType,
                    'receiver_name'       => $request->input('dest_name'),
                    'receiver_phone'      => $request->input('dest_phone'),
                    'destination_address' => $request->input('dest_address'),
                    'status'              => $result['status'] ?? 'pending',
                    'price'               => $result['price'] ?? 0,
                ]);
        }

        // 5. Kembalikan respons ke tampilan UI
        return response()->json($result);
    }

    // Fungsi untuk memproses tracking (API)
    public function track(Request $request)
    {
        $request->validate([
            'waybill_id' => 'required',
            'courier' => 'required'
        ]);

        $result = $this->biteship->getTracking($request->waybill_id, $request->courier);
        return response()->json($result);
    }

    public function userOrders()
    {
        $orders = \App\Models\Shipment::where('user_id', auth()->id())->latest()->get();
        return view('pesanan', compact('orders'));
    }

    public function adminOrders()
    {
        $orders = \App\Models\Shipment::with('user')->latest()->get(); 
        return view('admin.pesanan', compact('orders'));
    }

    public function myOrders()
    {

        $orders = \App\Models\Shipment::where('user_id', auth()->id())->latest()->get();
        
        return view('pesanan', compact('orders'));
    }
}