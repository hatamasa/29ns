
@if (session('info') ?? isset($info_message))
<div class="flash bg-info text-center py-2 my-0">{{ session('info') ?? $info_message }}</div>
@endif

@if (session('success') ?? isset($success_message))
<div class="flash bg-success text-center py-2 my-0">{{ session('success') ?? $success_message }}</div>
@endif

@if (session('error') ?? isset($error_message))
<div class="flash bg-danger text-center py-2 my-0">{{ session('error') ?? $error_message }}</div>
@endif