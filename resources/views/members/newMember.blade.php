@extends('layouts.app')

@section('content')
<div class="container-full mx-5">
    <div class="row justify-content-center">
        <div class="col-12 mb-3">
            <div class="row">
                <div class="col-3"></div>
                <div class="col-4">
                    <div class=" float-end my-4"><h3>会員情報編集</h3></div>
                </div>
                <div class="col-5"></div>
            </div>
        </div>
        <div class="col-12 mb-3">
            <div class="toast align-items-center text-white bg-success border-0" id="memberToast" style="position: absolute; top: 100px; right: 20px">
                <div class="d-flex">
                    <div class="toast-body" id="toastValueContent">
                        正しく更新されました！
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-3 col-sm-2"></div>
                <div class="col-xl-6 col-sm-8">
                    <form method="POST" id="userPersonalInForForm" action="{{ url('/members') }}">
                        @csrf
                        <div class="modal-body">
                            <input hidden type="text" id="memberIdInput" name="memberIdInput" value="{{Auth::id()}}">
                            <div class="mb-3 row">
                                <label for="exampleFormControlInput1" class="col-sm-4 col-form-label">法人名</label>
                                <div class="col-sm-8">
                                    <input class="form-control" value="{{auth()->user()->company_name}}" id="company_name" name="company_name" type="text" placeholder="法人名">
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
                                    <input class="form-control" value="{{auth()->user()->name}}" id="name" name="name" type="text" placeholder="担当者氏名" aria-label="default input example">
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
                                    <input class="form-control" value={{auth()->user()->furigana_name}} id="furigana_name" name="furigana_name" type="text" placeholder="担当者ふりがな">
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
                                    <input type="email" id="email" value="{{auth()->user()->email}}" name="email" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com">
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
                                    <input class="form-control" value="{{auth()->user()->phone_number}}" id="phone_number" name="phone_number" type="text" placeholder="1234567890" aria-label="default input example">
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
                                            value="{{substr(auth()->user()->post_code, 0, 3)}}" 
                                            name="post_code_prefix"
                                            maxlength="3"
                                            type="text" 
                                            placeholder="100"
                                        >
                                    </div>
                                    -
                                    <div class="w-50">
                                        <input 
                                        class="form-control" 
                                        value="{{substr(auth()->user()->post_code, -4)}}" 
                                        id="post_code_suffix" 
                                        name="post_code_suffix" 
                                        maxlength="4"
                                        type="text" 
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
                                    <input class="form-control" value="{{auth()->user()->location}}" id="location" name="location" type="text" placeholder="shikoku" aria-label="default input example">
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
                                    <input class="form-control" value="{{auth()->user()->street_adress}}" id="street_adress" name="street_adress" type="text" placeholder="112" aria-label="default input example">
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
                                    <input class="form-control" value="{{auth()->user()->building_name}}" id="building_name" name="building_name" type="text" placeholder="123ビル名" aria-label="default input example">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            {{-- <button type="button" id="cancelButton" class="btn btn-secondary mx-2" data-bs-dismiss="modal">取り消す</button> --}}
                            <button type="button" id="userPersonalInforEditButton" class="btn btn-primary">確認</button>
                        </div>
                    </form>
                </div>
                <div class="col-xl-3 col-sm-2"></div>
            </div>
        </div>
    </div>
</div>
@endsection
