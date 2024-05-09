@extends('layouts.app')

@section('content')
<div class="container-full mx-5">
    <div class="row justify-content-center">
        <div class="col-12 mb-1">
            <div class="row">
                <div class="col-12 border-bottom">
                    <div class="row">
                        <div class="col-5"></div>
                        <div class="col-3 float-start"><h3 class="tab-title">会員情報</h3></div>
                        <div class="col-4">
                            <div class="toast align-items-center border-0 d-float float-end m-0 p-0 " id="toastmember" role="alert" aria-live="assertive" aria-atomic="true">
                                <div class="d-flex">
                                    <div class="toast-body" id="toastValue"></div>
                                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 mt-3">
                    <div class="row">
                        <div class="col-10">
                            <div class="mb-3 row">
                                <div class="col-xl-6 col-sm-8">
                                    <div class="row">
                                        {{-- <label for="exampleFormControlInput1" class="col-sm-4 col-form-label">絞り込み</label> --}}
                                        <div class="col-sm-8">
                                            <input class="form-control w-100" id="search_field" name="filter" type="text" placeholder="絞り込み">
                                        </div>
                                        <div class="col-sm-4">
                                            <a href="/members" type="button" id="searchButton" class="btn btn-outline-primary w-lg-50 w-sm-100">検索</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="float-end">
                                <button type="button" id="newMember" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#staticBackdrop">新規登録</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row table-responsive">
        <div style="height: 650px">
            <table class="table table-hover text-center table-responsive-width" id="memberTable">
                <thead class="table-dark" style="position: sticky; top: 0;">
                    <tr>
                        {{-- <th class="py-3" scope="col">#</th> --}}
                        <th class="py-3" scope="col">クライアントID</th>
                        <th class="py-3" scope="col">クライアント名（法人名）</th>
                        <th class="py-3" scope="col">担当者名</th>
                        <th class="py-3" scope="col">連絡先（メールアドレス）</th>
                        <th class="py-3" scope="col">編集</th>
                        <th class="py-3" scope="col">削除</th>
                    </tr>
                </thead>
                <tbody id="memberTableBody">
                    @foreach ($members as $member)
                        <tr>
                            <td class="align-middle" hidden id="furigana_name_{{$member->id}}">{{$member->furigana_name}}</td>
                            <td class="align-middle" hidden id="password_{{$member->id}}">{{$member->password}}</td>
                            {{-- <td class="align-middle" hidden id="post_code_prefix_{{$member->id}}">{{$member->post_code_prefix}}</td> --}}
                            <td class="align-middle" hidden id="post_code_{{$member->id}}">{{$member->post_code}}</td>
                            <td class="align-middle" hidden id="location_{{$member->id}}">{{$member->location}}</td>
                            <td class="align-middle" hidden id="street_adress_{{$member->id}}">{{$member->street_adress}}</td>
                            <td class="align-middle" hidden id="building_name_{{$member->id}}">{{$member->building_name}}</td>

                            <th class="align-middle" id="_{{$member->id}}" scope="row">{{$member->id}}</th>
                            <td class="align-middle" id="company_name_{{$member->id}}">{{$member->company_name}}</td>
                            <td class="align-middle" id="name_{{$member->id}}">{{$member->name}}</td>
                            <td class="align-middle">
                                <div>
                                    <p class="m-0 p-0" id="email_{{$member->id}}">{{$member->email}}</p>
                                    <p class="m-0 p-0" id="phone_number_{{$member->id}}">{{$member->phone_number}}</p>
                                </div>
                            </td>
                            <td class="align-middle"><button type="button" memberId="{{$member->id}}" post_code="{{$member->post_code}}" member_role="{{$member->user_role}}" class="btn btn-outline-primary editButton" data-bs-toggle="modal" data-bs-target="#staticBackdrop">編集</button></td>
                            <td class="align-middle"><button type="button" memberId="{{$member->id}}" class="btn btn-outline-danger deleteButton" data-bs-toggle="modal" data-bs-target="#deleteMemberModal">削除</button></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="d-flex justify-content-center">
        {{ $members->links() }}
    </div>

    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" id="myForm" action="{{ url('/members') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">会員情報</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3 row">
                            <label for="exampleFormControlInput1" class="col-sm-4 col-form-label">法人名</label>
                            <div class="col-sm-8">
                                <input class="form-control" value="" id="company_name" name="company_name" type="text" placeholder="法人名" aria-label="default input example">
                            </div>
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-4"></div>
                                    <div class="col-8">
                                        <small class="text-danger" id="company_name_Error"></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="exampleFormControlInput1" class="col-sm-4 col-form-label">担当者氏名</label>
                            <div class="col-sm-8">
                                <input class="form-control" value="" id="name" name="name" type="text" placeholder="担当者氏名" aria-label="default input example">
                            </div>
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-4"></div>
                                    <div class="col-8">
                                        <small class="text-danger" id="name_Error"></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="exampleFormControlInput1" class="col-sm-4 col-form-label">担当者ふりがな</label>
                            <div class="col-sm-8">
                                <input class="form-control" value="" id="furigana_name" name="furigana_name" type="text" placeholder="担当者ふりがな" aria-label="default input example">
                            </div>
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-4"></div>
                                    <div class="col-8">
                                        <small class="text-danger" id="furigana_name_Error"></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="exampleFormControlInput1" class="col-sm-4 col-form-label">メールアドレス</label>
                            <div class="col-sm-8">
                                <input type="email" id="email" name="email" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com">
                            </div>
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-4"></div>
                                    <div class="col-8">
                                        <small class="text-danger" id="email_Error"></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="inputPassword" class="col-sm-4 col-form-label">パスワード</label>
                            <div class="col-sm-8">
                                <input type="password" id="password" value="" name="password" class="form-control" id="inputPassword">
                                <input type="checkbox" id="showPassword" onclick="showPass()"><small>パスワード表示</small>
                            </div>
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-4"></div>
                                    <div class="col-8">
                                        <small class="text-danger" id="password_Error"></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="exampleFormControlInput1" class="col-sm-4 col-form-label">電話番号</label>
                            <div class="col-sm-8">
                                <input class="form-control" value="" id="phone_number" name="phone_number" type="text" placeholder="1234567890" aria-label="default input example">
                            </div>
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-4"></div>
                                    <div class="col-8">
                                        <small class="text-danger" id="phone_number_Error"></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="exampleFormControlInput1" class="col-sm-4 col-form-label">郵便番号</label>
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
                                        type="text" 
                                        maxlength="4"
                                        placeholder="0005"
                                        onKeyUp="AjaxZip3.zip2addr('post_code_prefix','post_code_suffix','location','location');"
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
                            <label for="exampleFormControlInput1" class="col-sm-4 col-form-label">住所</label>
                            <div class="col-sm-8">
                                <input class="form-control" value="" id="location" name="location" type="text" placeholder="" aria-label="default input example">
                            </div>
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-4"></div>
                                    <div class="col-8">
                                        <small class="text-danger" id="location_Error"></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="street_adress" class="col-sm-4 col-form-label">番地</label>
                            <div class="col-sm-8">
                                <input class="form-control" value="" id="street_adress" name="street_adress" type="text" placeholder="112" aria-label="default input example">
                            </div>
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-4"></div>
                                    <div class="col-8">
                                        <small class="text-danger" id="street_adress_Error"></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="exampleFormControlInput1" class="col-sm-4 col-form-label">ビル名</label>
                            <div class="col-sm-8">
                                <input class="form-control" id="building_name" name="building_name" type="text" placeholder="123ビル名" aria-label="default input example">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="selecetPermission" class="col-sm-4 col-form-label">役割</label>
                            <div class="col-sm-8">
                                <select name="selecetPermission" id="selecetPermission" class="form-control">
                                    <option value="1">管理者</option>
                                    <option value="2">倉庫の人</option>
                                    <option value="3" selected>会員</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="cancelButton" class="btn btn-secondary" data-bs-dismiss="modal">取り消す</button>
                        <button type="button" id="createAndEditButton" class="btn btn-primary">確認</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteMemberModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
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
                    <button type="button" id="deleteCancelButton" class="btn btn-primary text-white" data-bs-dismiss="modal">取り消す</button>
                    <button type="button" id="deleteConfirm" class="btn btn-danger text-white">確認</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
