@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Admin Dashboard</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>User</th>
                <th>Email</th>
                <th>Current Role</th>
                <th>Status</th>
                <th>Assign Role</th>
                <th>Toggle Status</th>
            </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->role->role_name ?? 'None' }}</td>
                <td>{{ $user->is_active ? 'Active' : 'Inactive' }}</td>
                <td>
                    <form method="POST" action="{{ route('admin.assignRole') }}">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                        <select name="role_name" class="form-select">
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                        <button class="btn btn-sm btn-primary mt-1">Assign</button>
                    </form>
                </td>
                <td>
                    <form method="POST" action="{{ route('admin.toggleStatus', $user->id) }}">
                        @csrf
                        <button class="btn btn-sm btn-warning">
                            {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
