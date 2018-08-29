@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <form action="/ads/{{ $ad->id }}" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    @csrf
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Name:</label>
                        <input type="text" name="name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" value="{{ $ad->name }}" id="recipient-name" required>
                         @if ($errors->has('name'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="url" class="col-form-label">Link it redirects to</label>
                        <input type="url" name="url" class="form-control{{ $errors->has('url') ? ' is-invalid' : '' }}" id="url" value="{{ $ad->url }}" required>
                         @if ($errors->has('url'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('url') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="file" class="col-form-label">Image</label>
                        <input type="file" class="form-control{{ $errors->has('image') ? ' is-invalid' : '' }}" id="file" name="image" required>
                         @if ($errors->has('image'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('image') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Ad</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection