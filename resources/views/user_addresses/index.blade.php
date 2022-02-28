@extends('layouts.app')
@section('title', 'Shipping address list')

@section('content')
  <div class="row">
    <div class="col-md-10 offset-md-1">
      <div class="card panel-default">
        <div class="card-header">
          Shipping address list
          <a href="{{ route('user_addresses.create') }}" class="float-right">New shipping address</a>
        </div>
        <div class="card-body">
          <table class="table table-bordered table-striped">
            <thead>
            <tr>
              <th>Receiver</th>
              <th>address</th>
              <th>post code</th>
              <th>telephone</th>
              <th>operate</th>
            </tr>
            </thead>
            <tbody>
            @foreach($addresses as $address)
              <tr>
                <td>{{ $address->contact_name }}</td>
                <td>{{ $address->full_address }}</td>
                <td>{{ $address->zip }}</td>
                <td>{{ $address->contact_phone }}</td>
                <td>
                  <a href="{{ route('user_addresses.edit', ['user_address' => $address->id]) }}" class="btn btn-primary">Revise</a>
                  <button class="btn btn-danger btn-del-address" type="button" data-id="{{ $address->id }}">delete</button>
                </td>
              </tr>
            @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('scriptsAfterJs')
<script>
$(document).ready(function() {
  $('.btn-del-address').click(function() {
    var id = $(this).data('id');
    swal({
        title: "Are you sure you want to delete this address?",
        icon: "warning",
        buttons: ['Cancel', 'Sure'],
        dangerMode: true,
      })
    .then(function(willDelete) {
      if (!willDelete) {
        return;
      }
      axios.delete('/user_addresses/' + id)
        .then(function () {
          location.reload();
        })
    });
  });
});
</script>
@endsection
