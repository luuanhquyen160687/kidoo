@extends('admin.layouts.app')
@section('content')

<div class="row g-3 flex-between-end mb-5">
  <div class="col-auto">
    <h2 class="mb-2">Số dư</h2>
  </div>
</div>

@if(session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
  <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="card mb-4">
  <div class="card-body">
    <h6 class="text-body-tertiary mb-1">Số dư hiện tại</h6>
    <h3 class="mb-0 {{ $balance < 0 ? 'text-danger' : 'text-success' }}">{{ number_format($balance) }} đ</h3>
  </div>
</div>

<div class="card">
  <div class="card-body">
    <table class="table table-sm">
      <thead>
        <tr>
          <th>Thời gian</th>
          <th>Loại giao dịch</th>
          <th>Nội dung</th>
          <th class="text-end">Số tiền</th>
          <th class="text-end">Số dư sau GD</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($transactions as $tx)
        <tr>
          <td>{{ \Illuminate\Support\Carbon::parse($tx->created_at)->format('d/m/Y H:i') }}</td>
          <td>{{ $type_labels[$tx->type] ?? $tx->type }}</td>
          <td>{{ $tx->description ?? '-' }}</td>
          <td class="text-end {{ $tx->direction == 'credit' ? 'text-success' : 'text-danger' }}">
            {{ $tx->direction == 'credit' ? '+' : '-' }}{{ number_format($tx->amount) }} đ
          </td>
          <td class="text-end">{{ number_format($tx->balance_after) }} đ</td>
        </tr>
        @empty
        <tr>
          <td colspan="5" class="text-center text-body-tertiary">Chưa có giao dịch nào.</td>
        </tr>
        @endforelse
      </tbody>
    </table>

    <div class="d-flex justify-content-end">
      {{ $transactions->onEachSide(1)->links('pagination::bootstrap-5') }}
    </div>
  </div>
</div>

@endsection
