@extends('layouts.app')
@section('title', 'my collection')

@section('content')
<div class="row">
<div class="col-lg-10 offset-lg-1">
  <div class="card">
    <div class="card-header">my collection</div>
    <div class="card-body">
      <div class="row products-list">
        @foreach($products as $product)
          <div class="col-3 product-item">
            <div class="product-content">
              <div class="top">
                <div class="img">
                  <a href="{{ route('products.show', ['product' => $product->id]) }}">
                    <img src="{{ $product->image_url }}" alt="">
                  </a>
                </div>
                <div class="price"><b>$</b>{{ $product->price }}</div>
                <a href="{{ route('products.show', ['product' => $product->id]) }}">{{ $product->title }}</a>
              </div>
              <div class="bottom">
                <div class="sold_count">sales <span>{{ $product->sold_count }}Pen</span></div>
                <div class="review_count">evaluate <span>{{ $product->review_count }}</span></div>
              </div>
            </div>
          </div>
        @endforeach
      </div>
      <div class="float-right">{{ $products->render() }}</div>
    </div>
  </div>
</div>
</div>
@endsection
