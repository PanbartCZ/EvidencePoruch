{{-- resources/views/partials/contact-card.blade.php --}}
@php
    $isAdmin  = $type === 'spravce';
    $initials = mb_strtoupper(mb_substr($user->name, 0, 1) . mb_substr($user->surname, 0, 1));
    $roleLine = $isAdmin ? 'Správce' : ($user->specializace ?: 'Technik');
@endphp

<article class="flex flex-col items-center rounded-2xl bg-white p-6 text-center shadow-sm ring-1 ring-slate-200 transition duration-200 hover:-translate-y-1 hover:shadow-lg">

    {{-- avatar s iniciály a ikonou role --}}
    <div class="relative">
        <div class="flex size-20 items-center justify-center rounded-full text-2xl font-semibold ring-4 ring-slate-100 {{ $isAdmin ? 'bg-[#1f2a44] text-white' : 'bg-blue-500 text-white' }}">
            {{ $initials }}
        </div>
        <span class="absolute -bottom-1 -right-1 flex size-7 items-center justify-center rounded-full bg-white text-sm shadow ring-1 ring-slate-200 {{ $isAdmin ? 'text-[#1f2a44]' : 'text-blue-500' }}">
            <i class="bi {{ $isAdmin ? 'bi-shield-lock-fill' : 'bi-tools' }}"></i>
        </span>
    </div>

    {{-- jméno a role --}}
    <h3 class="mt-5 text-lg font-semibold text-[#1f2a44]">{{ $user->name }} {{ $user->surname }}</h3>
    <p class="mt-0.5 text-sm font-medium text-blue-600">{{ $roleLine }}</p>

    {{-- kontakty --}}
    <div class="mt-4 flex w-full flex-col gap-2 border-t border-slate-100 pt-4 text-sm text-slate-600">
        <a href="tel:{{ preg_replace('/\s+/', '', $user->phone) }}"
           class="inline-flex items-center justify-center gap-2 transition hover:text-[#1f2a44]">
            <i class="bi bi-telephone-fill text-slate-400"></i>
            {{ $user->phone }}
        </a>
        <a href="mailto:{{ $user->email }}"
           class="inline-flex items-center justify-center gap-2 break-all transition hover:text-[#1f2a44]">
            <i class="bi bi-envelope-fill shrink-0 text-slate-400"></i>
            {{ $user->email }}
        </a>
    </div>
</article>