@extends('BackEnd.master')
@section('title')
    Restaurant page
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    Restaurant Type {{ $restaurantType->id }}
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ url('/dashboard/restaurantType/' . $restaurantType->id . '/update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <label for="type" class="col-md-4 col-form-label text-md-end">{{ __('Type Name') }}</label>

                            <div class="col-md-6">
                                <input id="type" type="text" class="form-control @error('type') is-invalid @enderror" name="type" value="{{ old('type', $restaurantType->type) }}" required autocomplete="name" autofocus>

                                @error('type')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="photo_path" class="col-md-4 col-form-label text-md-end">{{ __('Photo Upload') }}</label>

                            <div class="col-md-6">
                                <input id="photo_path" type="file" class="form-control @error('photo_path') is-invalid @enderror" name="photo_path" accept="image/*">

                                @error('photo_path')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-info">
                                    {{ __('Save') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
