<x-mail::message>
# ¡Gracias por tu compra!

Tu orden #{{ $order->hashId }} ha sido creada exitosamente.

## Detalles de la Orden
- **Total:** {{ $order->formattedTotal }}
</x-mail::message>
