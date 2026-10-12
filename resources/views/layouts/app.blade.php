<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>
        @yield('title', 'Business Management System')
    </title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 text-gray-800">

    <div class="min-h-screen flex">

        {{-- Mobile Overlay --}}
        <div id="sidebar-overlay" class="fixed inset-0 z-40 hidden bg-black/50 lg:hidden">
        </div>


        {{-- Sidebar --}}
        <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-72 max-w-[85vw] bg-slate-900 text-white
                   transform -translate-x-full transition-transform duration-300 ease-in-out
                   lg:static lg:z-auto lg:w-64 lg:max-w-none lg:translate-x-0
                   flex flex-col">

            {{-- Logo / Brand --}}
            <div class="p-5 border-b border-slate-700 flex-shrink-0">

                <div class="flex items-center justify-between">

                    <div>
                        <h1 class="text-xl font-bold">
                            Sultan Traders
                        </h1>

                        <p class="text-xs text-slate-400 mt-1">
                            Management System
                        </p>
                    </div>

                    {{-- Mobile Close Button --}}
                    <button id="close-sidebar" type="button"
                        class="lg:hidden p-2 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800">

                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />

                        </svg>

                    </button>

                </div>

            </div>


            {{-- Navigation --}}
            <nav class="flex-1 overflow-y-auto p-4 space-y-1">

                {{-- Dashboard --}}
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg
                           hover:bg-slate-800 transition-colors">

                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0h4" />

                    </svg>

                    <span>Dashboard</span>

                </a>


                {{-- Products --}}
                @if (auth()->user()->hasPermission('products.view'))
                    <a href="{{ route('products.index') }}"
                        class="flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-slate-800 transition-colors">

                        <span>Products</span>

                    </a>

                    <a href="{{ route('categories.index') }}"
                        class="flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-slate-800 transition-colors">

                        <span>Categories</span>

                    </a>

                    <a href="{{ route('units.index') }}"
                        class="flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-slate-800 transition-colors">

                        <span>Units</span>

                    </a>
                @endif


                {{-- Stocks --}}
                @if (auth()->user()->hasPermission('stock.view'))
                    <div class="space-y-1">

                        {{-- Stocks Parent --}}
                        <button type="button" id="stocks-toggle" class="w-full flex items-center justify-between gap-3 px-4 py-2.5
                                       rounded-lg hover:bg-slate-800 transition-colors text-left">

                            <div class="flex items-center gap-3">

                                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7H4a2 2 0 01-2-2V4a2 2 0 012-2h16a2 2 0 012 2v1a2 2 0 01-2 2zM4 7v13h16V7M8 11h8M8 15h5" />

                                </svg>

                                <span>Stocks</span>

                            </div>

                            {{-- Arrow --}}
                            <svg id="stocks-arrow" class="w-4 h-4 text-slate-400 transition-transform duration-200"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />

                            </svg>

                        </button>


                        {{-- Stock Submenu --}}
                        <div id="stocks-submenu" class="hidden ml-4 pl-4 border-l border-slate-700 space-y-1">

                            {{-- Stock Summary --}}
                            <a href="{{ route('stock.summary') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg
                                           text-sm text-slate-300
                                           hover:bg-slate-800 hover:text-white transition-colors">

                                <span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span>

                                <span>Stock Summary</span>

                            </a>


                            {{-- Stock Ledger --}}
                            <a href="{{ route('stock.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg
                                           text-sm text-slate-300
                                           hover:bg-slate-800 hover:text-white transition-colors">

                                <span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span>

                                <span>Stock Ledger</span>

                            </a>

                        </div>

                    </div>
                @endif



                {{-- Purchases --}}
                @if (auth()->user()->hasPermission('purchases.view'))
                    <div class="space-y-1">

                        {{-- Purchases Parent --}}
                        <button type="button" id="purchases-toggle" class="w-full flex items-center justify-between gap-3 px-4 py-2.5
                                       rounded-lg hover:bg-slate-800 transition-colors text-left">

                            <div class="flex items-center gap-3">

                                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7H4a2 2 0 01-2-2V4a2 2 0 012-2h16a2 2 0 012 2v1a2 2 0 01-2 2zM4 7v13h16V7M8 11h8M8 15h5" />

                                </svg>

                                <span>Purchases</span>

                            </div>

                            {{-- Arrow --}}
                            <svg id="purchases-arrow" class="w-4 h-4 text-slate-400 transition-transform duration-200"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />

                            </svg>

                        </button>


                        {{-- Purchase Submenu --}}
                        <div id="purchases-submenu" class="hidden ml-4 pl-4 border-l border-slate-700 space-y-1">

                            {{-- Purchase History --}}
                            <a href="{{ route('purchases.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg
                                           text-sm text-slate-300
                                           hover:bg-slate-800 hover:text-white transition-colors">

                                <span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span>

                                <span>Purchase History</span>

                            </a>


                            {{-- New Purchase --}}
                            @if (auth()->user()->hasPermission('purchases.create'))
                                <a href="{{ route('purchases.create') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg
                                                                       text-sm text-slate-300
                                                                       hover:bg-slate-800 hover:text-white transition-colors">

                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span>

                                    <span>New Purchase</span>

                                </a>
                            @endif

                        </div>

                    </div>


                @endif
                {{-- Sales --}}
                @if (auth()->user()->hasPermission('sales.view'))
                    <a href="{{route('sales.index')}}"
                        class="flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-slate-800 transition-colors">

                        <span>Sales</span>

                    </a>
                @endif


                {{-- Customers --}}
                @if (auth()->user()->hasPermission('customers.view'))
                    <a href="{{ route('customers.index') }}"
                        class="flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-slate-800 transition-colors">

                        <span>Customers</span>

                    </a>
                @endif


                {{-- Suppliers --}}
                @if (auth()->user()->hasPermission('suppliers.view'))
                    <a href="{{ route('suppliers.index') }}"
                        class="flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-slate-800 transition-colors">

                        <span>Suppliers</span>

                    </a>
                @endif


                {{-- Accounts --}}
                @if (auth()->user()->hasPermission('accounts.view'))
                    <a href="{{route('accounts.index')}}"
                        class="flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-slate-800 transition-colors">

                        <span>Accounts</span>

                    </a>
                @endif


                {{-- Expenses --}}
                @if (auth()->user()->hasPermission('expenses.view'))
                    <a href="{{route('expenses.index')}}"
                        class="flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-slate-800 transition-colors">

                        <span>Expenses</span>

                    </a>
                @endif


                {{-- Reports --}}
                @if (auth()->user()->hasPermission('reports.view'))
                    <a href="{{Route('reports.index')}}"
                        class="flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-slate-800 transition-colors">

                        <span>Reports</span>

                    </a>
                @endif


                {{-- Administration --}}
                <div class="pt-5 mt-5 border-t border-slate-700">

                    <p class="px-4 text-xs uppercase tracking-wider text-slate-500 mb-2">
                        Administration
                    </p>


                    {{-- Users --}}
                    @if (auth()->user()->hasPermission('users.view'))
                        <a href="{{route('admin.users.index')}}"
                            class="flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-slate-800 transition-colors">

                            <span>Users</span>

                        </a>
                    @endif


                    {{-- Roles --}}
                    <a href="{{route('admin.roles.index')}}"
                        class="flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-slate-800 transition-colors">

                        <span>Roles & Permissions</span>

                    </a>


                    {{-- Audit Logs --}}
                    <a href="{{ route('admin.audit-logs.index')  }}"
                        class="flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-slate-800 transition-colors">

                        <span>Audit Logs</span>

                    </a>

                </div>

            </nav>

        </aside>


        {{-- Main Area --}}
        <div class="flex-1 min-w-0 flex flex-col min-h-screen">


            {{-- Top Header --}}
            <header class="bg-white border-b sticky top-0 z-30">

                <div class="px-4 sm:px-6 py-3 sm:py-4 flex items-center justify-between gap-4">

                    {{-- Left Side --}}
                    <div class="flex items-center gap-3 min-w-0">

                        {{-- Mobile Menu Button --}}
                        <button id="open-sidebar" type="button" class="lg:hidden flex-shrink-0 p-2 rounded-lg
                                   text-gray-600 hover:bg-gray-100">

                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />

                            </svg>

                        </button>


                        {{-- Page Title --}}
                        <div class="min-w-0">

                            <h2 class="text-base sm:text-lg font-semibold truncate">
                                @yield('header', 'Dashboard')
                            </h2>

                        </div>

                    </div>


                    {{-- User Area --}}
                    <div class="flex items-center gap-2 sm:gap-4">

                        {{-- User Information --}}
                        <div class="hidden sm:block text-right">

                            <p class="text-sm font-medium truncate max-w-[180px]">
                                {{ auth()->user()->name }}
                            </p>

                            <p class="text-xs text-gray-500 truncate max-w-[180px]">
                                {{ auth()->user()->email }}
                            </p>

                        </div>


                        {{-- User Avatar --}}
                        <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-slate-800
                                    text-white flex items-center justify-center
                                    font-semibold flex-shrink-0">

                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}

                        </div>


                        {{-- Logout --}}
                        <form method="POST" action="{{ route('logout') }}">

                            @csrf

                            <button type="submit" class="px-3 py-2 text-sm bg-gray-100 hover:bg-gray-200
                                       rounded-lg whitespace-nowrap transition-colors">

                                <span class="hidden sm:inline">
                                    Logout
                                </span>

                                <span class="sm:hidden">
                                    ↪
                                </span>

                            </button>

                        </form>

                    </div>

                </div>

            </header>


            {{-- Page Content --}}
            <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-hidden">

                @yield('content')

            </main>


        </div>

    </div>


    {{-- Mobile Sidebar Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');

            const openButton = document.getElementById('open-sidebar');
            const closeButton = document.getElementById('close-sidebar');


            function openSidebar() {

                sidebar.classList.remove('-translate-x-full');

                sidebar.classList.add('translate-x-0');

                overlay.classList.remove('hidden');

                document.body.classList.add('overflow-hidden');
            }


            function closeSidebar() {

                sidebar.classList.remove('translate-x-0');

                sidebar.classList.add('-translate-x-full');

                overlay.classList.add('hidden');

                document.body.classList.remove('overflow-hidden');
            }


            openButton?.addEventListener('click', openSidebar);

            closeButton?.addEventListener('click', closeSidebar);

            overlay?.addEventListener('click', closeSidebar);


            // Close sidebar after clicking a navigation link on mobile
            sidebar.querySelectorAll('a').forEach(function (link) {

                link.addEventListener('click', function () {

                    if (window.innerWidth < 1024) {
                        closeSidebar();
                    }

                });

            });


            // Close mobile sidebar when resizing to desktop
            window.addEventListener('resize', function () {

                if (window.innerWidth >= 1024) {

                    overlay.classList.add('hidden');

                    sidebar.classList.remove('-translate-x-full');

                    sidebar.classList.add('translate-x-0');

                    document.body.classList.remove('overflow-hidden');

                } else {

                    sidebar.classList.remove('translate-x-0');

                    sidebar.classList.add('-translate-x-full');

                }

            });

        });


        // Stocks submenu
        const stocksToggle = document.getElementById('stocks-toggle');
        const stocksSubmenu = document.getElementById('stocks-submenu');
        const stocksArrow = document.getElementById('stocks-arrow');

        stocksToggle?.addEventListener('click', function () {

            stocksSubmenu.classList.toggle('hidden');

            stocksArrow.classList.toggle('rotate-180');

        });
        // Purchases submenu
        const purchasesToggle = document.getElementById('purchases-toggle');
        const purchasesSubmenu = document.getElementById('purchases-submenu');
        const purchasesArrow = document.getElementById('purchases-arrow');

        purchasesToggle?.addEventListener('click', function () {

            purchasesSubmenu.classList.toggle('hidden');

            purchasesArrow.classList.toggle('rotate-180');

        });
    </script>

</body>

</html>