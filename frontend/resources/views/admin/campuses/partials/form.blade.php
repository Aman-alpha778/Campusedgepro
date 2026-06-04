<dialog class="portal-modal" id="{{ $dialogId }}">
  <div class="portal-modal-body">
    <div class="portal-card-head"><div><h2>{{ $campus ? 'Edit campus' : 'Add campus' }}</h2><p>All values are stored in the campus table.</p></div><button class="portal-button-ghost" onclick="this.closest('dialog').close()">Close</button></div>
    <form class="portal-form-grid" method="post" action="{{ $action }}">
      @csrf @if($method !== 'post') @method($method) @endif
      @foreach (['name' => 'Name', 'code' => 'Code', 'email' => 'Email', 'phone' => 'Phone', 'city' => 'City', 'state' => 'State', 'country' => 'Country', 'logo' => 'Logo path'] as $field => $label)
        <label class="portal-field"><span>{{ $label }}</span><input name="{{ $field }}" value="{{ old($field, $campus?->{$field}) }}" {{ in_array($field, ['name','code','email']) ? 'required' : '' }}></label>
      @endforeach
      <label class="portal-field full"><span>Address</span><textarea name="address">{{ old('address', $campus?->address) }}</textarea></label>
      <label class="portal-field"><span>Status</span><select name="status" required><option value="active" @selected(old('status', $campus?->status ?? 'active') === 'active')>Active</option><option value="inactive" @selected(old('status', $campus?->status) === 'inactive')>Inactive</option></select></label>
      <div class="full"><button class="portal-button" type="submit">Save campus</button></div>
    </form>
  </div>
</dialog>
