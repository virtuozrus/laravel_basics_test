@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Пользователи</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('users.create') }}">Создать нового пользователя</a>
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
            <th>№</th>
            <th>ID</th>
            <th>Имя</th>
            <th>E-mail</th>
            <th>Отделы</th>
            <th>Создан в</th>
            <th>Обновлен в</th>
            <th>Действие</th>
        </tr>

        @foreach ($users as $user)
            <tr>
                <td>{{ ++$i }}</td>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    <ul>
                        @foreach($user->departments->unique()->take(5) as $department)
                            <li>{{ $department->name }}</li>
                        @endforeach
                    </ul>
                </td>
                <td>{{ $user->created_at->format('d.m.Y H:i:s') }}</td>
                <td>{{ $user->updated_at->format('d.m.Y H:i:s') }}</td>
                <td>
                    <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                        @csrf

                        <a class="btn btn-sm btn-info" href="{{ route('users.show', $user->id) }}">Показать</a>
                        <a class="btn btn-sm btn-primary" href="{{ route('users.edit', $user->id) }}">Редактировать</a>

                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Удалить</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </table>

    {{ $users->links() }}
@endsection
