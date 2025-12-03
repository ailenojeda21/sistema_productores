@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'p-4 mb-4 rounded-lg font-medium text-sm' . 
        (session('status') === 'We have emailed your password reset link!' 
            ? ' bg-green-100 text-green-700' 
            : ' bg-blue-100 text-blue-700')]) }}>
        {{ $status }}
    </div>
@endif
