<div class="box box-info">
  <div class="box-header with-border">
    <h3 class="box-title">Order serial number:{{ $order->no }}</h3>
    <div class="box-tools">
      <div class="btn-group float-right" style="margin-right: 10px">
        <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-default"><i class="fa fa-list"></i> list</a>
      </div>
    </div>
  </div>
  <div class="box-body">
    <table class="table table-bordered">
      <tbody>
      <tr>
        <td>Buyer:</td>
        <td>{{ $order->user->name }}</td>
        <td>Payment time:</td>
        <td>{{ $order->paid_at->format('Y-m-d H:i:s') }}</td>
      </tr>
      <tr>
        <td>payment method:</td>
        <td>{{ $order->payment_method }}</td>
        <td>Payment channel tracking number:</td>
        <td>{{ $order->payment_no }}</td>
      </tr>
      <tr>
        <td>Shipping address</td>
        <td colspan="3">{{ $order->address['address'] }} {{ $order->address['zip'] }} {{ $order->address['contact_name'] }} {{ $order->address['contact_phone'] }}</td>
      </tr>
      <tr>
        <td rowspan="{{ $order->items->count() + 1 }}">Product list</td>
        <td>product name</td>
        <td>unit price</td>
        <td>quantity</td>
      </tr>
      @foreach($order->items as $item)
      <tr>
        <td>{{ $item->product->title }} {{ $item->productSku->title }}</td>
        <td>${{ $item->price }}</td>
        <td>{{ $item->amount }}</td>
      </tr>
      @endforeach
      <tr>
        <td>order amount:</td>
        <td>${{ $order->total_amount }}</td>
        <td>Shipping Status:</td>
        <td>{{ \App\Models\Order::$shipStatusMap[$order->ship_status] }}</td>
      </tr>
      @if($order->ship_status === \App\Models\Order::SHIP_STATUS_PENDING)
        @if($order->refund_status !== \App\Models\Order::REFUND_STATUS_SUCCESS)
        <tr>
          <td colspan="4">
            <form action="{{ route('admin.orders.ship', [$order->id]) }}" method="post" class="form-inline">
              {{ csrf_field() }}
              <div class="form-group {{ $errors->has('express_company') ? 'has-error' : '' }}">
                <label for="express_company" class="control-label">logistics company</label>
                <input type="text" id="express_company" name="express_company" value="" class="form-control" placeholder="Enter logistics company">
                @if($errors->has('express_company'))
                  @foreach($errors->get('express_company') as $msg)
                    <span class="help-block">{{ $msg }}</span>
                  @endforeach
                @endif
              </div>
              <div class="form-group {{ $errors->has('express_no') ? 'has-error' : '' }}">
                <label for="express_no" class="control-label">shipment number</label>
                <input type="text" id="express_no" name="express_no" value="" class="form-control" placeholder="Enter logistics order number">
                @if($errors->has('express_no'))
                  @foreach($errors->get('express_no') as $msg)
                    <span class="help-block">{{ $msg }}</span>
                  @endforeach
                @endif
              </div>
              <button type="submit" class="btn btn-success" id="ship-btn">Ship</button>
            </form>
          </td>
        </tr>
        @endif
      @else
      <tr>
        <td>Logistics company:</td>
        <td>{{ $order->ship_data['express_company'] }}</td>
        <td>shipment number:</td>
        <td>{{ $order->ship_data['express_no'] }}</td>
      </tr>
      @endif

      @if($order->refund_status !== \App\Models\Order::REFUND_STATUS_PENDING)
      <tr>
        <td>Refund Status:</td>
        <td colspan="2">{{ \App\Models\Order::$refundStatusMap[$order->refund_status] }}，reason：{{ $order->extra['refund_reason'] }}</td>
        <td>
          @if($order->refund_status === \App\Models\Order::REFUND_STATUS_APPLIED)
          <button class="btn btn-sm btn-success" id="btn-refund-agree">agree</button>
          <button class="btn btn-sm btn-danger" id="btn-refund-disagree">disagree</button>
          @endif
        </td>
      </tr>
      @endif

      </tbody>
    </table>
  </div>
</div>

<script>
$(document).ready(function() {
  $('#btn-refund-disagree').click(function() {
    swal({
      title: 'Enter the reason for rejecting the refund',
      input: 'text',
      showCancelButton: true,
      confirmButtonText: "confirm",
      cancelButtonText: "Cancel",
      showLoaderOnConfirm: true,
      preConfirm: function(inputValue) {
        if (!inputValue) {
          swal('reason cannot be empty', '', 'error')
          return false;
        }
        return $.ajax({
          url: '{{ route('admin.orders.handle_refund', [$order->id]) }}',
          type: 'POST',
          data: JSON.stringify({ 
            agree: false,  
            reason: inputValue,
            _token: LA.token,
          }),
          contentType: 'application/json', 
        });
      },
      allowOutsideClick: false
    }).then(function (ret) {
      if (ret.dismiss === 'cancel') {
        return;
      }
      swal({
        title: 'Successful operation',
        type: 'success'
      }).then(function() {
        location.reload();
      });
    });
  });

  $('#btn-refund-agree').click(function() {
    swal({
      title: 'Are you sure you want to refund the money to the user?',
      type: 'warning',
      showCancelButton: true,
      confirmButtonText: "confirm",
      cancelButtonText: "Cancel",
      showLoaderOnConfirm: true,
      preConfirm: function() {
        return $.ajax({
          url: '{{ route('admin.orders.handle_refund', [$order->id]) }}',
          type: 'POST',
          data: JSON.stringify({
            agree: true, 
            _token: LA.token,
          }),
          contentType: 'application/json',
        });
      },
      allowOutsideClick: false
    }).then(function (ret) {
      if (ret.dismiss === 'cancel') {
        return;
      }
      swal({
        title: 'Successful operation',
        type: 'success'
      }).then(function() {
        location.reload();
      });
    });
  });


});
</script>
