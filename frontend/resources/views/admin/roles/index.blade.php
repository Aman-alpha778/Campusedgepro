@extends('admin.layouts.app', ['title' => 'Roles'])

@section('content')
  <section class="portal-page-head"><h1>Role & Permission Management</h1><p>Create roles and assign database-backed permissions.</p></section>
  <div class="portal-admin-toolbar"><span></span><button class="portal-button" onclick="document.getElementById('createRole').showModal()">Create role</button></div>
  <section class="portal-card">
    <table class="portal-table">
      <thead><tr><th>Role</th><th>Users</th><th>Permissions</th><th>Actions</th></tr></thead>
      <tbody>
        @foreach ($roles as $role)
          <tr>
            <td><strong>{{ $role->name }}</strong><div class="portal-table-note">{{ $role->description }}</div></td>
            <td>{{ $role->users_count }}</td>
            <td>{{ $role->permissions->pluck('name')->join(', ') }}</td>
            <td class="portal-actions">
              <button class="portal-button-ghost portal-button-small" onclick="document.getElementById('editRole{{ $role->id }}').showModal()">Edit</button>
              <form method="post" action="{{ route(($adminRoutePrefix ?? 'admin').'.roles.destroy', $role) }}">@csrf @method('delete')<button class="portal-button-danger portal-button-small">Delete</button></form>
            </td>
          </tr>
          @include('admin.roles.partials.form', ['dialogId' => 'editRole'.$role->id, 'role' => $role, 'permissions' => $permissions, 'action' => route(($adminRoutePrefix ?? 'admin').'.roles.update', $role), 'method' => 'put'])
        @endforeach
      </tbody>
    </table>
    <div class="portal-pagination">{{ $roles->links() }}</div>
  </section>
  @include('admin.roles.partials.form', ['dialogId' => 'createRole', 'role' => null, 'permissions' => $permissions, 'action' => route(($adminRoutePrefix ?? 'admin').'.roles.store'), 'method' => 'post'])
@endsection
