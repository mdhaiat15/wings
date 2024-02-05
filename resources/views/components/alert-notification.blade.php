@if (session('status'))
    <x-alert type="" message="{{ session('status') }}" :close="false" />
@endif
@if (session('status-success'))
    <x-alert type="" message="{{ session('status-success') }}" :close="false" />
@endif
@if (session('status-error'))
    <x-alert type="error" message="{{ session('status-error') }}" :close="false" />
@endif

@if ($errors->any())
    <x-alert type="error" message="Error" :close="false" />

    @env('local')
    @foreach ($errors->all() as $message)
        <li>{{ $message }}</li>
    @endforeach
    @endenv
@endif
