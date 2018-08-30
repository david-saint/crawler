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
            
            <br>
            
            <div class="card">
                <div class="card-header">Ads On this Link <a style="float: right; cursor: pointer;" data-toggle="modal" data-target="#addAdtoLink"><i class="material-icons">add</i></a></div>
                
                <div class="card-body">
                    <ul class="list-group">
                        @foreach($page->ads as $ad)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $ad->name }}
                            <div>
                                <a href="/ads/{{ $ad->id }}/edit"><span class="badge badge-primary badge-pill"><i class="material-icons">edit</i></span></a>
                                <form action="/ads/{{ $ad->id }}" method="POST" id="delete-{{ $ad->id }}-ad">
                                    @method('DELETE')
                                    @csrf
                                </form>
                                <a href="" onclick="event.preventDefault();
                                                         document.getElementById('delete-{{ $ad->id }}-ad').submit();">
                                    <span class="badge badge-danger badge-pill"><i class="material-icons">close</i></span>
                                </a>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                    <a href="{{ route('ads.index') }}">View More...</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addAdtoLink" tabindex="-1" role="dialog" aria-labelledby="addAdtl" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addAdtl">New Ad</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="/pages/{{ $page->slug }}/ads" method="POST">
                <div class="modal-body">
                    @csrf
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Name:</label>
                        <input type="text" name="ad" class="form-control{{ $errors->has('ad_id') ? ' is-invalid' : '' }}" id="ad_name" required>
                        <input type="hidden" name="ad_id" id="adId">
                        <div id="adList"></div>
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

<script>
    // Wait for body to complete loading
    document.addEventListener('DOMContentLoaded', function () {
        // get the input element
        var autocompleteInput = document.getElementById('ad_name');
        // add a keyup event listener to the element
        autocompleteInput.addEventListener('keyup', function () {
            // if the value is not null
            if (this.value !== '') {
                let query = this.value;
                axios
                    .post('/autocomplete', { query: query })
                    .then(function (response) {
                        document.getElementById('adList').innerHTML = response.data;
                    })
            }
        });
    });

    function hasClass(elem, className) {
        return elem.className.split(' ').indexOf(className) > -1;
    }

    document.addEventListener('click', function (e) {
        if (hasClass(e.target, 'adid')) {
            var val = e.target.getAttribute('data-adid');
            var adname = document.getElementById('ad_name');
            var adid = document.getElementById('adId');

            adname.value = e.target.innerHTML;
            adid.value = val;
            document.getElementById('adList').innerHTML = "";
        }
    }, false);
</script>
@endsection