<?php

// Kode ditulis oleh :
// Nama  : Fadhiil Akmal Hamizan
// Github: Axmalz
// NRP   : 5026231128
// Kelas : PPPL B

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

use App\Models\Notification;

class CourierController extends Controller
{
    public function index(Request $request)
    {
        $couriers = ['Reksy', 'Adit', 'Rama', 'Abi', 'Budi'];
        $selectedCourier = $request->query('name', 'Reksy'); // Default Reksy

        // Ambil order yang ditugaskan ke kurir ini
        $orders = Order::where('courier_name', $selectedCourier)
                       ->with(['book', 'buyer', 'seller'])
                       ->orderByDesc('created_at')
                       ->get();

        return view('courier.index', compact('couriers', 'selectedCourier', 'orders'));
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        // Validasi: Jika sudah delivered, tidak boleh ubah
        if ($order->status == 'Delivered') {
            return back()->with('error', 'Pesanan sudah selesai!');
        }

        $order->status = $request->status;
        $order->courier_message = $request->message;
        $order->save();

        if ($request->status == 'Delivered') {
            Notification::create([
                'user_id' => $order->buyer_id, // Kirim ke pembeli
                'title'   => 'Order Delivered!',
                'message' => "Buku '{$order->book->judul_buku}' telah sampai di tujuan.",
                'type'    => 'transaction',
                'icon'    => 'icon-notif-truck.png'
            ]);
        }

        return back()->with('success', 'Status berhasil diperbarui!');
    }

    public function statistics(Request $request)
    {
        $couriers = ['Reksy', 'Adit', 'Rama', 'Abi', 'Budi'];
        $selectedCourier = $request->query('name', 'Reksy');

        // Hitung statistik
        $stats = Order::where('courier_name', $selectedCourier)
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        // Pastikan semua key ada
        $data = [
            'Packing' => $stats['Packing'] ?? 0,
            'Picked' => $stats['Picked'] ?? 0,
            'In Transit' => $stats['In Transit'] ?? 0,
            'Delivered' => $stats['Delivered'] ?? 0,
        ];

        $totalHeld = array_sum($data);
        $totalDelivered = $data['Delivered'];

        return view('courier.stats', compact('couriers', 'selectedCourier', 'data', 'totalHeld', 'totalDelivered'));
    }
}
