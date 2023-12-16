
@extends('user-page.layouts.user_main')

@section('content-pages')

<div class="explain-product my-4">
  <hr>
    <div class="heading text-center">
        <div class="pt-3">
        <h3 style="font-size: 26px; font-weight: 600"> {{$title}} </h3>
        </div>
    </div>

    <div class="row justify-content-center bg-secondary-color pb-3 px-3 mt-3 mx-auto w-75">
        @foreach ($my_orders as $item)
        <?php 
        $qty_dt = $item->salesOrderDetails->qty;
        $price_dt = $item->salesOrderDetails->price;
        $purchase_price = $item->salesOrderDetails->products->purchase_price;
        ?>
            <div class="mt-3 p-3 card shadow-lg">
                <div class="row">
                    <div class="col-lg-8">
                        <h2>{{$item->salesOrderDetails->products->name}}</h2>
                        <span class="alert alert-info py-0"> {{$item->salesOrderDetails->products->categories->name}}</span>
                        <p class="mt-2">{{$item->description}}</p>
                        <div class="alert alert-warning px-2 py-0">
                          Merek &nbsp; : {{$item->salesOrderDetails->products->brands->name}} <br>
                          Ukuran &nbsp; : {{$item->salesOrderDetails->products->sizes->initial}} <br>
                        </div>
                        <div class="shadow px-2 py-0">
                          <table class="table">
                            <tr>
                              <th>Quantity</th>
                              <th style="text-align: right;">{{ $qty_dt == 0 ? 1 : $qty_dt }}</th>
                            </tr>
                            <tr>
                              <th>Harga</th>
                              <th style="text-align: right;">{{ $qty_dt == 0 ? number_format($purchase_price,2) : number_format($price_dt,2) }}</th>
                            </tr>
                            <tr>
                              <th>Total Harga</th>
                              <th style="text-align: right;">
                                <small><sub>{{ $qty_dt == 0 ? 1 : $qty_dt }} x</sub></small>
                                {{ $qty_dt == 0 ? number_format($purchase_price,2) : number_format($purchase_price*$qty_dt,2) }}
                              </th>
                            </tr>
                          </table>
                        </div>
                        <br>
                        <a href="/payment/{{ $item->id }}" class="btn btn-warning btn-sm mt-3" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Lanjutkan Pembelian">
                          <i class="fas fa-comment-dollar"></i>&nbsp; Pembayaran
                        </a>
                        <a href="/detail-product/{{ $item->salesOrderDetails->products->id }}" class="btn btn-info btn-sm mt-3" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Lanjutkan Detail Produk">
                          <i class="fas fa-info-circle"></i>&nbsp; Detail Produk
                        </a>
                    </div>
                    <div class="col-lg-4">
                        <img src="{{asset('/storage/'.$item->salesOrderDetails->products->image)}}" class="w-75" alt="serviceImg">
                    </div>
                </div>
            </div>
        @endforeach
    </div>

</div>

@endsection

<div class="modal fade" id="printCard" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"><b>Kartu Pembelian</b></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>Modal body text goes here.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
          <button class="btn btn-success" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Kartu Pembelian" >
            <i class="fas fa-print me-1"></i> Cetak
        </button>
        </div>
      </div>
    </div>
  </div>
