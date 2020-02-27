@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Отображение деталей пользователя</h2>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                Имя: {{ $user->name }}
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                E-mail: {{ $user->email }}
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                Отделы:
                <ul class="list-group list-group-flush">
                    @foreach($departmentsOfUser as $user)
                        <li class="list-group-item">{{ $user->name }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endsection
