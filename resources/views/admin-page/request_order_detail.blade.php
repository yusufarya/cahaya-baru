@extends('admin-page.layouts.main_layout')

@section('content-pages')

<div class="content-header">
  <div class="container-fluid">
    <div class="row my-2">
      <div class="col-sm-6">
        <h3 class="m-0 ml-2">{{ $title}}</h3>
      </div><!-- /.col --> 
    </div><!-- /.row -->
    <hr style="margin-bottom: 0">
  </div><!-- /.container-fluid -->
</div>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row mx-2">
            <div class="card mx-1 elevation-1 p-3 w-100">
                
                <div class="row my-4 mx-3">
                    
                    <div class="col-lg-4 col-md-4 col-sm-4 mt-2">
                        <label for="vendor_code">Nomor Transaksi</label>
                        <input type="text" class="form-control" name="order_code" id="order_code" readonly value="{{ $resultData->code }}">
                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-4 mt-2">
                        <label for="text">Nama Pelanggan</label>
                        <input type="text" name="text" id="text" class="form-control" value="{{ $resultData->customers->fullname }}" readonly>
                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-4 mt-2">
                        <label for="date">Tanggal</label>
                        <input type="date" name="date" id="date" class="form-control" value="{{ date('Y-m-d', strtotime($resultData->date)) }}" readonly>
                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-4 mt-2">
                        <label for="size">Ukuran</label>
                        <input type="text" name="size" id="size" class="form-control" value="{{ $resultData->sizes->initial .' - '.$resultData->sizes->name }}" readonly>
                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-4 mt-2">
                        <label for="qty">Quantity</label>
                        <input type="text" name="qty" id="qty" class="form-control" value="{{ number_format($resultData->qty,2) }}" readonly>
                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-4 mt-2">
                        <label for="price">Harga</label>
                        <input type="text" name="price" id="price" class="form-control" onkeyup="formatRupiah(this, this.value)" value="{{ number_format($resultData->price,2) }}">
                    </div>

                    <div class="col-lg-8 col-md-8 col-sm-8 mt-2">
                        <label for="description">Catatan :</label>
                        <textarea rows="5" name="description" id="description" readonly class="form-control">{{ $resultData->description }}</textarea>
                    </div>
                    <div class="col-md-4 col-lg-4 mt-3">
                        {{-- <label for="img">Gambar</label> --}}
                        <img src="{{ asset('/storage').'/'.$resultData->image }}" class="shadow p-2 mt-4 img-fluid w-100" alt="img-request">
                    </div>
                    <button type="button" id="detailPayment" class="btn btn-primary btn-sm mb-2 float-left"> Status Pembayaran </button>
                </div>
    
                <hr style="margin: 0 22px 20px;">
                <div class="row justify-content-end mx-3">
                    <section class="col-lg-4">
                        <section style="float: right;">
                            <a href="/orders" class="btn btn-outline-secondary mr-2"><i class="fas fa-backspace"></i> Kembali</a>
                            <button type="button" id="save" class="btn btn-success">Simpan</button>
                        </section>
                    </section>
                </div>
                
            </div>
        </div>
    </div>

</section> 


<div class="modal fade" id="modal-proses" tabindex="-1">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title ml-2 font-weight-bold">Persetujuan</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        
        <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tidak</button>
        <button type="button" class="btn btn-primary" id="clickDelete">Ya</button>
        </div> 
      </div>
    </div>
</div>


<div class="modal fade" id="modal-detail" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title ml-2 font-weight-bold">Detail Pembayaran</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">
            @if ($orderPayment && $orderPayment->payment_methods)
                
                <div class="row">
                    <div class="col-md-6 col-lg-6 col-sm-5">
                        <img src="{{ asset('/storage').'/'.$orderPayment->image }}" class="img-fluid" alt="imgpayment" style="height: 300px; width: auto;">
                    </div>
                    <div class="col">
                        <div class="mt-3">
                            <label for="payment_methods">Metode Pembayaran</label>
                            <div>{{ $orderPayment->payment_methods->bank_name }} - {{ $orderPayment->payment_methods->account_number }}</div>
                        </div>
                        <div class="mt-3">
                            <label for="payment_methods">Nominal Pembayaran</label>
                            <div>Rp. {{ number_format($resultData->nett,2) }},-</div>
                        </div>
                        <div class="mt-3">
                            <label for="payment_methods">Status Pesanan</label>
                            <div class="w-50 text-center">
                                @switch($orderPayment->status)
                                    @case("Approve")
                                        <div class="alert alert-success"> Disetujui </div>
                                        @break
                                    @case("Reject")
                                            <div class="alert alert-danger"> Ditolak </div>
                                        @break
                                    @default
                                    <div class="alert alert-warning"> Menunggu Persetujuan </div>
                                    <form action="/request-orders/{{$resultData->code}}/detail" method="GET">
                                        @csrf
                                        <input type="hidden" name="status" value="Y">
                                        <button type="submit" class="btn btn-success"> Terima Pesanan </button>
                                    </form>
                                @endswitch
                            </div>
                        </div>
                    </div>
                </div>

            @else
                
            <div class="row justify-content-center"> 
                <br><br>
                <div class="mt-3">
                    <span class="alert alert-danger">Pelanggan belum melakukan pembayaran</span>
                </div>
                <br><br>
            </div>
            @endif
        </div>
        
        <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        </div> 
      </div>
    </div>
</div>

@endsection