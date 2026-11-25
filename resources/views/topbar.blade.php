<div class="flex-1 flex flex-col min-w-0 md:overflow-hidden">

            <!-- Desktop Topbar s hledáním -->
            <header class="hidden md:flex bg-sky-500 text-white px-6 py-3 justify-between items-center shadow-sm">
                <div class="text-xl font-bold tracking-wide">HLÁŠENÍ PORUCH</div>

                <form action="{{ route('home') }}" method="GET" class="flex items-center gap-4">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Hledat poruchu..." class="pl-3 pr-4 py-1.5 rounded-md text-sm bg-sky-400/40 text-white placeholder-sky-100 focus:outline-none focus:bg-white focus:text-gray-900">
                    <a href="{{ route('profile') }}" class="flex items-center gap-2.5 text-sm font-medium hover:opacity-90">
                        <div class="w-8 h-8 rounded-full bg-slate-200 text-slate-700 flex items-center justify-center font-bold">{{ $initials }}</div>
                        <span>{{ $u ? $u->name . ' ' . $u->surname : 'Uživatel' }}</span>
                        @if($u)
                            <span class="px-2 py-0.5 rounded-full text-xs font-semibold {{ $roleBadgeClass }}">
                                {{ $u->role_label }}
                            </span>
                        @endif
                    </a>
                </form>