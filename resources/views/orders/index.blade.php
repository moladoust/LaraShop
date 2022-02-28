@extends('layouts.app')
@section('title', 'Order List')

@section('content')
<div class="row">
<div class="col-lg-10 offset-lg-1">
<div class="card">
  <div class="card-header">Order List</div>
  <div class="card-body">
    <ul class="list-group">
      @foreach($orders as $order)
        <li class="list-group-item">
          <div class="card">
            <div class="card-header">
              order number:{{ $order->no }}
              <span class="float-right">{{ $order->created_at->format('Y-m-d H:i:s') }}</span>
            </div>
            <div class="card-body">
              <table class="table">
                <thead>
                <tr>
                  <th>Product information</th>
                  <th class="text-center">unit price</th>
                  <th class="text-center">quantity</th>
                  <th class="text-center">total order price</th>
                  <th class="text-center">condition</th>
                  <th class="text-center">operate</th>
                </tr>
                </thead>
                @foreach($order->items as $index => $item)
                  <tr>
                    <td class="product-info">
                      <div class="preview">
                        <a target="_blank" href="{{ route('products.show', [$item->product_id]) }}">
                          <img src="{{ $item->product->image_url }}">
                        </a>
                      </div>
                      <div>
                        <span class="product-title">
                           <a target="_blank" href="{{ route('products.show', [$item->product_id]) }}">{{ $item->product->title }}</a>
                        </span>
                        <span class="sku-title">{{ $item->productSku->title }}</span>
                      </div>
                    </td>
                    <td class="sku-price text-center">${{ $item->price }}</td>
                    <td class="sku-amount text-center">{{ $item->amount }}</td>
                    @if($index === 0)
                      <td rowspan="{{ count($order->items) }}" class="text-center total-amount">${{ $order->total_amount }}</td>
                      <td rowspan="{{ count($order->items) }}" class="text-center">
                        @if($order->paid_at)
                          @if($order->refund_status === \App\Models\Order::REFUND_STATUS_PENDING)
                          Paid
                          @else
                            {{ \App\Models\Order::$refundStatusMap[$order->refund_status] }}
                          @endif
                        @elseif($order->closed)
                        closed
                        @else
                        unpaid<br>
                        Please {{ $order->created_at->addSeconds(config('app.order_ttl'))->format('H:i') }} before completing the payment<br>
                        Otherwise the order will be closed automatically
                        @endif
                      </td>
                      <td rowspan="{{ count($order->items) }}" class="text-center">
                        <a class="btn btn-primary btn-sm" href="{{ route('orders.show', ['order' => $order->id]) }}">check order</a>
                        @if($order->paid_at)
                        <a class="btn btn-success btn-sm" href="{{ route('orders.review.show', ['order' => $order->id]) }}">
                        {{ $order->reviewed ? 'View Reviews' : 'evaluate' }}
                        </a>
                        @endif
                      </td>
                    @endif
                  </tr>
                @endforeach
              </table>
            </div>
          </div>
        </li>
      @endforeach
    </ul>
    <div class="float-right">{{ $orders->render() }}</div>
  </div>
</div>
</div>
</div>
@endsection
