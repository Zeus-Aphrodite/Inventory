@extends('layouts.app')

@section('content')
<div class="container-full mx-5">
    <div class="row justify-content-center">
        <div class="col-12 mb-3">
            <div class="row mb-3 border-bottom">
                <div class="col-5"></div>
                <div class="col-3">
                    <div class=""><h3 class="tab-title">受注詳細</h3></div>
                </div>
                <div class="col-4">
                    <div class="toast align-items-center text-white bg-success border-0" id="orderToast" style="position: absolute; top: 100px; right: 20px">
                        <div class="d-flex">
                            <div class="toast-body" id="orderToastValueContent">
                                正常に更新されました。
                            </div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-9 col-md-12 order-manage-font">
                    <div class="mb-3 row">
                        <label for="exampleFormControlInput1" class="col-lg-1 col-md-2 col-form-label">会員</label>
                        <div class="col-lg-4 col-md-6">
                            <h3 class="border-bottom">{{$date[5]}}</h3>
                        </div>
                        <div class="col-1g-7 col-md-4"></div>
                    </div>
                    <div class="row">
                        <label for="exampleFormControlInput1" class="col-sm-2 col-form-label">受注日時</label>
                        <div class="col-sm-2">
                            <p class="fs-6 pt-2">{{$date[0]}}</p>
                        </div>
                        <label for="exampleFormControlInput1" class="col-sm-2 col-form-label">発送予定日</label>
                        <div class="col-sm-2">
                            @if (Auth::user()->user_role == 3)
                                <p class="fs-6 pt-2">{{$date[3]}}</p>
                            @else
                                @if ($date[4] == "完了")
                                    <p class="fs-6 pt-2">{{$date[3]}}</p>
                                @else
                                    <input id="estimate_delivery_date" class="form-control pt-2" value="{{$date[3]}}" type="date">
                                @endif
                            @endif
                        </div>
                        <label for="exampleFormControlInput1" class="col-sm-2 col-form-label px-md-0">最終更新日時</label>
                        <div class="col-sm-2">
                            <p class="pt-2">{{$date[1]}}</p>
                        </div>
                    </div>
                    <div class="row">
                        <label for="exampleFormControlInput1" class="col-sm-2 col-form-label">ステータス</label>
                        <div class="col-sm-2">
                            @if (Auth::user()->user_role == 3)
                                <input class="form-control" id="orderStatus" type="text" readonly value="{{$date[4]}}">
                            @else
                                @if ($date[4] == "完了")
                                    <div class="form-control" id="orderStatus">{{$date[4]}}</div>
                                @else
                                    <select name="orderStatus" id="orderStatus" class="form-control" id="">
                                        @if ($date[4] == "発送前")
                                            <option selected value="発送前">発送前</option>
                                        @else
                                            <option value="発送前">発送前</option>
                                        @endif
                                        @if ($date[4] == "発送中")
                                            <option selected value="発送中">発送中</option>
                                        @else
                                            <option value="発送中">発送中</option>
                                        @endif
                                        @if ($date[4] == "完了")
                                            <option selected value="完了">完了</option>
                                        @else
                                            <option value="完了">完了</option>
                                        @endif
                                    </select>
                                @endif
                            @endif
                        </div>
                        <label for="exampleFormControlInput1" class="col-sm-2 col-form-label">発送完了日</label>
                        <div class="col-sm-2">
                            @if ($date[4] == "完了")
                                <p class="fs-6 pt-2">{{$date[2]}}</p>
                            @else
                            <p class="fs-6 pt-2"></p>
                            @endif
                            {{-- <p class="fs-6 pt-2">{{$date[2]}}</p> --}}
                        </div>
                        <div class="col-md-4  d-lg-none d-sm-block">
                            @if (Auth::user()->user_role == 3)
                                <div class="row">
                                    <div class="col-12"><a href="/orders/createNewOrder" type="button" id="createNewOrderButton1" class="createNewOrder btn btn-warning w-100 float-end">新しい依頼</a></div>
                                </div>
                            @else
                                <div class="row">
                                    <div class="col-12"><button type="button" id="updateOrdersButton1" class="updateOrdersButtonClass btn btn-warning w-100 float-end">更新する</button></div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 d-lg-block d-sm-none">
                    @if (Auth::user()->user_role != 3)
                        <div>
                            <button type="button" id="updateOrdersButton1" class="updateOrdersButtonClass btn btn-warning w-75 float-end">更新する</button>
                        </div>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-9"></div>
                <div class="col-3">
                    <a href="/orders/download/{{$date[6]}}" class="btn btn-outline-primary w-md-75 w-sm-100 float-end">csvダウンロード</a>
                </div>
            </div>
        </div>
        <div class="col-12 table-responsive">
            <table class="table table-hover text-center table-responsive-width" style="min-width: 1800px" title="{{$date[7]}}">
                <thead class="table-dark align-middle">
                    <tr>
                        <th rowspan="2">管理ID</th>
                        <th rowspan="2">本のタイトル</th>
                        <th id="dest_len_m"  style="min-width: 400px" colspan="{{count($locations)}}" class="w-30">配送先</th>
                        <th rowspan="2">出荷計</th>
                        <th rowspan="2">在庫</th>
                        <th rowspan="2">出荷後在庫</th>
                    </tr>
                    <tr>
                        @foreach ($locations as $key => $location)
                            <th id="dest_id_header_{{$key}}"  style="min-width: 100px">{{$location}}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    <input hidden type="number" id="lengthOfDatas" orderId="{{$date[6]}}" value="{{count($datas)}}">
                        @foreach ($datas as $key => $data)
                            <input hidden type="number" id="goodIdOfEachValue_{{$key}}" value="{{$data['good_id']}}">
                            <tr>
                                <th class="align-middle" scope="row" style="min-width: 150px">{{$data['good_manageId']}}</th>
                                <td class="align-middle" style="min-width: 150px">{{$data['good_title']}}</td>
                                @foreach ($locations as $k => $location)
                                    <td class="align-middle" style="min-width: 100px">
                                        @if (Auth::user()->user_role == 3)
                                            <input readonly destinationId="{{$data['destination_location'][$k]['destination_id']}}"  value="{{$data['destination_location'][$k]['quantity']}}" id="dest_val_m_{{$key}}_{{$k}}" class="form-control text-center" type="text">
                                        @else
                                            @if ($date[4] == "完了")
                                                <div destinationId="{{$data['destination_location'][$k]['destination_id']}}" k="{{$key}}" id="dest_val_m_{{$key}}_{{$k}}" class="updateOrderManager text-center">{{$data['destination_location'][$k]['quantity']}}</div>
                                            @else   
                                                <input destinationId="{{$data['destination_location'][$k]['destination_id']}}" k="{{$key}}" value="{{$data['destination_location'][$k]['quantity']}}" id="dest_val_m_{{$key}}_{{$k}}" class="updateOrderManager form-control text-center" type="number">
                                            @endif
                                        @endif
                                    </td>
                                @endforeach
                                <td class="align-middle" id="order_sum_m{{$key}}" style="min-width: 150px">{{$data['all_quantity']}}</td>
                                <td class="align-middle" id="goodInventory_{{$key}}" style="min-width: 150px">{{$data['good_inventory']}}</td>
                                <td class="align-middle" id="remain_inventory_m{{$key}}" style="min-width: 150px">{{$data['remain_quantity']}}</td>
                            </tr>
                        @endforeach
                </tbody>
            </table>
        </div>
        <div class="row mt-3">
            <div class="col-lg-9 col-sm-7"></div>
            @if (Auth::user()->user_role != 3)
                <div class="col-lg-3 col-sm-5">
                    <button type="button" id="updateOrdersButton2" class="updateOrdersButtonClass btn btn-warning float-end w-75">更新する</button>
                </div>
            @endif
        </div>
    </div>

    <div class="modal fade" id="confirmInputedData" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
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
                    <button type="button" class="btn" data-bs-dismiss="modal">取り消す</button>
                    <button type="button" class="btn btn-primary text-white">確認</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
