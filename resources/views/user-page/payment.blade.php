@extends('user-page.layouts.user_main')

@section('content-pages')

<div class="explain-product my-4">
  <div class="heading text-center ">
    <div class="pt-3">
      <h3 style="font-size: 26px; font-weight: 600"> Rincian Pembayaran </h3>
    </div>
  </div>

  <div class="row mt-3 bg-secondary-color mx-3">

    <div class="alert alert-danger">
        <b>Alamat Pengiriman</b> : {{ $auth_user->address }}</b> &nbsp;
        <b>No. Telp</b> : {{ $auth_user->phone }}</b>
    </div>

    <div class="row bg">

        <div class="col-lg-3 col-md-3 col-sm-6 mb-3">
            <div class="card shadow-sm p-2" style="width: 100%;">
                @if ($resultData->salesOrderDetails->products->image)
                    <img src="{{ asset('/storage').'/'.$resultData->salesOrderDetails->products->image }}" class="card-img-top" alt="{{$resultData->salesOrderDetails->products->id}}">
                @else
                    <img src="{{ asset('/img/logo.png') }}" class="card-img-top p-3" alt="{{$resultData->salesOrderDetails->products->id}}">
                @endif
            </div>
        </div>
    
        <div class="col mx-3">
            <h4 style="font-size: 28px; font-weight: 700; text-transform: uppercase;"> {{ $resultData->salesOrderDetails->products->name }} </h4>
            <div class="mb-2">
                <small class="alert alert-warning py-0">{{ $resultData->salesOrderDetails->products->categories->name }}</small>
                <small class="alert alert-info py-0">Stok :{{ $resultData->salesOrderDetails->products->inventory->stock }}</small>
            </div>
            <p>
                <small class="card-text"> 
                    Ukuran : {{ $resultData->salesOrderDetails->products->sizes->initial }}
                </small><br>
                <small class="card-text"> 
                    Merek : {{ $resultData->salesOrderDetails->products->brands->name }}
                </small>
            </p>
            <p class="text-black " style="font-size: 16.5px; line-height: 1.6; text-align: justify"><?= $resultData->salesOrderDetails->products->description ?></p>
            <div class="row">
                <div class="col-md-2">Quantity</div>
                <div class="col-md-2"></div>
                <div class="col-md-2">
                    <input type="number" onchange="changeQty()" onkeyup="onlyNumbers(this)" name="qty_dt" id="qty_dt" class="form-control" value="1">
                </div>
            </div>
            <div class="row">
                <div class="col-md-2">Harga</div>
                <div class="col-md-2"></div>
                <div class="col-md-2">
                    <input type="text" name="price_dt" id="price_dt" class="form-control bg-transparent" readonly value="{{ number_format($resultData->salesOrderDetails->products->selling_price,2) }}">
                    <input type="hidden" name="price" id="price" class="form-control bg-transparent" readonly value="{{ $resultData->salesOrderDetails->products->selling_price }}">
                </div>
            </div>
            <div class="row">
                <div class="col-md-2">Total Harga</div>
                <div class="col-md-2"></div>
                <div class="col-md-2">
                    <input type="text" name="total_price" id="total_price" class="form-control bg-transparent" readonly value="{{ number_format($resultData->salesOrderDetails->products->selling_price,2) }}">
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-2">Jenis Pengiriman</div>
                <div class="col-md-2"><span class="alert alert-success py-0">Local</span></div>
                <div class="col-md-2">
                    <input type="text" name="charge_" id="charge_" class="form-control bg-transparent" readonly value="{{ number_format(5000,2) }}">
                    <input type="hidden" name="charge" id="charge" class="form-control bg-transparent" readonly value="5000">
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-4"><b>Total Pembayaran</b></div>
                <div class="col-md-2">
                    <input type="text" name="netto" id="netto" class="form-control bg-transparent" style="font-weight: 600;" readonly value="5000">
                </div>
            </div>
        </div>
        <hr class="mx-3 mt-3">

        <div class="bg-secondary-color py-4">
            <button type="button" style="float: right;" class="btn btn-danger" onclick="payOrder(`{{ $resultData->id }}`)">Check Out</button>
            <button type="button" style="float: right;" class="btn btn-secondary me-3" onclick="cancelOrder(`{{$resultData->id}}`)">
                Batalkan Pesanan
            </button>
        </div>

    </div>

  </div>

</div>

@endsection

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
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id="Y">Ya</button>
        </div>
      </div>
    </div>
</div>