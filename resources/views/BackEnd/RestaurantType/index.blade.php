@extends('BackEnd.master')
@section('title')
        Restaurant page 
@endsection
@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header text-right">
                    <a href="{{ url('/dashboard/restaurantType/create') }}">
                        <button type="button" class="btn btn-info">Add Restaurant Type</button>
                    </a>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Type Name</th>
                                <th scope="col">Photo</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($restaurantTypes as $restaurantType)
                            <tr>
                                <th scope="row">{{ $restaurantType->id }}</th>
                                <td>{{ $restaurantType->type }}</td>
                                <td>
                                    <img src="{{ asset('images/') }}/{{ $restaurantType->photo_path }}" class="img-top" alt="{{ $restaurantType->type }}"
                                        width="50px" height="50px" >
                                </td>
                                <td>
                                <div class="d-flex">
                                        <a href="{{ url('/dashboard/restaurantType/edit/'.$restaurantType->id) }}" class="btn btn-primary me-2">Edit</a>  &nbsp;   &nbsp;  &nbsp; 
                                        <form action="{{ route('delete_restaurant_type', ['id' => $restaurantType->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this restaurant type?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {!! $restaurantTypes->links('pagination::bootstrap-5') !!}
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
