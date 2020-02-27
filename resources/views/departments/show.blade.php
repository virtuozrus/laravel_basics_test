@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Отображение деталей отдела</h2>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                Название: {{ $department->name }}
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                Описание: {{ $department->description }}
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                Лого:
                <img src="{{ asset('/storage/app/logo/' . $department->logo) }}" title="logo" alt="" width="200" height="200">
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                Пользователи:
                <ul class="list-group list-group-flush">
                    @foreach($usersInDepartment as $user)
                        <li class="list-group-item">{{ $user->name }} ({{ $user->email }})</li>
                    @endforeach
                </ul>
            </div>
        </div>

    </div>
@endsection
