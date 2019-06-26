@extends('admin.templates.master')
@section('title', 'List article')
@section('content')
<div class="card-header">
    <nav class="breadcrumb">
        <a class="breadcrumb-item" href="#">Admin</a>
        <a class="breadcrumb-item" href="/admin/article/index">Article</a>
        <span class="breadcrumb-item active">Index</span>
    </nav>
</div>
<div class="card-body">
    <a class="btn btn-secondary mb-2" href="admin/article/add">Add</a>
    @if(request()->session()->has('message'))
        <div class="alert alert-primary" role="alert">
            {!!request()->session()->get('message')!!}
        </div>
    @endif
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>Id</th>
                <th>Name</th>
                <th>Description</th>
                <th>Category</th>
                <th>Is Active</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @if (count($articles) == 0)
                <tr>
                    <td class="text-center" colspan="7">
                        <div class="alert alert-danger" role="alert">
                            Don't have any items.
                        </div>
                    </td>
                </tr>
            @else
                @foreach($articles as $article)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$article->id}}</td>
                        <td>{{$article->name}}</td>
                        <td>{{$article->description}}</td>
                        <td>{{$article->category->name}}</td>
                        <td>
                            <button data-article-id="{{$article->id}}" data-article-status="{{$article->is_active}}"
                                type="button"
                                class="active-status btn-sm btn btn-{{$article->is_active == 0 ? 'dark' : 'danger'}}">
                            Active</button>
                        </td>
                        <td>
                            <a id="" class="btn btn-sm btn-secondary"
                               href="/admin/article/edit/{{$article->id}}">Edit</a>
                            <a onclick="confirm('Are you want to delete {{$article->name}}')"
                               id="" class="btn btn-sm btn-danger"
                               href="/admin/article/delete/{{$article->id}}?page={{request('page')}}">Delete</a>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                {{ $articles->links('vendor.pagination.custompagination') }}
            </div>
        </div>
    </div>
</div>
<script src="js/jquery-3.3.1.js"></script>
<script>
    $('document').ready(function () {
        $('.active-status').click(function () {
            let articleStatus = $(this).attr('data-article-status');
            let articleId = $(this).attr('data-article-id');
            let $button = $(this);
            $.ajax({
                url: 'admin/article/toggleStatus',
                type: 'POST',
                cache: false,
                data: {
                    articleId : articleId,
                    articleStatus : articleStatus,
                    _token: '{{ csrf_token() }}',
                },
                success: function(response){
                    if (response == 'success') {
                        if (articleStatus == 1) {
                            $button.removeClass('btn-danger').addClass('btn-dark');
                            $button.attr('data-article-status', 0);
                        } else {
                            $button.removeClass('btn-dark').addClass('btn-danger');
                            $button.attr('data-article-status', 1);
                        }
                    }
                },
                error: function (){
                    alert("have a error");
                }
            });
        });
    });
</script>
@endsection