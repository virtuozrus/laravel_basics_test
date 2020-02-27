@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Отделы</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('departments.create') }}">Создать новый отдел</a>
            </div>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <th>Название</th>
            <th>Описание</th>
            <th>Лого</th>
            <th>Пользователи</th>
            <th>Действие</th>
        </tr>

        @foreach ($departments as $department)
            <tr>
                <td>{{ $department->id }}</td>
                <td>{{ $department->name }}</td>
                <td>{{ $department->description }}</td>
                <td><img src="{{ asset('/storage/app/logo/' . $department->logo) }}" title="logo" alt="" width="100" height="100"></td>
                <td>
                    <ul>
                        @foreach($department->users->unique()->take(5) as $user)
                            <li>{{ $user->name }} ({{ $user->email }})</li>
                        @endforeach
                    </ul>
                </td>
                <td>
                    <form action="{{ route('departments.destroy', $department->id) }}" method="POST">
                        @csrf

                        <a class="btn btn-sm btn-info" href="{{ route('departments.show', $department->id) }}">Показать</a>
                        <a class="btn btn-sm btn-primary" href="{{ route('departments.edit', $department->id) }}">Редактировать</a>

                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Удалить</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </table>

    {{ $departments->links() }}
@endsection
