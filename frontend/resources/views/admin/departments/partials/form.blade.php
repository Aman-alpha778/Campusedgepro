<dialog class="portal-modal department-modal" id="{{ $dialogId }}">
  <div class="portal-modal-body department-modal-body">
    <div class="department-modal-head">
      <div class="department-modal-title">
        <span>{{ $department ? 'ED' : 'ND' }}</span>
        <div>
          <h2>{{ $department ? 'Edit department' : 'Create department' }}</h2>
          <p>Single institution department setup with HOD ownership and academic capacity.</p>
        </div>
      </div>
      <button class="department-modal-close" type="button" onclick="this.closest('dialog').close()" aria-label="Close department form">&times;</button>
    </div>
    <form class="portal-form-grid department-modal-form" method="post" action="{{ $action }}">
      @csrf
      @if($method !== 'post') @method($method) @endif
      <label class="portal-field"><span>Department Name</span><input name="name" value="{{ old('name', $department?->name) }}" required></label>
      <label class="portal-field"><span>Department Code</span><input name="code" value="{{ old('code', $department?->code) }}" placeholder="CSE" required></label>
      <label class="portal-field full"><span>Description</span><textarea name="description">{{ old('description', $department?->description) }}</textarea></label>
      <label class="portal-field"><span>Email</span><input type="email" name="email" value="{{ old('email', $department?->email) }}"></label>
      <label class="portal-field"><span>Phone</span><input name="phone" value="{{ old('phone', $department?->phone) }}"></label>
      <label class="portal-field"><span>Location</span><input name="location" value="{{ old('location', $department?->location) }}" placeholder="Block A, Floor 2"></label>
      <label class="portal-field"><span>Established Year</span><input type="number" name="established_year" min="1900" max="{{ now()->year }}" value="{{ old('established_year', $department?->established_year) }}"></label>
      <label class="portal-field"><span>Intake Capacity</span><input type="number" name="total_intake" min="0" value="{{ old('total_intake', $department?->total_intake ?? 0) }}"></label>
      <label class="portal-field"><span>Assign HOD</span><select name="hod_id"><option value="">Unassigned</option>@foreach($hodUsers as $user)<option value="{{ $user->id }}" @selected(old('hod_id', $department?->hod_id) == $user->id)>{{ $user->name }}</option>@endforeach</select></label>
      <label class="portal-field"><span>Status</span><select name="status"><option value="active" @selected(old('status', $department?->status ?? 'active') === 'active')>Active</option><option value="inactive" @selected(old('status', $department?->status) === 'inactive')>Inactive</option></select></label>
      <div class="department-modal-actions full">
        <button type="button" class="portal-button-ghost" onclick="this.closest('dialog').close()">Cancel</button>
        <button class="portal-button">{{ $department ? 'Update department' : 'Create department' }}</button>
      </div>
    </form>
  </div>
</dialog>
