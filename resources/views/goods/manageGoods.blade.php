@extends('layouts.app')

@section('content')
<div class="container-full mx-5">
    <div class="row justify-content-center">
        <div class="col-12 mb-2">
            <div class="row mb-2 border-bottom">
                <div class="col-5"></div>
                <div class="col-4">
                    <div class=""><h3>商品登録 / 編集</h3></div>
                </div>
                <div class="col-3"></div>
            </div>
            <div class="row">
                <div class="col-xl-3 col-sm-0">
                </div>
                <div class="col-xl-6 col-sm-12 responsive-font-size">
                    <div class="mb-2 row">
                        <label for="exampleFormControlInput1" class="col-sm-4 col-form-label">会員を選択</label>
                        <div class="col-sm-8">
                            <select class="form-select" id="selectUserField" aria-label="Default select example">
                                @foreach ($allUserInfor as $user)
                                    @if ($user->id == $userId)
                                        <option selected userIds = "{{$user->id}}">{{$user->company_name}}</option>
                                    @else
                                        <option userIds = "{{$user->id}}">{{$user->company_name}}</option>
                                    @endif
                                @endforeach
                            </select>
                            <a hidden href="/goods/3" id="selectUserLink"></a>
                        </div>
                    </div>
                    <div class="mb-2 row">
                        <label for="exampleFormControlInput1" class="col-4 col-form-label">IDまたはタイトルで絞り込む</label>
                        <div class="col-6">
                            <input class="form-control" id="searchGoodsField" type="text" placeholder="">
                        </div>
                        <div class="col-2">
                            <a href="/goods/{{$userId}}" class="form-control btn btn-outline-primary" id="searchGoodsForUsersButton">検索</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-xl-1 mb-sm-1">
                <div class="col-9">
                    <div class="float-end">
                        <a href="/goods/download/{{$userId}}" class="btn btn-responsive">csvをダウンロード</a>
                        <button class="btn btn-outline-primary btn-responsive" userId={{$userId}} id="tmpGoodsUploadButton">CSVで登録・更新</button>
                        <input hidden type="file" id="goodsFormFileUpload" class="form-control btn btn-outline-primary btn-responsive">
                    </div>
                </div>
            </div>
        </div>
        <div class="row table-responsive" style="max-height: 500px;">
            <table class="table table-hover text-center table-responsive-width">
                <thead class="table-dark">
                    <tr>
                        <th class="align-middle" scope="col">管理ID</th>
                        <th class="align-middle" scope="col">本のタイトル</th>
                        <th class="align-middle" scope="col">在庫数</th>
                        <th class="align-middle" scope="col">現在の注文数</th>
                        <th class="align-middle" scope="col">発送可能在庫数</th>
                        <th class="align-middle" scope="col">削除</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($goods as $key => $good)
                        <tr>
                            <th class="align-middle p-1" scope="row">{{$good->manageGoodsId}}</th>
                            <td class="align-middle p-1">{{$good->goodsTitle}}</td>
                            <td class="align-middle p-1">{{$good->goodsInventory}}</td>
                            @if (!array_key_exists($good->id, $goodsQuantities))
                                <td class="align-middle p-1">{{0}}</td>
                                <td class="align-middle p-1">{{$good->goodsInventory}}</td>
                            @else
                                <td class="align-middle p-1">{{$goodsQuantities[$good->id]}}</td>
                                <td class="align-middle p-1">{{$good->goodsInventory - $goodsQuantities[$good->id]}}</td>
                            @endif
                            <td class="align-middle p-1"><button type="button" userIdValue = "{{$userId}}" goodsIdValue="{{$good->id}}" class="btn btn-outline-danger btn-responsive p-1 deleteGoodsForUserButton" data-bs-toggle="modal" data-bs-target="#deleteGoodsModal">削除</button></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="row m-0 p-0">
            <div class="col-12">
                <div class="float-end"><button type="button" class="btn text-primary btn-responsive" data-bs-toggle="modal" data-bs-target="#goodsStoreModal">行を追加する</button></div>
            </div>
            <div class="col-12">
                <div class="row">
                    <div class="col-4"></div>
                    <div class="col-4"><button type="button" class="btn btn-primary w-100 btn-responsive">更新する</button></div>
                    <div class="col-4"></div>
                </div>
            </div>
        </div>
        <div class="col-12 mt-3 d-flex justify-content-center">
            {{ $goods->links() }}
        </div>
    </div>

    <div class="modal fade" id="goodsStoreModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" id="goodsForm" action="{{ url('/members') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">商品登録</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input class="form-control" hidden value="{{$userId}}" type="text" id="userId" name="userId">
                        <div class="mb-3 row">
                            <label for="exampleFormControlInput1" class="col-sm-4 col-form-label">管理ID</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="text" id="manageId" name="manageId" placeholder="管理ID" aria-label="default input example">
                            </div>
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-4"></div>
                                    <div class="col-8">
                                        <small class="text-danger" id="manageId_Error"></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="exampleFormControlInput1" class="col-sm-4 col-form-label">本のタイトル</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="text" id="goodsTitle" name="goodsTitle" placeholder="本のタイトル" aria-label="default input example">
                            </div>
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-4"></div>
                                    <div class="col-8">
                                        <small class="text-danger" id="goodsTitle_Error"></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="exampleFormControlInput1" class="col-sm-4 col-form-label">在庫数</label>
                            <div class="col-sm-8">
                                <input class="form-control" value="0" id="goodsInventory" name="goodsInventory" type="number" placeholder="在庫数" aria-label="default input example">
                            </div>
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-4"></div>
                                    <div class="col-8">
                                        <small class="text-danger" id="goodsInventory_Error"></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="goodsStoreModalCancelButton" class="btn btn-secondary" data-bs-dismiss="modal">取り消す</button>
                        <button type="button" id="storeGoodsButton" class="btn btn-primary">登録</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteGoodsModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-danger" id="staticBackdropLabel">消す</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    これは削除してもよろしいでしょうか？
                </div>
                <div class="modal-footer">
                    <button type="button" id="deleteGoodsForUserButtonCancel" class="btn btn-primary text-white" data-bs-dismiss="modal">取り消す</button>
                    <button type="button" id="deleteGoodsForUserButtonConfirm" class="btn btn-danger text-white">確認</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
