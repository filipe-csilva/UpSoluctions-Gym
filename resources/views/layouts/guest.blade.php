<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'GymControl') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/css/adminlte.css', 'resources/js/app.js'])
    </head>
    <body>
        <div style="display: flex; width: 100vw; height: 100vh; overflow: hidden;">
            <!-- Lado Esquerdo - 60% com Imagem -->
            <div style="display: none; width: 85%; height: 100vh; position: relative; overflow: hidden;">
                <!-- Imagem de fundo da academia -->
                <div style="position: absolute; inset: 0; background-image: url('{{ asset('images/academia-bg.jpg') }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">
                    <!-- Overlay escuro para contraste -->
                    <div style="position: absolute; inset: 0; background: linear-gradient(135deg, rgba(15, 23, 42, 0.85), rgba(15, 23, 42, 0.7), rgba(239, 68, 68, 0.3));"></div>
                </div>

                <!-- Efeitos decorativos -->
                <div style="position: absolute; top: 5rem; left: 5rem; width: 16rem; height: 16rem; background: rgba(239, 68, 68, 0.15); border-radius: 9999px; filter: blur(80px); z-index: 1;"></div>
                <div style="position: absolute; bottom: 5rem; right: 5rem; width: 24rem; height: 24rem; background: rgba(239, 68, 68, 0.1); border-radius: 9999px; filter: blur(80px); z-index: 1;"></div>
                
                <div style="position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; z-index: 2; padding: 3rem;">
                    <div style="text-align: center;  color: white; max-width: 32rem;">
                        <!-- Logo da Academia -->
                        <div style="margin-bottom: 1.5rem; position: relative;">
                            <div style="width: 120px; height: 120px; margin: 0 auto; background: linear-gradient(135deg, #ef4444, #dc2626); border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 20px 60px rgba(239, 68, 68, 0.4);">
                                <!-- Ícone de Haltere -->
                                <svg style="width: 70px; height: 70px; color: white;" viewBox="0 0 100 100" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="10" y="35" width="12" height="30" rx="2" fill="white" stroke="none"/>
                                    <rect x="78" y="35" width="12" height="30" rx="2" fill="white" stroke="none"/>
                                    <rect x="22" y="42" width="56" height="16" rx="2" fill="white" stroke="none"/>
                                    <circle cx="16" cy="50" r="4" fill="none" stroke="white" stroke-width="3"/>
                                    <circle cx="84" cy="50" r="4" fill="none" stroke="white" stroke-width="3"/>
                                </svg>
                            </div>
                        </div>
                        
                        <h1 style="font-size: 3.5rem; font-weight: 800; margin-bottom: 0.5rem; letter-spacing: -0.025em; text-shadow: 0 4px 20px rgba(0,0,0,0.3);">
                            <span style="color: #ffffff;">Gym</span><span style="color: #ef4444;">Control</span>
                        </h1>
                        
                        <p style="font-size: 1.5rem; font-weight: 300; color: #e2e8f0; max-width: 28rem; margin: 0 auto; line-height: 1.625; text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                            Sua academia sob controle total
                        </p>
                        
                        <div style="margin-top: 2.5rem; max-width: 28rem; margin-left: auto; margin-right: auto;">
                            <div style="display: flex; align-items: center; gap: 0.75rem; justify-content: center; color: #cbd5e1; font-size: 0.875rem; background: rgba(0,0,0,0.3); padding: 0.75rem 1.5rem; border-radius: 1rem; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.1);">
                                <span style="display: flex; align-items: center; gap: 0.5rem;">
                                    <svg style="width: 1.25rem; height: 1.25rem; color: #ef4444;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Gestão de alunos
                                </span>
                                <span style="width: 1px; height: 1.5rem; background: rgba(255,255,255,0.2);"></span>
                                <span style="display: flex; align-items: center; gap: 0.5rem;">
                                    <svg style="width: 1.25rem; height: 1.25rem; color: #ef4444;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Controle financeiro
                                </span>
                                <span style="width: 1px; height: 1.5rem; background: rgba(255,255,255,0.2);"></span>
                                <span style="display: flex; align-items: center; gap: 0.5rem;">
                                    <svg style="width: 1.25rem; height: 1.25rem; color: #ef4444;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    Frequência
                                </span>
                            </div>
                        </div>
                        
                        <div style="margin-left: 6.5rem; margin-top: 3rem; padding-top: 2rem; border-top: 1px solid rgba(255,255,255,0.1);">
                            <div style="display: flex; align-items: center; justify-content: center; gap: 0.75rem;">
                                <span style="color: #94a3b8; font-size: 0.75rem;">Desenvolvido por</span>
                                <span style="color: #ef4444; font-size: 0.875rem; font-weight: 600;">UpSoluctions</span>
                                <span style="color: #475569; font-size: 0.75rem;">|</span>
                                <span style="color: #94a3b8; font-size: 0.75rem;">v2.5.0</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lado Direito - 40% Formulário -->
            <div style="width: 100%; height: 100vh; display: flex; align-items: center; justify-content: center; background: #ffffff; padding: 2rem;">
                <div style="width: 100%; max-width: 28rem;">
                    <!-- Logo mobile -->
                    <div style="text-align: center; margin-bottom: 2.5rem; display: block;">
                        <div style="width: 64px; height: 64px; margin: 0 auto 0.75rem; background: linear-gradient(135deg, #ef4444, #dc2626); border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 10px 30px rgba(239, 68, 68, 0.2);">
                            <svg style="width: 36px; height: 36px; color: white;" viewBox="0 0 100 100" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="10" y="35" width="12" height="30" rx="2" fill="white" stroke="none"/>
                                <rect x="78" y="35" width="12" height="30" rx="2" fill="white" stroke="none"/>
                                <rect x="22" y="42" width="56" height="16" rx="2" fill="white" stroke="none"/>
                                <circle cx="16" cy="50" r="4" fill="none" stroke="white" stroke-width="3"/>
                                <circle cx="84" cy="50" r="4" fill="none" stroke="white" stroke-width="3"/>
                            </svg>
                        </div>
                        <h2 style="font-size: 1.5rem; font-weight: 700; color: #0f172a;">
                            <span style="color: #0f172a;">Gym</span><span style="color: #ef4444;">Control</span>
                        </h2>
                        <p style="font-size: 0.875rem; color: #64748b; margin-top: 0.25rem;">Faça login para acessar o sistema</p>
                    </div>
                    
                    {{ $slot }}
                </div>
            </div>
        </div>

        <!-- Estilos para responsividade -->
        <style>
            @media (min-width: 1024px) {
                div:first-child > div:first-child {
                    display: flex !important;
                }
                div:first-child > div:last-child {
                    width: 40% !important;
                }
                div:first-child > div:last-child > div > div:first-child {
                    display: none !important;
                }
            }
        </style>
    </body>
</html>