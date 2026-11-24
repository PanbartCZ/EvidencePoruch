@extends('layouts.default')

@section('title', 'Kontakty | Hlášení poruch')

@section('header')
@endsection

@section('maincontent')
<div class="mx-auto w-full max-w-7xl px-4 py-12 sm:px-6 lg:px-8">

    {{-- HLAVIČKA --}}
    <div class="mx-auto mb-12 max-w-2xl text-center">
        <p class="text-sm font-semibold text-blue-600">Jsme tu pro vás</p>
        <h1 class="mt-2 text-4xl font-semibold tracking-tight text-[#1f2a44] sm:text-5xl">Kontakty</h1>
        <p class="mt-4 text-lg leading-relaxed text-slate-500">
            Máte problém nebo dotaz? Obraťte se na správce, nebo přímo na technika podle jeho zaměření.
        </p>

        {{-- PŘEPÍNAČ --}}
        <div role="tablist" aria-label="Typ kontaktu" class="mt-7 flex flex-wrap items-center justify-center gap-3">
            <button type="button" role="tab" data-tab="spravci" data-active="true" aria-selected="true"
                    class="cursor-pointer rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-[#1f2a44] shadow-sm transition hover:bg-slate-50 data-[active=true]:border-[#1f2a44] data-[active=true]:bg-[#1f2a44] data-[active=true]:text-white data-[active=true]:hover:bg-[#2c3b5e]">
                Správci
                <span class="ml-1 opacity-60">{{ $spravci->count() }}</span>
            </button>

            <button type="button" role="tab" data-tab="technici" data-active="false" aria-selected="false"
                    class="cursor-pointer rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-[#1f2a44] shadow-sm transition hover:bg-slate-50 data-[active=true]:border-[#1f2a44] data-[active=true]:bg-[#1f2a44] data-[active=true]:text-white data-[active=true]:hover:bg-[#2c3b5e]">
                Technici
                <span class="ml-1 opacity-60">{{ $technici->count() }}</span>
            </button>
        </div>
    </div>

    {{-- SPRÁVCI --}}
    <section data-panel="spravci" role="tabpanel" class="animate-fade-in">
        <h2 class="sr-only">Správci</h2>

        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @forelse($spravci as $spravce)
                @include('contact-card', ['user' => $spravce, 'type' => 'spravce'])
            @empty
                <p class="col-span-full text-center text-slate-500">
                    <i class="bi bi-exclamation-circle"></i> Kontakt je momentálně nedostupný.
                </p>
            @endforelse
        </div>
    </section>

    {{-- TECHNICI --}}
    <section data-panel="technici" role="tabpanel" hidden class="animate-fade-in">
        <h2 class="sr-only">Technici</h2>

        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @forelse($technici as $technik)
                @include('contact-card', ['user' => $technik, 'type' => 'technik'])
            @empty
                <p class="col-span-full text-center text-slate-500">
                    <i class="bi bi-exclamation-circle"></i> Kontakt je momentálně nedostupný.
                </p>
            @endforelse
        </div>
    </section>
</div>

<script>
(() => {
    const tabs   = [...document.querySelectorAll('[data-tab]')];
    const panels = [...document.querySelectorAll('[data-panel]')];

    const show = (name) => {
        tabs.forEach((tab) => {
            const active = tab.dataset.tab === name;
            tab.dataset.active = String(active);
            tab.setAttribute('aria-selected', String(active));
        });
        panels.forEach((panel) => {
            panel.hidden = panel.dataset.panel !== name;
        });
    };

    tabs.forEach((tab) => tab.addEventListener('click', () => {
        show(tab.dataset.tab);
        history.replaceState(null, '', '#' + tab.dataset.tab);
    }));

    const fromHash = location.hash.slice(1);
    if (tabs.some((tab) => tab.dataset.tab === fromHash)) show(fromHash);
})();
</script>
@endsection

@section('footer')
@endsection
