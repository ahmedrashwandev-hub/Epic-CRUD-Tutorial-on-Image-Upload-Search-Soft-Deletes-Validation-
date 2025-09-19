@extends('layouts.layout')
@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                Add New Category
            </div>
            <div class="card-body">
                <form action="{{ isset($category) ? route('product.updateCategory', $category->id) : route('product.storeCategory') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method("PUT")
                    <div class="mb-3">
                        <label class="form-label">Category Name *</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $category->name ?? '')}}">
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status *</label>
                        <select name="status" class="form-select" required>
                            <option value="active" {{ old('status', $category->status ?? '') == 'active' ? 'selected' : ''}}>Active</option>
                            <option value="in-active" {{ old('status', $category->status ?? '') == 'in-active' ? 'selected' : ''}}>InActive</option>
                        </select>
                        @error('status')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                        <button type="submit" class="btn btn-primary">
                            {{ isset($category) ? 'Update' : 'Save' }}
                        </button>
                    <a href="{{ route('product.index') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div><br>
    <div class="container">
        <div class="card">
            <div class="card-header">
                The Available Categories
            </div>
            @if (Session::has('success'))
                <span class="alert alert-success text-center">{{ Session::get('success') }}</span>
            @endif
            @if(Session::has('danger'))
                <span class="alert alert-danger text-center">{{ Session::get('danger') }}</span>
            @endif
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Category Name</th>
                            <th scope="col">Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($categories) > 0)
                            @foreach ($categories as $category)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $category->name }}</td>
                                <td>{{ $category->status }}</td>
                                {{-- <td><a href=" {{ route('product.show', $category->id) }} " class="btn btn-success btn-sm">Show</a></td> --}}
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href=" {{ route('product.editCategory', $category->id) }} " class="btn btn-primary btn-sm">Edit</a>







                                        <form action="{{ route('product.destroyCategory',$category->id) }}" method="post" style="display:inline">
                                            @csrf 
                                            @method('DELETE')
                                            <button onclick="return confirm('Are You Sure?')" class="btn btn-sm btn-danger">Delete Permanently</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="7" class="text-center">
                                    No Data Found!
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                {{-- {{ $categories->links() }} --}}
            </div>
        </div>
    </div>
@endsection