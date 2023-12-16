
@extends('user-page.layouts.user_main')

@section('content-pages')

<?php 
// dd(session());
?>

<div class="explain-product my-5 rounded">

  <div class="row my-3 p-3">
    @if (session()->has('message'))
        <div class="alert alert-danger py-1 text-center">
            {{-- <a href="/update-profile" class="text-decoration-none text-dark font-weight-bolder"> {{ session()->get('message') }}</a> --}}
            <a href="/update-profile" class="text-decoration-none text-dark font-weight-bolder"> <?php echo session()->get('message') ?></a>
        </div>
    @endif
    <div class="col-lg-3 col-md-3 col-sm-6 mb-3">
    
        <div class="card shadow-sm p-2" style="width: 100%;">
            @if ($product->image)
                <img src="{{ asset('/storage').'/'.$product->image }}" class="card-img-top" alt="{{$product->id}}">
            @else
                <img src="{{ asset('/img/logo.png') }}" class="card-img-top p-3" alt="{{$product->id}}">
            @endif
        </div>

    </div>

    <div class="col mx-3">
        <h4 style="font-size: 28px; font-weight: 700; text-transform: uppercase;"> {{ $product->name }} </h4>
        <div class="mb-2">
            <small class="alert alert-warning py-0">{{ $product->categories->name }}</small>
            <small class="alert alert-info py-0">Stok :{{ $product->inventory ? $product->inventory->stock : 0 }}</small>
        </div>
        <p>
            <small class="card-text"> 
                Ukuran : {{ $product->sizes->initial }}
            </small><br>
            <small class="card-text"> 
                Merek : {{ $product->brands->name }}
            </small>
        </p>
        <p class="text-black " style="font-size: 16.5px; line-height: 1.6; text-align: justify"><?= $product->description ?></p>
        <a href="/checkDataUser/{{$product->id}}" class="btn bg-warning text-white"><i class="fas fa-cart-plus"></i> Keranjang</a> |
        <a href="/checkDataUser/{{$product->id}}" class="btn bg-danger text-white"><i class="fas fa-dollar-sign"></i> Beli Sekarang</a>

    </div>
    <hr class="mx-3 mt-3">

  </div>

  <div class="row">
    
  </div>

</div>

@endsection