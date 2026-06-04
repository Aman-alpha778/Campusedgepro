<dialog class="portal-modal" id="{{ $dialogId }}">
  <div class="portal-modal-body">
    <div class="portal-card-head"><div><h2>{{ $role ? 'Edit role' : 'Create role' }}</h2><p>Permissions are loaded from the permissions table.</p></div><button class="portal-button-ghost" onclick="this.closest('dialog').close()">Close</button></div>
    <form class="portal-form" method="post" action="{{ $action }}">
      @csrf @if($method !== 'post') @method($method) @endif
      <label class="portal-field"><span>Name</span><input name="name" value="{{ old('name', $role?->name) }}" required></label>
      <label class="portal-field"><span>Description</span><textarea name="description">{{ old('description', $role?->description) }}</textarea></label>
      @foreach ($permissions as $group => $items)
        <h3>{{ $group ?: 'General' }}</h3>
        <div class="portal-checkbox-grid">
          @foreach ($items as $permission)
            <label class="portal-checkbox"><input type="checkbox" name="permissions[]" value="{{ $permission->id }}" @checked($role?->permissions->contains($permission))> {{ $permission->name }}</label>
          @endforeach
        </div>
      @endforeach
      <button class="portal-button" type="submit">Save role</button>
    </form>
  </div>
</dialog>
