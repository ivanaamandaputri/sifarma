<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PengajuanController extends Controller
{
    public function index()
    {
        // Fetch transactions, including the user relationship, and order by date and user room.
        $transaksi = Transaksi::with('user') // Get user details along with transaction
            ->orderBy('tanggal_order', 'desc') // Order by date of the order
            ->orderBy(User::select('jabatan') // Assuming we might want to order by jabatan or another user field
                ->whereColumn('users.id', 'transaksi.id_users')
                ->limit(1))
            ->paginate(10); // Pagination for better performance

        return view('pengajuan.index', compact('transaksi'));
    }

    public function approve(Request $request, $id)
    {
        try {
            // Find the transaction by ID
            $transaksi = Transaksi::findOrFail($id);

            // Validate the approved amount
            $request->validate([
                'acc' => 'required|integer|min:1|max:' . $transaksi->jumlah_permintaan, // Ensure ACC is within range
            ]);

            $acc = $request->input('acc'); // Amount approved by the admin
            $obat = $transaksi->obat; // Get the related medicine

            // Log for debugging
            Log::info('Jumlah ACC: ' . $acc);
            Log::info('Harga Obat: ' . $obat->harga);

            // Check if stock is sufficient
            if ($obat->stok < $acc) {
                return response()->json(['error' => 'Stok obat tidak mencukupi.'], 400);
            }

            // Calculate the total price
            $total = $acc * $obat->harga;

            // Update transaction status and other details
            $transaksi->update([
                'jumlah_acc' => $acc,
                'status' => 'Disetujui', // Update the status to 'Disetujui'
                'total_harga' => $total, // Calculate the total price
            ]);

            // Update the stock of the medicine
            $obat->stok -= $acc; // Reduce stock based on approved quantity
            $obat->save();

            // Create notification for the operator
            Notification::create([
                'id_users' => $transaksi->id_users, // Operator who requested
                'judul' => 'Pengajuan Disetujui',
                'isi' => 'Pengajuan Anda telah disetujui.',
                'is_read' => false, // Set notification as unread
            ]);

            return response()->json(['message' => 'Transaksi disetujui dan stok berkurang.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function reject(Request $request, $id)
    {
        try {
            // Validate rejection reason
            $request->validate([
                'reason' => 'required|string|max:255', // Ensure reason is provided
            ]);

            // Find the transaction by ID
            $transaksi = Transaksi::findOrFail($id);

            // Ensure the transaction is not already rejected
            if ($transaksi->status === 'Ditolak') {
                return response()->json(['error' => 'Transaksi sudah ditolak.'], 400);
            }

            // Update the transaction with rejection details
            $transaksi->update([
                'status' => 'Ditolak',
                'jumlah_acc' => 0,
                'total_harga' => 0,
                'alasan_penolakan' => $request->input('reason'), // Store rejection reason
            ]);

            // Create a notification for the operator
            Notification::create([
                'id_users' => $transaksi->id_users, // Operator who requested
                'judul' => 'Pengajuan Ditolak',
                'isi' => 'Pengajuan Anda ditolak dengan alasan: ' . $request->input('reason'),
                'is_read' => false, // Set notification as unread
            ]);

            return response()->json(['message' => 'Transaksi berhasil ditolak.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan saat memproses penolakan transaksi.'], 500);
        }
    }

    public function getNotification()
    {
        $level = auth()->user()->role; // Get the role of the current user

        // Fetch notifications based on user role (admin/operator)
        if ($level === 'admin') {
            $notification = Notification::where('is_read', false)->get(); // Notifications for admin
        } elseif ($level === 'operator') {
            $notification = Notification::where('id_users', auth()->id())
                ->where('is_read', false) // Fetch unread notifications for the operator
                ->get();
        } else {
            abort(403, 'Unauthorized access.');
        }

        // Return the view with the notifications
        return view('dashboard', compact('Notification'));
    }

    public function bacaNotification($id)
    {
        // Mark the notification as read
        $notification = Notification::findOrFail($id);
        $notification->update(['is_read' => true]);

        // Redirect back to the previous page
        return redirect()->back();
    }
}
