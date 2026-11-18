
@extends('v_layouts.app')

@section('content')

<div class="container">
    <h3 class="mb-4">KERANJANG BELANJA</h3>

    @if($order->orderItems->isEmpty())
        <div class="alert alert-info">
            Keranjang belanja kosong.
        </div>
    @else

    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>PRODUK</th>
                    <th class="text-center">HARGA</th>
                    <th class="text-center">QUANTITY</th>
                    <th class="text-center">TOTAL</th>
                </tr>
            </thead>

            <tbody>
                @foreach($order->orderItems as $item)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <img
                                src="{{ asset('storage/img-produk/thumb_md_' . $item->produk->foto) }}"
                                width="70"
                                height="70"
                                style="object-fit: cover;"
                                alt="{{ $item->produk->nama_produk }}"
                            >

                            <div>
                                <strong>{{ $item->produk->nama_produk }}</strong>
                                <br>
                                <small>
                                    Berat: {{ $item->produk->berat }} Gram
                                </small>
                                <br>
                                <small>
                                    Stok: {{ $item->produk->stok }}
                                </small>
                            </div>
                        </div>
                    </td>

                    <td class="text-center">
                        Rp {{ number_format($item->harga, 0, ',', '.') }}
                    </td>

                    <td class="text-center">
                        {{ $item->quantity }}
                    </td>

                    <td class="text-center text-danger fw-bold">
                        Rp {{ number_format(
                            $item->harga * $item->quantity,
                            0, ',', '.'
                        ) }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="row justify-content-end">
        <div class="col-md-6">
            <table class="table table-bordered">
                <tr>
                    <th>SUBTOTAL</th>
                    <td>
                        Rp {{ number_format($order->total_harga, 0, ',', '.') }}
                    </td>
                </tr>

                <tr>
                    <th>Ongkos Kirim</th>
                    <td>
                        Rp {{ number_format($order->biaya_ongkir, 0, ',', '.') }}
                        <br>
                        <small>
                            {{ strtoupper($order->kurir) }}
                            {{ $order->layanan_ongkir }}
                            <br>
                            Estimasi: {{ $order->estimasi_ongkir }}
                        </small>
                    </td>
                </tr>

                <tr>
                    <th>TOTAL BAYAR</th>
                    <td class="text-danger fw-bold fs-5">
                        Rp {{ number_format(
                            $order->total_harga + $order->biaya_ongkir,
                            0, ',', '.'
                        ) }}
                    </td>
                </tr>
            </table>

     <div class="text-end mt-4">
    <a href="{{ route('order.selectShipping') }}"
       class="btn btn-secondary">
        KEMBALI
    </a>

    <form action="{{ route('order.bayarSekarang') }}"
          method="POST"
          class="d-inline">
        @csrf

        <button type="submit" class="btn btn-danger">
            BAYAR SEKARANG
        </button>
    </form>
</div>
        </div>
    </div>

    @endif
</div>

@endsection