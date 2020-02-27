@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Создание нового пользователя</h2>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            Проверьте введенные данные:<br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('users.store') }}" method="POST">
        @csrf

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <label>
                        Имя:
                        <input type="text" name="name" class="form-control" placeholder="Имя">
                    </label>
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <label>
                        E-mail:
                        <input type="email" name="email" class="form-control" placeholder="E-mail">
                    </label>
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <label>
                        Пароль:
                        <input type="password" name="password" class="form-control" placeholder="Пароль">
                    </label>
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">
                Отделы:
                @foreach($departments as $department)
                    <div class="form-check">
                        <label class="form-check-label">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="departments[]"
                                value="{{ $department->id }}"
                            >
                            {{ $department->name }}
                        </label>
                    </div>
                @endforeach
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">
                <button type="submit" class="btn btn-primary">Подтвердить</button>
            </div>
        </div>
    </form>
@endsection
