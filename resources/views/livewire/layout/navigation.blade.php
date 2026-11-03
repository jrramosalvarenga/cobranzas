<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<nav x-data="{ open: false }" class="bg-gradient-to-r from-[#0f2a4a] to-[#0d7c5f] shadow-md">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center gap-2">
                    <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center gap-2">
                        <x-application-logo class="block h-8 w-8" />
                        <span class="hidden sm:block font-semibold text-white">{{ config('app.name') }}</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden sm:-my-px sm:ms-6 sm:flex sm:space-x-4 lg:space-x-6 overflow-x-auto scrollbar-hide">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    <x-nav-link :href="route('cobranza.registrar')" :active="request()->routeIs('cobranza.*')" wire:navigate>
                        {{ __('Cobranza') }}
                    </x-nav-link>
                    <x-nav-link :href="route('constancias.index')" :active="request()->routeIs('constancias.*')" wire:navigate>
                        {{ __('Constancias') }}
                    </x-nav-link>
                    @if(auth()->user()->isAdmin() || auth()->user()->isSuperAdmin())
                    <x-nav-link :href="route('clientes.index')" :active="request()->routeIs('clientes.*')" wire:navigate>
                        {{ __('Clientes') }}
                    </x-nav-link>
                    <x-nav-link :href="route('contratos.index')" :active="request()->routeIs('contratos.*')" wire:navigate>
                        {{ __('Contratos') }}
                    </x-nav-link>
                    <x-nav-link :href="route('cuotas.index')" :active="request()->routeIs('cuotas.*')" wire:navigate>
                        {{ __('Cuotas') }}
                    </x-nav-link>
                    <x-nav-link :href="route('servicios.index')" :active="request()->routeIs('servicios.*')" wire:navigate>
                        {{ __('Servicios') }}
                    </x-nav-link>
                    <x-nav-link :href="route('contabilidad.index')" :active="request()->routeIs('contabilidad.*')" wire:navigate>
                        {{ __('Contabilidad') }}
                    </x-nav-link>
                    <x-nav-link :href="route('usuarios.index')" :active="request()->routeIs('usuarios.*')" wire:navigate>
                        {{ __('Usuarios') }}
                    </x-nav-link>
                    @endif
                    @if(auth()->user()->isSuperAdmin())
                    <x-nav-link :href="route('tenants.index')" :active="request()->routeIs('tenants.*')" wire:navigate>
                        {{ __('Organizaciones') }}
                    </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-4 shrink-0">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-white/20 text-sm leading-4 font-medium rounded-md text-white/90 bg-white/10 hover:bg-white/20 hover:text-white focus:outline-none transition ease-in-out duration-150">
                            <div x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile')" wire:navigate>
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <button wire:click="logout" class="w-full text-start">
                            <x-dropdown-link>
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </button>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-white/80 hover:text-white hover:bg-white/10 focus:outline-none focus:bg-white/10 focus:text-white transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('cobranza.registrar')" :active="request()->routeIs('cobranza.*')" wire:navigate>
                {{ __('Cobranza') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('constancias.index')" :active="request()->routeIs('constancias.*')" wire:navigate>
                {{ __('Constancias') }}
            </x-responsive-nav-link>
            @if(auth()->user()->isAdmin() || auth()->user()->isSuperAdmin())
            <x-responsive-nav-link :href="route('clientes.index')" :active="request()->routeIs('clientes.*')" wire:navigate>
                {{ __('Clientes') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('contratos.index')" :active="request()->routeIs('contratos.*')" wire:navigate>
                {{ __('Contratos') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('cuotas.index')" :active="request()->routeIs('cuotas.*')" wire:navigate>
                {{ __('Cuotas') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('servicios.index')" :active="request()->routeIs('servicios.*')" wire:navigate>
                {{ __('Servicios') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('contabilidad.index')" :active="request()->routeIs('contabilidad.*')" wire:navigate>
                {{ __('Contabilidad') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('usuarios.index')" :active="request()->routeIs('usuarios.*')" wire:navigate>
                {{ __('Usuarios') }}
            </x-responsive-nav-link>
            @endif
            @if(auth()->user()->isSuperAdmin())
            <x-responsive-nav-link :href="route('tenants.index')" :active="request()->routeIs('tenants.*')" wire:navigate>
                {{ __('Organizaciones') }}
            </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-white/20">
            <div class="px-4">
                <div class="font-medium text-base text-white" x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>
                <div class="font-medium text-sm text-white/70">{{ auth()->user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile')" wire:navigate>
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <button wire:click="logout" class="w-full text-start">
                    <x-responsive-nav-link>
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </button>
            </div>
        </div>
    </div>
</nav>
