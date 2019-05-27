
@if (session('info'))
<div class="flash bg-info text-center py-2 my-0">{{ session('info') }}</div>
@endif

@if (session('success'))
<div class="flash bg-success text-center py-2 my-0">{{ session('success') }}</div>
@endif

@if (session('error'))
<div class="flash bg-danger text-center py-2 my-0">{{ session('error') }}</div>
@endif