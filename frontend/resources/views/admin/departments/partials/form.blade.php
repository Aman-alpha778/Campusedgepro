<dialog class="portal-modal" id="{{ $dialogId }}">
  <div class="portal-modal-body">
    <div class="portal-card-head">
      <div><h2>{{ $department ? 'Edit department' : 'Create department' }}</h2><p>Single institution department setup.</p></div>
      <button class="portal-button-ghost" type="button" onclick="this.closest('dialog').close()">Close</button>
    </div>
    <form class="portal-form-grid" method="post" action="{{ $action }}">
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
      <div class="full"><button class="portal-button">Save department</button></div>
    </form>
  </div>
</dialog>
