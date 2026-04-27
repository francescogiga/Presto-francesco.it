<form action="{{ route('locale.set', $lang) }}" method="POST">
    @csrf
    <button type="submit" class="btn btn-link p-0 border-0">
        <img src="{{ asset('vendor/blade-flags/country-' . $lang . '.svg') }}" width="24" height="24" alt="{{ $lang }}">
    </button>
</form>
