@extends('layouts.app')

@section('content')
<div class="container-full mx-5">
    <div class="row justify-content-center">
        <div class="col-12 mb-3">
            <div class="row mb-3 border-bottom">
                <div class="col-5"></div>
                <div class="col-3">
                    <div class=""><h3 class="tab-title">会員情報</h3></div>
                </div>
                <div class="col-4"></div>
            </div>
            <div class="row">
                <div class="col-lg-9 col-md-12 order-manage-font">
                    <div class="mb-2 row">
                        <label for="searchUserOrderField" class="col-xl-2 col-lg-3 col-md-4 col-form-label">絞り込み</label>
                        <div class="col-xl-4 col-lg-6 col-md-6 d-flex">
                            <input class="form-control" id="searchUserOrderField" type="text" placeholder="山田太郎">
                            <a href="/orders" class="btn btn-outline-primary w-25" id="searchUserOrderButton">検索</a>
                        </div>
                        <div class="col-xl-6 col-lg-12 col-md-12 mt-xl-0 mt-sm-2">
                            <div class="d-flex row">
                                <label for="periodDate" class="col-xl-4 col-lg-3 col-md-4 col-form-label text-xl-end">期間指定</label>
                                <div class="col-xl-8 col-lg-8 col-md-8 d-flex">
                                    <div class="w-50">
                                        <input type="date" class="form-control w-100" id="periodStartDate">
                                    </div>
                                    <div class="w-50">
                                        <input type="date" class="form-control w-100" id="periodEndDate">
                                    </div>
                                    <a hidden href="/orders/search" id="searchWithDate"></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="col-3 float-end">
                        <a href="/orders/createNewOrder" type="button" id="createNewOrderButton1" class="createNewOrder btn btn-warning float-end w-75">新しい依頼</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 table-responsive">
            <table class="table table-hover text-center table-responsive-width" title="{{Auth::user()->company_name}}">
                <thead class="table-dark align-middle">
                    <tr>
                        <th>受注ID</th>
                        <th>依頼日</th>
                        <th>発送日</th>
                        <th>ステータス</th>
                        <th>詳細はこちら</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($datas as $data)
                        <tr>
                            <th class="align-middle">{{ $data->order_name }}</th>
                            <td class="align-middle">{{ $data->created_at }}</td>
                            <td class="align-middle">{{ $data->delivery_date }}</td>
                            <td class="align-middle">{{ $data->status }}</td>
                            <td class="align-middle">
                                <a href="/orders/{{$data->user_id}}/{{$data->id}}">依頼内容確認</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="row">
            <div class="col-3"></div>
            <div class="col-6 d-flex justify-content-center">
                {{ $datas->links() }}
            </div>
            <div class="col-3"></div>
        </div>
    </div>
</div>
@endsection
