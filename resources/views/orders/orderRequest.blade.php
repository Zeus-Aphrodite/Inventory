@extends('layouts.app')

@section('content')
<div class="container-full mx-5">
    <div class="row justify-content-center">
        <div class="col-12 mb-3">
            <div class="row mb-3 border-bottom">
                <div class="col-6">
                    <div class="float-end"><h3>発送依頼</h3></div>
                </div>
                <div class="col-6">
                    <div class="toast align-items-center text-white border-0" id="newOrderToast" style="position: absolute; top: 100px; right: 20px">
                        <div class="d-flex">
                            <div class="toast-body" id="newOrderToastValue">
                                成果的に登録されました。
                            </div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-8">
                    <div class="mb-3 row">
                        <label for="exampleFormControlInput1" class="col-sm-4 col-form-label text-end">会員</label>
                        <div class="col-sm-4">
                            <input class="form-control" id="client_name" type="text" value="{{Auth::user()->name}}">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="exampleFormControlInput1" class="col-sm-4 col-form-label text-end">発送日</label>
                        <div class="col-sm-4">
                            <input class="form-control" id="delivery_date" type="date">
                        </div>
                        <div class="col-sm-4"></div>
                    </div>
                </div>
            </div>
            <div class="row mb-1 pe-3">
                <div class="col-12">
                    <div class="row">
                        <div class="col-lg-8 col-sm-8">
                            <div class="row">
                                <label for="exampleFormControlInput1" class="col-sm-4 col-form-label text-end">CSVで一括登録</label>
                                <div class="col-sm-8">
                                    <button class="btn btn-outline-primary btn-responsive px-lg-5" id="tmpOrdersUploadButton">CSVアップロード</button>
                                    <input hidden type="file" id="ordersFormFileUpload" class="form-control btn btn-outline-primary btn-responsive">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-4">
                            <div class="row">
                                <div class="col-12">
                                    <a href="/ordersd/orderRequest/download/" class="btn btn-outline-primary float-end">依頼用CSVダウンロード</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 table-responsive">
            <table class="table table-hover text-center table-responsive-width">
                <thead class="table-dark align-middle">
                    <tr>
                        <th class="py-3" rowspan="3" scope="col">管理ID</th>
                        <th class="py-3" rowspan="3" scope="col">本のタイトル</th>
                        <th class="py-1 w-30" id="dest_len" colspan="{{count($destinations)}}" scope="col">配送先ID /ラベル</th>
                        <th class="py-3" rowspan="3" scope="col">出荷計</th>
                        <th class="py-3" rowspan="3" scope="col">現在の在庫</th>
                        <th class="py-3" rowspan="3" scope="col">出荷後在庫</th>
                    </tr>
                    <tr>
                        @foreach ($destinations as $k1 => $destination)
                            <th><input id="des_id_{{$k1}}" type="text" class="form-control text-center p-0" value="{{$destination->id}}"></th>
                        @endforeach
                    </tr>
                    <tr>
                        @foreach ($destinations as $k2 => $destination)
                            <td><input id="des_{{$k2}}" type="text" class="form-control text-center p-0" value="{{$destination->destinationLocation}}"></td>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    <input hidden type="number" id="lengthOfDatas" value="{{count($datas)}}">
                        @foreach ($datas as $key => $data)
                            <input hidden type="number" id="goodIdOfEachValue_{{$key}}" value="{{$data['id']}}">
                            <tr>
                                <th class="align-middle" key={{$key}} scope="row">{{$data['manageGoodsId']}}</th>
                                <td class="align-middle" key={{$key}}>{{$data['goodsTitle']}}</td>
                                @foreach ($destinations as $k3 => $destination)
                                    <td class="align-middle">
                                        <input value="" key={{$key}} id="dest_val_{{$key}}_{{$k3}}" class="orderNumber form-control text-center p-0" type="number">
                                    </td>
                                @endforeach
                                <td class="align-middle" key={{$key}} id="orderSum_{{$key}}">0</td>
                                <td class="align-middle" key={{$key}} id="goodsInventory_{{$key}}">{{$data['goodsInventory']}}</td>
                                <td class="align-middle" key={{$key}} id="remainOrder_{{$key}}">{{$data['goodsInventory']}}</td>
                            </tr>
                        @endforeach
                </tbody>
            </table>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <div class="row">
                    <div class="col-4"></div>
                    <div class="col-4"><button id="orderRequestButton" type="button" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#confirmOrderRequestModal">依頼する</button></div>
                    <div class="col-4"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="confirmOrderRequestModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">本当に提出してもよろしいですか？</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    内容を確認してください。
                </div>
                <div class="modal-footer">
                    <button type="button" id="cancelOrderRequest" class="btn" data-bs-dismiss="modal">取り消す</button>
                    <button type="button" id="confirmOrderRequest" class="btn btn-primary text-white">確認</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
