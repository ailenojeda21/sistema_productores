@props(['user'])

<div class="relative">
    <div class="rounded-full overflow-hidden border-2 border-white shadow-lg h-24 w-24">
        <img
            src="{{ $user->avatar ? asset('images/avatars/' . $user->avatar) : asset('images/avatars/uno.png') }}"
            alt="{{ $user->name }}"
            class="h-full w-full object-cover"
        >
    </div>
</div>
