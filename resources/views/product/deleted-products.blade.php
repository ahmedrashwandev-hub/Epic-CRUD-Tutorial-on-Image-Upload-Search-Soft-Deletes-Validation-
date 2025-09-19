@extends('layouts.layout')
@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col">
                        <h2>Product List<h2>
                    </div>
                    <div class="col">
                        <div class="row">
                            <div class="col-md-6">
                                <form class="d-flex" role="search" action="{{ route('product.trashed') }}" method="GET">
                                    @csrf
                                    <input class="form-control me-2" type="search" name="search" placeholder="Search" aria-label="Search"/>
                                    <button class="btn btn-outline-success" type="submit">Search</button>
                                </form>
                            </div>
                        <div class="col-md-6">
                                <a href="{{ route('product.index')}}" class="float-end btn btn-warning">View All Products</a>
                        </div>
                    </div>
                </div>
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
                            <th scope="col" class="text-nowrap">Product Name</th>
                            <th scope="col">Category</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Price</th>
                            <th scope="col">Status</th>
                            <th scope="col">Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($products) > 0)
                            @foreach ($products as $product)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->category->name }}</td>
                                <td>{{ $product->quantity }}</td>
                                <td>{{ $product->price }}</td>
                                <td>{{ $product->status }}</td>
                                <td>{{ $product->description }}</td>
                                <td><a href=" {{ route('product.show', $product->id) }} " class="btn btn-success btn-sm">Show</a></td>
                                <td>
                                    <form action="{{ route('product.restore',$product->id) }}" method="post" style="display:inline">
                                        @csrf @method('PUT')
                                        <button onclick="return confirm('Are You Sure ?')" class="btn btn-sm btn-info">Restore</button>
                                    </form>                                    
                                <td>
                                    <form action="{{ route('product.delete',$product->id) }}" method="post" style="display:inline">
                                        @csrf @method('DELETE')
                                        <button onclick="return confirm('Are You Sure Want To Delete This Product Permanently ?')" class="btn btn-sm btn-danger text-nowrap">Delete Permanently</button>
                                    </form>
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
                {{ $products->links() }}
            </div>
        </div>
    </div>
@endsection