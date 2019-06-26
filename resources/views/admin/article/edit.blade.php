@extends('admin.templates.master')
@section('title', 'Edit ' . $article->name)
@section('content')
<div class="card-header">
    <nav class="breadcrumb">
        <a class="breadcrumb-item" href="#">Admin</a>
        <a class="breadcrumb-item" href="/admin/article/index">Article</a>
        <span class="breadcrumb-item active">Edit</span>
    </nav>
</div>
<div class="card-body">
    <form action="{{url('admin/article/edit/' . $article->id)}}" method="post">
        @csrf
        <div class="form-group">
            <label for="category">Category</label>
            <select name="category_id" id="category" class="form-control">
                @foreach($categories as $category)
                    <option {{$category->id == $article->category->id ? 'selected' : ''}} value="{{$category->id}}">{{$category->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="id">Id</label>
            <input type="text" class="form-control" readonly value="{{$article->id}}" name="id" id="id" placeholder="" />
        </div>
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" value="{{$article->name}}" name="name" id="name" placeholder="" />
        </div>
        <div class="form-group">
            <label for="slug">Slug</label>
            <input type="text" readonly class="form-control" value="{{$article->slug}}" name="slug" id="slug" placeholder="" />
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <input type="text" class="form-control" value="{{$article->description}}" name="description" id="description" placeholder="" />
        </div>
        <div class="form-group">
            <label for="content">Content</label>
            <input type="text" class="form-control" value="{{$article->content}}" name="content" id="content" placeholder="" />
        </div>
        <div class="form-group">
            <label for="active">Active</label>
            <select name="is_active" id="active" class="form-control">
                <option {{$article->is_active == 1 ? 'selected' : ''}} value="1">Active</option>
                <option {{$article->is_active == 0 ? 'selected' : ''}} value="0">Deactive</option>
            </select>
        </div>
        <button type="submit" class="btn btn-secondary">Edit</button>
    </form>
</div>
@endsection