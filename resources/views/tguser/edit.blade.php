@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <div class="card">
            <div class="card-header">{{ __('VipStatus update') }}</div>

            <div class="card-body">
                <form enctype="multipart/form-data" method="POST" action="/tguser/{{$tguser->id}}/update">
                    @csrf

                    <div class="row mb-3">
                        <label  class="col-md-4 col-form-label text-md-end">{{ __('VipStatus') }}</label>

                        <div class="col-md-6">
                            <input type="text" value="{{$tguser->isvip}}" class="form-control" name="title" required autofocus>
                        </div>
                    </div>

                    
                    <div class="row mb-0">
                        <div class="col-md-8 offset-md-4">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Вперёд') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection