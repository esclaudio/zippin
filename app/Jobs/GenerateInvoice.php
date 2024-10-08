<?php

namespace App\Jobs;

use App\Models\Order;
use Dompdf\Dompdf;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;

class GenerateInvoice implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Order $order
    ) {}

    public function handle(): void
    {
        $html = view('order.pdf', ['order' => $this->order])->render();

        $pdf = new Dompdf;
        $pdf->loadHtml($html);
        $pdf->setPaper('A4');
        $pdf->render();

        Storage::put(
            sprintf('invoice_00001-%08d.pdf', $this->order->id),
            $pdf->output()
        );
    }
}
