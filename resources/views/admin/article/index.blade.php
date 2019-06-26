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
                    @php
                        $classActive = (request()->session()->has('articleId')
                                        && request()->session()->get('articleId') == $article->id)
                                        ? 'table-success' : 'active';
                    @endphp
                    <tr class="{{$classActive}}">
                        <td>{{$loop->iteration}}</td>
                        <td>{{$article->id}}</td>
                        <td>{{$article->name}}</td>
                        <td>{{$article->description}}</td>
                        <td>{{$article->category->name}}</td>
                        <td>
                            <button
                                data-article-id="{{$article->id}}" data-article-status="{{$article->is_active}}"
                                type="button" data-toggle="tooltip"
                                title="Click to {{$article->is_active == 0 ? 'activate item.' : 'deactivate item.'}}"
                                class="active-status btn-sm btn btn-{{$article->is_active == 0 ? 'dark' : 'danger'}}">
                                Active
                            </button>
                        </td>
                        <td>
                            <a id="" class="btn btn-sm btn-secondary"
                               href="/admin/article/edit/{{$article->id}}">Edit</a>
                            <a  data-toggle="modal" data-target="#deleteModal" data-whatever="{{$article->name}}"
                                id="" class="btn btn-sm btn-danger"
                                data-href="/admin/article/delete/{{$article->id}}?page={{request('page')}}">
                                Delete
                            </a>
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
    <div>
        <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog"
             aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Do you want to delete this item?</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <a class="btn btn-primary btn-ok">Delete</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('page-script')
<script>
    $('document').ready(function () {
        $('#deleteModal').on('show.bs.modal', function (event) {
            let $button = $(event.relatedTarget);
            let articleName = $button.data('whatever');
            let $modal = $(this);
            $modal.find('.modal-body').text(articleName);
            $modal.modal('hide');
            $modal.find('.btn-ok').attr('href', $(event.relatedTarget).data('href'));
        });

        $('.active-status').tooltip();

        $('.active-status').click(function () {

            // $('#deleteModal').modal();

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
                        let title = "Click to ";
                        title += articleStatus == 1 ? 'activate item.' : 'deactivate item.';
                        // $button.attr('title', title);
                        $button.tooltip('hide')
                            .attr('data-original-title', title)
                            .tooltip('show');
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