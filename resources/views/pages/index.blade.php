@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Links <a style="float: right; cursor: pointer;" data-toggle="modal" data-target="#addLink"><i class="material-icons">add</i></a></div>

                <div class="card-body">
                    <ul class="list-group">
                        @foreach($pages as $page)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ ($page->title) ? $page->title : $page->link }}
                        <div>
                            <a href="/pages/{{ $page->slug }}/edit"><span class="badge badge-primary badge-pill"><i class="material-icons">edit</i></span></a>
                            <form action="/pages/{{ $page->slug }}" method="POST" id="delete-{{ $page->id }}-form">
                            	@method('DELETE')
                            	@csrf
                            </form>
                            <a href="" onclick="event.preventDefault();
                                                     document.getElementById('delete-{{ $page->id }}-form').submit();">
                            	<span class="badge badge-danger badge-pill"><i class="material-icons">close</i></span>
                            </a>
                        </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
			<br>
            {{ $pages->links() }}
        </div>
    </div>
</div>

<div class="modal fade" id="addLink" tabindex="-1" role="dialog" aria-labelledby="addLinkLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addLinkLabel">New Link</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('pages.store') }}" method="POST">
                <div class="modal-body">
                    @csrf
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Link:</label>
                        <input type="url" name="link" class="form-control{{ $errors->has('link') ? ' is-invalid' : '' }}" id="recipient-name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Link</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection