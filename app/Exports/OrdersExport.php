<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class OrdersExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * Ambil semua data order
    */
    public function collection()
    {
        // Ambil semua data, urutkan dari terbaru
        return Order::with('user')->orderBy('created_at', 'desc')->get();
    }

    /**
     * Judul Kolom di Excel (Header)
     */
    public function headings(): array
    {
        return [
            'ID Order',
            'No Invoice',
            'Tanggal Transaksi',
            'Nama Pelanggan',
            'Alamat Pengiriman',
            'Metode Bayar',
            'Metode Kirim',
            'Status',
            'Total Bayar (Rp)',
        ];
    }

    /**
     * Isi Data per Baris
     */
    public function map($order): array
    {
        return [
            $order->id,
            $order->invoice_number,
            $order->created_at->format('d-m-Y H:i'), // Format Tanggal
            $order->recipient_name,
            $order->address ?? '-',
            strtoupper($order->payment_method),
            strtoupper($order->delivery_method),
            strtoupper($order->status),
            $order->total_price,
        ];
    }
}