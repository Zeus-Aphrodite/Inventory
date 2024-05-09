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
                            <div class="toast align-items-center border-0 d-float float-end m-0 p-0 " id="toastgood" role="alert" aria-live="assertive" aria-atomic="true">
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
                                            <input class="form-control w-100" id="search_users_field" name="filter" type="text" placeholder="絞り込み">
                                        </div>
                                        <div class="col-sm-4">
                                            <a href="/goods" type="button" id="searchUsersButton" class="btn btn-outline-primary w-lg-50 w-sm-100">検索</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row table-responsive">
        <div style="height: 650px">
            <table class="table table-hover text-center table-responsive-width" id="goodTable">
                <thead class="table-dark" style="position: sticky; top: 0;">
                    <tr>
                        {{-- <th class="py-3" scope="col">#</th> --}}
                        <th class="py-3" scope="col">クライアントID</th>
                        <th class="py-3" scope="col">クライアント名（法人名）</th>
                        <th class="py-3" scope="col">担当者名</th>
                        <th class="py-3" scope="col">詳細</th>
                    </tr>
                </thead>
                <tbody id="goodTableBody">
                    @foreach ($members as $member)
                        <tr>
                            <th class="align-middle" scope="row">{{$member->id}}</th>
                            <td class="align-middle">{{$member->company_name}}</td>
                            <td class="align-middle">{{$member->name}}</td>
                            <td class="align-middle"><a href="/goods/{{$member->id}}">商品情報編集</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="d-flex justify-content-center">
        {{ $members->links() }}
    </div>
@endsection
