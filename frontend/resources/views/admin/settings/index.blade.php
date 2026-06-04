@extends('admin.layouts.app', ['title' => 'Settings'])

@section('content')
  <section class="portal-page-head"><h1>System Settings</h1><p>Manage database-backed college identity and theme values.</p></section>
  <section class="portal-card">
    <form class="portal-form-grid" method="post" action="{{ route(($adminRoutePrefix ?? 'admin').'.settings.update') }}">
      @csrf
      @foreach ($settings as $setting)
        <label class="portal-field {{ in_array($setting->key, ['address', 'theme']) ? 'full' : '' }}"><span>{{ Str::headline($setting->key) }}</span><textarea name="settings[{{ $setting->key }}]">{{ old('settings.'.$setting->key, $setting->value) }}</textarea></label>
      @endforeach
      <div class="full"><button class="portal-button">Save settings</button></div>
    </form>
  </section>
@endsection
