@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Редактирование отдела</h2>
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

    <form action="{{ route('departments.update', $department->id) }}" method="POST" enctype="multipart/form-data">
        @csrf

        @method('PUT')
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <label>
                        Название:
                        <input type="text" name="name" class="form-control" placeholder="Название"
                               value="{{ $department->name }}">
                    </label>
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <label>
                        Описание:
                        <textarea type="text" name="description" class="form-control" placeholder="Описание">{{ $department->description }}
                        </textarea>
                    </label>
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <label>
                        Лого:
                        <img src="{{ asset('/storage/app/logo/' . $department->logo) }}" title="logo" alt="" width="100"
                             height="100">
                        <input type="file" name="logo" class="form-control">
                    </label>
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">
                Пользователи:
                @foreach($users as $user)
                    <div class="form-check">
                        <label class="form-check-label">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="users[]"
                                value="{{ $user->id }}"
                                {{ in_array($user->id, $usersInDepartament) ? 'checked' : '' }}
                            >
                            {{ $user->name }} ({{ $user->email }})
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
