<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Support\Facades\Blade;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->brandLogo(asset('images/Kel11_TokoKelontongNurhayati.png'))
            ->brandLogoHeight('3rem')
            ->colors([
                'primary' => '#004193', // Biru Navy
                'warning' => '#F39200', // Orange
                'success' => '#F39200', 
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->renderHook(
                'panels::styles.after',
                fn (): string => Blade::render('
                    <style>
                        /* LIGHT MODE ONLY: Area utama (tengah) dengan gradient halus */
                        html:not(.dark) .fi-main { background: linear-gradient(to bottom right, #f4f5f7, #e9ecef) !important; }

                        /* Sidebar tetap Biru Navy dengan bayangan */
                        .fi-sidebar { 
                            background-color: #004193 !important; 
                            box-shadow: 4px 0 15px rgba(0, 0, 0, 0.1) !important;
                        }

                        /* Teks menu standar warna putih transparan */
                        .fi-sidebar-item-label, .fi-sidebar-group-label, .fi-sidebar-item-icon {
                            color: rgba(255, 255, 255, 0.8) !important;
                            transition: all 0.3s ease;
                        }

                        /* Hover menu biasa */
                        .fi-sidebar-item-button:hover .fi-sidebar-item-label,
                        .fi-sidebar-item-button:hover .fi-sidebar-item-icon {
                            color: #ffffff !important;
                        }

                        /* TOMBOL AKTIF: Premium Look Orange Gradient */
                        .fi-sidebar-item-active, 
                        .fi-sidebar-item-active a, 
                        .fi-sidebar-item-active button {
                            background: linear-gradient(90deg, rgba(243, 146, 0, 0.9), rgba(243, 146, 0, 0.7)) !important;
                            border-radius: 12px !important;
                            box-shadow: 0 4px 10px rgba(243, 146, 0, 0.3) !important;
                        }

                        /* Teks menu aktif tebal dan putih */
                        .fi-sidebar-item-active .fi-sidebar-item-label, 
                        .fi-sidebar-item-active .fi-sidebar-item-icon {
                            color: #ffffff !important;
                            font-weight: 800 !important;
                        }
                        
                        /* WIDGETS LIGHT MODE: Latar belakang putih transparan */
                        html:not(.dark) .fi-wi-widget {
                            background: rgba(255, 255, 255, 0.95) !important;
                            border: 1px solid rgba(255,255,255,0.8) !important;
                        }

                        /* WIDGETS UNIVERSAL: Bentuk dan animasi hover */
                        .fi-wi-widget {
                            border-radius: 20px !important;
                            box-shadow: 0 10px 30px rgba(0,0,0,0.04) !important;
                            backdrop-filter: blur(10px);
                            transition: transform 0.3s ease, box-shadow 0.3s ease;
                        }
                        .fi-wi-widget:hover {
                            transform: translateY(-4px);
                            box-shadow: 0 15px 35px rgba(0,0,0,0.15) !important;
                        }
                    </style>
                '),
            );
    }
}