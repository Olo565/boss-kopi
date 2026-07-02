<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class LaporanPenjualanExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $orders;

    public function __construct($orders)
    {
        $this->orders = $orders;
    }

    public function collection()
    {
        return $this->orders;
    }

    public function headings(): array
    {
        return [
            'Tanggal', 'No. Struk', 'Kasir', 'Tipe Pesanan',
            'Jumlah Item', 'Subtotal', 'Diskon', 'Ongkir',
            'Total', 'Metode Pembayaran', 'Status',
        ];
    }

    public function map($order): array
    {
        return [
            $order->created_at->format('d/m/Y H:i'),
            $order->nomor_struk,
            $order->kasir->name ?? 'Online',
            ucfirst(str_replace('_', '-', $order->tipe_pesanan)),
            $order->items->count(),
            $order->subtotal,
            $order->diskon,
            $order->ongkir,
            $order->total,
            strtoupper($order->metode_pembayaran ?? '-'),
            $order->getLabelStatus(),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:K1')->getFont()->setBold(true);
        $sheet->getStyle('A1:K1')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('4A3525');
        $sheet->getStyle('A1:K1')->getFont()->getColor()->setRGB('FFFFFF');
        $sheet->getStyle('A1:K1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        return [];
    }
}
