@extends('layouts.app')
@section('title', 'Product list')

@section('content')
<div class="row">
<div class="col-lg-10 offset-lg-1">
<div class="card">
  <div class="card-body">
    <form action="{{ route('products.index') }}" class="search-form">
      <div class="form-row">
        <div class="col-md-9">
          <div class="form-row">
            <div class="col-auto"><input type="text" class="form-control form-control-sm" name="search" placeholder="search"></div>
            <div class="col-auto"><button class="btn btn-primary btn-sm">search</button></div>
          </div>
        </div>
        <div class="col-md-3">
          <select name="order" class="form-control form-control-sm float-right">
            <option value="">Sort by</option>
            <option value="price_asc">Price from low to high</option>
            <option value="price_desc">price from high to low</option>
            <option value="sold_count_desc">Sales from high to low</option>
            <option value="sold_count_asc">Sales from low to high</option>
            <option value="rating_desc">Rating from high to low</option>
            <option value="rating_asc">Rating from low to high</option>
          </select>
        </div>
      </div>
    </form>
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
              <div class="title">
                <a href="{{ route('products.show', ['product' => $product->id]) }}">{{ $product->title }}</a>
              </div>
            </div>
            <div class="bottom">
              <div class="sold_count">sales <span>{{ $product->sold_count }}Pen</span></div>
              <div class="review_count">evaluate <span>{{ $product->review_count }}</span></div>
            </div>
          </div>
        </div>
      @endforeach
    </div>

    <div class="float-right">{{ $products->appends($filters)->render() }}</div>
  </div>
</div>
</div>
</div>
@endsection


@section('scriptsAfterJs')
  <script>
    var filters = {!! json_encode($filters) !!};
    $(document).ready(function () {
      $('.search-form input[name=search]').val(filters.search);
      $('.search-form select[name=order]').val(filters.order);

      $('.search-form select[name=order]').on('change', function() {
        $('.search-form').submit();
      });
    })
  </script>
@endsection
