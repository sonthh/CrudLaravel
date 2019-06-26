@extends('admin.templates.master')
@section('title', 'Add a article')
@section('content')
<div class="card-header">
    <nav class="breadcrumb">
        <a class="breadcrumb-item" href="#">Admin</a>
        <a class="breadcrumb-item" href="/admin/article/index">Article</a>
        <span class="breadcrumb-item active">Edit</span>
    </nav>
</div>
<div class="card-body">
    <form action="{{url('admin/article/add')}}" method="post">
        @csrf
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
            </ul>
        </div>
        @endif
        <div class="form-group">
            <label for="category">Category</label>
            <select name="category_id" id="category" class="form-control">
                @foreach($categories as $category)
                    <option value="{{$category->id}}">{{$category->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror"
                   value="" name="name" id="name" placeholder="" />
        </div>
        @error('name')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror

        <div class="form-group">
            <label for="description">Description</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" value=""
                   name="description" id="description" placeholder="" />
        </div>
        @error('description')
        <div class="alert alert-danger">{{ $message }}</div>
        @enderror

        <div class="form-group">
            <label for="content">Content</label>
            <input type="text" class="form-control" value="" name="content" id="content" placeholder="" />
        </div>
        <div class="form-group">
            <label for="active">Active</label>
            <select name="is_active" id="active" class="form-control">
                <option value="1">Active</option>
                <option value="0">Deactive</option>
            </select>
        </div>
        <button type="submit" class="btn btn-secondary">Add</button>
    </form>
</div>
@endsection