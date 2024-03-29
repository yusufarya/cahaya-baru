
@extends('user-page.layouts.user_main')

@section('content-pages')

<div class="explain-product my-4">
  <hr>
    <div class="heading text-center">
        <div class="pt-3">
        <h3 style="font-size: 26px; font-weight: 600"> {{$title}} </h3>
        </div>
    </div>

    <div class="row justify-content-center bg-secondary-color rounded pb-3 px-3 mt-3 mx-auto w-75">

      <form id="submitForm" action="/my-req-orders" class="pb-3 mt-3" action="registrant" method="GET">
        @csrf
        <div class="row">
          <div class="col-md-4">
            <label for="status"> Status Pesanan</label>
            <select name="status" id="status" class="form-select form-control">
              <option value="00"> Semua Status</option>
              <option value="N" {{ $status == 'N' ? 'selected' : '' }}> Belum Dibayar</option>
              <option value="Y" {{ $status == 'Y' ? 'selected' : '' }}> Dibayar</option>
            </select>
          </div>
          @if ($status == 'Y')
            <div class="col-md-4">
              <label for="status"> Status Pengiriman</label>
              <select name="delivery" id="delivery" class="form-select form-control">
                <option value=""> » Status Pengiriman</option>
                {{-- <option value="0" {{ $delivery == '0' ? 'selected' : '' }}> Pemesanan</option> --}}
                <option value="1" {{ $delivery == '1' ? 'selected' : '' }}> Siap untuk dikirim</option>
                <option value="2" {{ $delivery == '2' ? 'selected' : '' }}> Dalam Perjalanan Pengiriman</option>
                <option value="3" {{ $delivery == '3' ? 'selected' : '' }}> Pesanan telah sampai</option>
                <option value="4" {{ $delivery == '4' ? 'selected' : '' }}> Pesanan telah diterima</option>
              </select>
            </div>
          @endif
        </div>

      </form>
      
      @if (count($my_orders) > 0)
        @foreach ($my_orders as $item)
        <?php 
        // dd($item);
        $qty_dt = $item->qty;
        $price_dt = $item->price;
        $charge = $item->charge;
        $purchase_price = $item->price;
        $total_price = $item->price*$item->qty;
        $nett = ($item->price*$item->qty)+$charge;

        $order_code = $item->code;

        // dd ($item->status);
        if($item->status == 'Y') {
        } else {
          $checkPayment = FALSE;
        }
        $checkPayment = DB::table('order_payments')->where('order_code', $order_code)->first();
        
        ?>
            <div class="mt-3 p-3 card shadow-lg">
                <div class="row">
                    <div class="col-lg-8">
                        <h5 style="font-weight: 600;">{{$order_code}}</h5>
                        <p class="mt-2"><b>Catatan :</b> &nbsp; {{$item->description}}</p>
                        <small class="mt-2"><b>Tanggal :</b> &nbsp; {{date('d-m-Y', strtotime($item->date))}}</small>
                        <div class="alert alert-warning px-2 py-0">
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
                              <th>Jumlah</th>
                              <th style="text-align: right;">
                                <small><sub>{{ $qty_dt == 0 ? 1 : $qty_dt }} x</sub></small>
                                {{ $qty_dt > 0 ? number_format($total_price,2) : number_format($purchase_price*$qty_dt,2) }}
                              </th>
                            </tr>
                            <tr>
                              <th>Ongkos Kirim</th>
                              <th style="text-align: right;">{{ $charge > 0 ? number_format($charge,2) : 0 }}</th>
                            </tr>
                            <tr>
                              <th>Total Harga</th>
                              <th style="text-align: right;">
                                {{-- <small><sub>{{ $qty_dt == 0 ? 1 : $qty_dt }} x</sub></small> --}}
                                {{ $qty_dt > 0 ? number_format($nett,2) : number_format($purchase_price*$qty_dt,2) }}
                              </th>
                            </tr>
                          </table>
                        </div>
                        {{-- <br> --}}
                        @if ($item->price > 0)
                        
                          @if ($checkPayment) 
                                  
                            @if (!$checkPayment->image)
                              <a href="/pay-order-req/{{ $order_code }}" type="button" style="float: right;" class="btn btn-danger ms-2" >Pembayaran</a>
                              <span class="pt-1" style="float: right;">Anda belum mengirimkan bukti pembayaran</span> 
                            @else
                            
                              @switch($item->delivery)
                                @case(1)
                                    <div class="alert mt-4 py-1 alert-info">Siap untuk dikirim</div>
                                    @break
                                @case(2)
                                    <div class="alert mt-4 py-1 alert-primary">Dalam Perjalanan Pengiriman</div>
                                    @break
                                @case(3)
                                    <div class="alert alert-warning py-1 mt-4">Pesanan telah sampai tujuan</div>
                                    <button type="button" class="btn button-submit" onclick="acc_order(`{{ $order_code }}`)">Terima Pesanan</button>
                                    @break
                                @case(4)
                                    <div class="alert alert-success py-1 mt-4">Pesanan telah diterima</div>
                                    @break
                                @default
                                  @switch($checkPayment->status)
                                    @case('Approve')
                                      <div class="alert alert-success py-1 mt-4">Pesanan sedang dipersiapkan</div>
                                      @break
                                    @case('Reject')
                                      <div class="alert alert-success py-1 mt-4">Pesanan ditolak</div>
                                      @break
                                    @default
                                      <div class="alert alert-success py-1 mt-4">Pesanan dalam proses pengecekan</div>  
                                  @endswitch
                              @endswitch
                            @endif
                              
                          @else
                            <a href="/payment-req/{{ $item->code }}" class="btn btn-warning btn-sm mt-3" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Lanjutkan Pembelian">
                              <i class="fas fa-comment-dollar"></i>&nbsp; Pembayaran
                            </a>
                          @endif
                            
                        @else
                            <div class="alert alert-danger">
                              Pesanan anda sedang dalam proses pengecekan
                            </div>
                            <button type="button" style="float: right;" class="btn btn-secondary me-3" onclick="cancelOrder(`{{$order_code}}`)">
                              Batalkan Pesanan
                          </button>
                        @endif
                    </div>
                    <div class="col-lg-4">
                      <div class="mt-3">&nbsp;</div>
                        <img src="{{asset('/storage/'.$item->image)}}" class="w-75" alt="serviceImg">
                    </div>
                </div>
            </div>
        @endforeach
            
      @else
        <br><br><br>
        <hr>
        <div class="row justify-content-center text-center" >
          <span class="alert mt-3 text-danger">Anda belum memiliki pesanan.</span>
        </div>
        <br><br><br><br><br><br><br><br>

      @endif
        <br>
          <hr>
          <hr>
        <div class="row">
          <a href="/custom-request" class="text-decoration-none"><< Kembali</a>
        </div>
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

<div class="modal fade" id="cancelOrder" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><b><i class="fas fa-exclamation-triangle text-warning"></i>&nbsp; Batalkan Pesanan</b></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Anda yakin ingin membatalkan pembayaran? <br> Pesanan ini akan dihapus.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak</button>
        <button type="button" class="btn btn-primary" id="Y">Ya</button>
      </div>
    </div>
  </div>
</div>