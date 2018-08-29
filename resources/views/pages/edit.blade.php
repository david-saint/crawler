@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <form action="/pages/{{ $page->slug }}" method="POST">
                <div class="modal-body">
                    @method('PUT')
                    @csrf
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Link:</label>
                        <input type="url" name="link" class="form-control{{ $errors->has('link') ? ' is-invalid' : '' }}" id="recipient-name" value="{{ $page->link }}" required>
                        @if ($errors->has('link'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('link') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Title:</label>
                        <input type="text" name="title" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" id="recipient-name" value="{{ $page->title }}" required>
                        @if ($errors->has('title'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('title') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Our Link:</label>
                        <input type="text" name="slug" class="form-control{{ $errors->has('slug') ? ' is-invalid' : '' }}" id="recipient-name" value="{{ $page->slug }}" required>
                        @if ($errors->has('slug'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('slug') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Edit Link</button>
                </div>
            </form>
            <br>
            <p>Links: <a href="/{{ $page->slug }}">{{ url("/$page->slug") }}</a> OR <a href="/f/{{ $page->slug }}">{{ url("/f/$page->slug") }}</a></p>
        </div>
    </div>
</div>
@endsection