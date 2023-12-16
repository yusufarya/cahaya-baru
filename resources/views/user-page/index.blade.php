
@extends('user-page.layouts.user_main')

@section('header-pages')
<div class="banner">
  <div id="carouselExampleIndicators" class="carousel slide">
      <div class="carousel-indicators">
        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
      </div>
      <div class="carousel carousel-inner">
        <div class="carousel-item item active">
          <img src="{{ asset('img/header1.png') }}" class="d-block w-100" alt="header1">
        </div>
        <div class="carousel-item item">
            <img src="{{ asset('img/header2.png') }}" class="d-block w-100" alt="header2">
        </div> 
      </div>
      <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
      </button>
  </div>
</div>
@endsection

@section('content-pages')

<h4 class="mt-4 text-center">Kategori Produk</h4>
<div class="service-panel my-3 mx-1 bg-white shadow row justify-content-center" >
  <div class="parent-category-panel mt-3">
    
    @foreach ($category as $item)
        
      <div class="category-panel">
        <a href="/our-products/{{$item->id}}" class="text-decoration-none text-dark-emphasis">
          <div class="box-category">
            <div class="text-center">
              <b class="text-center">{{ $item->name }} </b>
            </div>
          </div>
        </a>
      </div>

    @endforeach

  </div>

</div>


@endsection