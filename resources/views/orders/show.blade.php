@extends('layouts.app')
@section('title', 'check order')

@section('content')
<div class="row">
<div class="col-lg-10 offset-lg-1">
<div class="card">
  <div class="card-header">
    <h4>order details</h4>
  </div>
  <div class="card-body">
    <table class="table">
      <thead>
      <tr>
        <th>Product information</th>
        <th class="text-center">unit price</th>
        <th class="text-center">quantity</th>
        <th class="text-right item-amount">小计</th>
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
          <td class="sku-price text-center vertical-middle">${{ $item->price }}</td>
          <td class="sku-amount text-center vertical-middle">{{ $item->amount }}</td>
          <td class="item-amount text-right vertical-middle">${{ number_format($item->price * $item->amount, 2, '.', '') }}</td>
        </tr>
      @endforeach
      <tr><td colspan="4"></td></tr>
    </table>
    <div class="order-bottom">
      <div class="order-info">
        <div class="line"><div class="line-label">Shipping address:</div><div class="line-value">{{ join(' ', $order->address) }}</div></div>
        <div class="line"><div class="line-label">order notes:</div><div class="line-value">{{ $order->remark ?: '-' }}</div></div>
        <div class="line"><div class="line-label">Order number:</div><div class="line-value">{{ $order->no }}</div></div>
        <div class="line">
          <div class="line-label">Logistics status:</div>
          <div class="line-value">{{ \App\Models\Order::$shipStatusMap[$order->ship_status] }}</div>
        </div>
        @if($order->ship_data)
        <div class="line">
          <div class="line-label">Logistics information:</div>
          <div class="line-value">{{ $order->ship_data['express_company'] }} {{ $order->ship_data['express_no'] }}</div>
        </div>
        @endif
        @if($order->paid_at && $order->refund_status !== \App\Models\Order::REFUND_STATUS_PENDING)
        <div class="line">
          <div class="line-label">Refund Status:</div>
          <div class="line-value">{{ \App\Models\Order::$refundStatusMap[$order->refund_status] }}</div>
        </div>
        <div class="line">
          <div class="line-label">Reason for refund:</div>
          <div class="line-value">{{ $order->extra['refund_reason'] }}</div>
        </div>
        @endif
      </div>
      <div class="order-summary text-right">
        @if($order->couponCode)
        <div class="text-primary">
          <span>discount information:</span>
          <div class="value">{{ $order->couponCode->description }}</div>
        </div>
        @endif
        <div class="total-amount">
          <span>Total order price:</span>
          <div class="value">${{ $order->total_amount }}</div>
        </div>
        <div>
          <span>Order Status:</span>
          <div class="value">
            @if($order->paid_at)
              @if($order->refund_status === \App\Models\Order::REFUND_STATUS_PENDING)
              Paid
              @else
                {{ \App\Models\Order::$refundStatusMap[$order->refund_status] }}
              @endif
            @elseif($order->closed)
            closed
            @else
            unpaid
            @endif
          </div>

          @if(isset($order->extra['refund_disagree_reason']))
          <div>
            <span>Reason for refusal of refund:</span>
            <div class="value">{{ $order->extra['refund_disagree_reason'] }}</div>
          </div>
          @endif

          @if(!$order->paid_at && !$order->closed)
          <div class="payment-buttons">
            <a class="btn btn-primary btn-sm" href="{{ route('payment.alipay', ['order' => $order->id]) }}">pay by AliPay</a>
            <button class="btn btn-sm btn-success" id='btn-wechat'>WeChat Pay</button>
          </div>
          @endif
          @if($order->ship_status === \App\Models\Order::SHIP_STATUS_DELIVERED)
          <div class="receive-button">
            <button type="button" id="btn-receive" class="btn btn-sm btn-success">confirm the receipt of goods</button>
          </div>
          @endif
          @if($order->paid_at && $order->refund_status === \App\Models\Order::REFUND_STATUS_PENDING)
          <div class="refund-button">
            <button class="btn btn-sm btn-danger" id="btn-apply-refund">Request a refund</button>
          </div>
          @endif
        </div>


      </div>
    </div>
  </div>
</div>
</div>
</div>
@endsection

@section('scriptsAfterJs')
<script>
  $(document).ready(function() {
    $('#btn-wechat').click(function() {
      swal({
        content: $('<img src="{{ route('payment.wechat', ['order' => $order->id]) }}" />')[0],
        buttons: ['close', 'Payment completed'],
      })
      .then(function(result) {
        if (result) {
          location.reload();
        }
      })
    });

    $('#btn-receive').click(function() {
      swal({
        title: "Are you sure you have received the item?",
        icon: "warning",
        dangerMode: true,
        buttons: ['Cancel', 'Acknowledgment of receipt'],
      })
      .then(function(ret) {
        if (!ret) {
          return;
        }
        axios.post('{{ route('orders.received', [$order->id]) }}')
          .then(function () {
            location.reload();
          })
      });
    });

    $('#btn-apply-refund').click(function () {
      swal({
        text: 'Please enter the reason for the refund',
        content: "input",
      }).then(function (input) {
        if(!input) {
          swal('Refund reason cannot be empty', '', 'error');
          return;
        }
        axios.post('{{ route('orders.apply_refund', [$order->id]) }}', {reason: input})
          .then(function () {
            swal('Successfully apply for a refund', '', 'success').then(function () {
              location.reload();
            });
          });
      });
    });


  });
</script>
@endsection
