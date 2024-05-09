@extends('layouts.app')

@section('content')
<div class="container-full mx-5">
    <div class="row justify-content-center">
        <div class="col-12 mb-3">
            <div class="row mb-3 border-bottom">
                <div class="col-5"></div>
                <div class="col-3">
                    <div class=""><h3 class="tab-title">受注管理</h3></div>
                </div>
                <div class="col-4"></div>
            </div>
            <div class="row">
                <div class="col-lg-9 col-md-12 order-manage-font">
                    <div class="mb-3 row">
                        <label for="ordersForEveryUserField" class="col-xl-2 col-lg-3 col-md-4 col-form-label">絞り込み検索</label>
                        <div class="col-xl-6 col-lg-5 col-md-6 d-flex">
                            <input class="form-control" id="ordersForEveryUserField" type="text" placeholder="">
                            <a href="/orders" id="searchOrdersForEveryUser" class="btn btn-outline-primary w-25">検索</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 table-responsive">
            <table class="table table-hover text-center table-responsive-width">
                <thead class="table-dark align-middle">
                    <tr>
                        <th>受注ID</th>
                        <th>クライアント名</th>
                        <th>ステータス</th>
                        <th>詳細はこちら</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr>
                            <th class="align-middle">{{ $order->order_name }}</th>
                            <td class="align-middle">{{ $userNames[$order->id]['name'] }}</td>
                            <td class="align-middle">{{ $order->status }}</td>
                            <td class="align-middle">
                                <a href="/orders/{{$userNames[$order->id]['user_id']}}/{{$order->id}}">詳細はこちら</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="row">
            <div class="col-3"></div>
            <div class="col-6 d-flex justify-content-center">
                {{ $orders->links() }}
            </div>
            <div class="col-3"></div>
        </div>
    </div>
</div>
@endsection
