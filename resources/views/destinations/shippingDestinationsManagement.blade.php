@extends('layouts.app')

@section('content')
<div class="container-full mx-5">
    <div class="row justify-content-center">
        <div class="col-12 mb-4">
            <div class="row border-bottom">
                <div class="col-5"></div>
                <div class="col-3">
                    <div><h3>発送先一覧</h3></div>
                </div>
                <div class="col-4"></div>
            </div>
        </div>
        <div class="col-12">
            <div class="row mb-2">
                @if(auth()->user()->user_role != 3)
                    <div class="col-xl-2 col-sm-8">
                        <div class="row">
                            <div class="col-sm-12">
                                <label>クライアントで絞り込み</label>
                                <select class="form-select" id="selectUserFieldForDestinations" aria-label="Default select example">
                                    @foreach ($users as $key => $user)
                                        @if ($user->id == $selectedUser->id)
                                            <option selected userIds = "{{$user->id}}">{{$user->name}}</option>
                                        @else
                                            <option userIds = "{{$user->id}}">{{$user->name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <a hidden href="/destination/" id="selectUserLinkForDestinations"></a>
                            </div>
                        </div>
                    </div>
                @endif
                @if(auth()->user()->user_role != 2)
                    <div class="col-xl-10 col-sm-4 align-self-end"><button type="button" id="addDestinationButton" class="btn btn-warning float-end align-self-end mx-xl-2" data-bs-toggle="modal" data-bs-target="#newAndEditDestinationModal">発送先の新規登録</button></div>
                @endif
            </div>
            <div class="row mb-1">
                <div class="col-xl-2 col-sm-12">
                    <div class="row">
                        <div class="col-xl-12 col-sm-8">
                            <label>発送先名で絞り込む</label>
                            <div class="row my-2">
                                <div class="col-xl-12 col-sm-8">
                                    <input class="form-control" id="searchDestinationName" type="text" placeholder="発送先名を入力してください">
                                </div>
                                <div class="col-xl-12 col-sm-4 d-float justtify-content-end pe-0 me-0">
                                    <a href="/destination/{{$selectedUser->id}}/" id="searchDestinationByName" class="float-xl-end me-xl-3 mt-xl-1 btn btn-outline-primary">絞り込み</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-12 col-sm-4">
                            <label>表示件数を変更する</label>
                            <select class="form-select my-2" id="showRowNumber" aria-label="Default select example">
                                @if ($rowNumber == 10)
                                    <option selected value="10">10</option>
                                    <option value="20">20</option>
                                    <option value="30">30</option>
                                    <option value="40">40</option>
                                    <option value="50">50</option>
                                @elseif ($rowNumber == 20)
                                    <option value="10">10</option>
                                    <option selected value="20">20</option>
                                    <option value="30">30</option>
                                    <option value="40">40</option>
                                    <option value="50">50</option>
                                @elseif ($rowNumber == 30)
                                    <option value="10">10</option>
                                    <option value="20">20</option>
                                    <option selected value="30">30</option>
                                    <option value="50">40</option>
                                    <option value="50">50</option>
                                @elseif ($rowNumber == 40)
                                    <option value="10">10</option>
                                    <option value="20">20</option>
                                    <option value="30">30</option>
                                    <option selected value="40">40</option>
                                    <option value="50">50</option>
                                @elseif ($rowNumber == 50)
                                    <option value="10">10</option>
                                    <option value="20">20</option>
                                    <option value="30">30</option>
                                    <option value="40">40</option>
                                    <option selected value="50">50</option>
                                @endif
                            </select>
                        <a hidden href="/destination/saveRowNumber/10/" id="showRowNumberLink"></a>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-sm-0"></div>
                <div class="col-xl-8 col-sm-12">
                    @if ($datas)
                        @foreach ($datas as $data)
                            <div class="row p-2 border-top">
                                @if(auth()->user()->user_role != 2)
                                    <div class="col-md-6">
                                @else
                                    <div class="col-sm-10">
                                @endif
                                        <div class="row g-0 border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
                                            <div class="col-12 p-4 d-flex flex-column position-static">
                                                <strong class="d-inline-block mb-2 text-primary" id="destinationManageLabel_{{$data->id}}">{{$data->destinationName}}</strong>
                                                <p class="mb-0" id="destinationPostCode_{{$data->id}}">{{$data->destinationPostCode}}</p>
                                                <p class="mb-0" id="destinationDetailAdress_{{$data->id}}">
                                                    {{$data->destinationName ." ". $data->destinationLocation ." ". $data->destinationStreetAdress ." ". $data->destinationBuildingName}}
                                                </p>
                                                <p class="card-text mb-auto" id="destinationPhoneNumber_{{$data->id}}">{{$selectedUser->phone_number}}</p>
                                                <input type="text" hidden class="form-control" id="destinationName_{{$data->id}}" value="{{$data->destinationName}}">
                                                <input type="text" hidden class="form-control" id="destinationLocation_{{$data->id}}" value="{{$data->destinationLocation}}">
                                                <input type="text" hidden class="form-control" id="destinationStreetAdress_{{$data->id}}" value="{{$data->destinationStreetAdress}}">
                                                <input type="text" hidden class="form-control" id="destinationBuildingName_{{$data->id}}" value="{{$data->destinationBuildingName}}">
                                            </div>
                                        </div>
                                    </div>
                                @if(auth()->user()->user_role != 2)
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-6"><button type="button" class="btn btn-primary w-75 float-end editDestinationButton" indexNum={{$data->id}} memberId="{{$selectedUser->id}}" data-bs-toggle="modal" data-bs-target="#newAndEditDestinationModal">編集</button></div>
                                            <div class="col-6"><button type="button" class="btn btn-danger w-75 float-end deleteDestinationButton" indexNum={{$data->id}} memberId="{{$selectedUser->id}}" data-bs-toggle="modal" data-bs-target="#destinationDeleteConfirmModal">削除</button></div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach    
                    @endif
                </div>
            </div>
            <div class="row my-3">
                <div class="col-lg-5 col-sm-4"></div>
                <div class="col-lg-4 col-sm-6">
                    {{$datas->links()}}
                </div>
                <div class="col-lg-3 col-sm-2"></div>
            </div>
        </div>
    </div>
    @if(auth()->user()->user_role != 2)
        <div class="modal fade" id="newAndEditDestinationModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="newDestinationForm" method="POST" action="{{ url('/destination') }}">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">発送先登録 / 編集</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3 row">
                                <label for="clientId" class="col-sm-4 col-form-label">顧客ID</label>
                                <div class="col-sm-8">
                                    @if(auth::user()->user_role == 3)
                                        <input class="form-control" type="text" user_role="3" name="clientId" id="clientId" value="{{ auth::id() }}" readonly>  
                                    @else
                                    <input class="form-control" type="text" placeholder="顧客ID" name="clientId" id="clientId" value="">
                                    @endif
                                </div>
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-4"></div>
                                        <div class="col-8">
                                            <small class="text-danger" id="clientId_Error"></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="destinationName" class="col-sm-4 col-form-label">発送先名</label>
                                <div class="col-sm-8">
                                    <input class="form-control" id="destinationName" name="destinationName" type="text" placeholder="発送先名" aria-label="default input example">
                                </div>
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-4"></div>
                                        <div class="col-8">
                                            <small class="text-danger" id="destinationName_Error"></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="post_code_prefix" class="col-sm-4 col-form-label">郵便番号</label>
                                <div class="col-sm-8 d-flex justify-content-between">
                                    <div class="w-25">
                                        <input 
                                            class="form-control" 
                                            id="post_code_prefix" 
                                            maxlength="3"
                                            name="post_code_prefix" 
                                            type="text" 
                                            placeholder="100">
                                    </div>
                                    -
                                    <div class="w-50">
                                        <input 
                                            class="form-control" 
                                            id="post_code_suffix" 
                                            name="post_code_suffix"
                                            maxlength="4"
                                            type="text" 
                                            placeholder="0005"
                                            onKeyUp="AjaxZip3.zip2addr('post_code_prefix','post_code_suffix','locationName','locationName');"
                                        >
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-4"></div>
                                        <div class="col-8">
                                            <small class="text-danger" id="post_code_suffix_Error"></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="locationName" class="col-sm-4 col-form-label">住所</label>
                                <div class="col-sm-8">
                                    <input class="form-control" type="text" id="locationName" name="locationName" placeholder="shikoku" aria-label="default input example">
                                </div>
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-4"></div>
                                        <div class="col-8">
                                            <small class="text-danger" id="locationName_Error"></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="streetAddressName" class="col-sm-4 col-form-label">番地</label>
                                <div class="col-sm-8">
                                    <input class="form-control" type="text" id="streetAddressName" name="streetAddressName" placeholder="112" aria-label="default input example">
                                </div>
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-4"></div>
                                        <div class="col-8">
                                            <small class="text-danger" id="streetAddressName_Error"></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="destinationBuildingName" class="col-sm-4 col-form-label">ビル名</label>
                                <div class="col-sm-8">
                                    <input class="form-control" id="destinationBuildingName" name="destinationBuildingName" type="text" placeholder="123ビル名" aria-label="default input example">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="cancelAddDestinationButton" class="btn btn-secondary" data-bs-dismiss="modal">取り消す</button>
                            <button type="button" id="addAndEditDestinationConfirmButton" class="btn btn-primary">確認</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="destinationDeleteConfirmModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
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
                        <button type="button" id="destinationDeleteButtonCancel" class="btn" data-bs-dismiss="modal">取り消す</button>
                        <button type="button" id="destinationDeleteButtonConfirm" class="btn btn-primary text-white">確認</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
