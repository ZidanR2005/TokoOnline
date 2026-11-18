
@extends('v_layouts.app')

@section('content')

<div class="container mt-5">

    <p class="text-muted mb-2">HISTORY</p>

    <h3 class="fw-bold">HISTORY PESANAN</h3>

    <hr style="border-top: 2px solid #ff6347; width: 55px; margin-top: 0;">

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="table-responsive mt-4">
        <table class="table align-middle">

            <thead>
                <tr>
                    <th>ID PESANAN</th>
                    <th>TANGGAL</th>
                    <th>TOTAL BAYAR</th>
                    <th>STATUS</th>
                    <th>DETAIL</th>
                </tr>
            </thead>

            <tbody>
                @forelse($orders as $order)

                    <tr>
                        <td>{{ $order->id }}</td>

                        <td>
                            {{ $order->created_at
                                ? $order->created_at->format('d M Y H:i')
                                : '-' }}
                        </td>

                        <td>
                            Rp {{ number_format(
                                ($order->total_harga ?? 0) +
                                ($order->biaya_ongkir ?? 0),
                                0, ',', '.'
                            ) }}
                        </td>

                        <td>
                          {{ $order->status == 'pending' ? 'Proses' : ucfirst($order->status) }}
                        </td>

                        <td>
                            <a href="#"
                               class="btn btn-sm text-white"
                               style="background-color: #ff6347;">
                                LIHAT DETAIL
                            </a>

                            <a href="#"
                               class="btn btn-sm text-white"
                               style="background-color: #ff6347;">
                                INVOICE
                            </a>
                        </td>
                    </tr>

                @empty

                    <tr>
                        <td colspan="5" class="text-center py-4">
                            Belum ada history pesanan.
                        </td>
                    </tr>

                @endforelse
            </tbody>

        </table>
    </div>

</div>

@endsection
