<x-guest-layout>
    <!-- Session Status -->
    @if (session('status'))
        <div style="margin-bottom: 1.5rem; padding: 0.75rem 1rem; background: #dcfce7; color: #166534; border-radius: 0.5rem; font-size: 0.875rem; border: 1px solid #86efac;">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div style="margin-bottom: 1.5rem; padding: 0.75rem 1rem; background: #fee2e2; color: #991b1b; border-radius: 0.5rem; font-size: 0.875rem; border: 1px solid #fca5a5;">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- E-mail -->
        <div style="margin-bottom: 1.25rem;">
            <label for="email" style="display: block; font-size: 0.875rem; font-weight: 600; color: #0f172a; margin-bottom: 0.375rem;">
                E-mail
            </label>
            <input 
                id="email" 
                type="email" 
                name="email" 
                value="{{ old('email') }}" 
                placeholder="seu@email.com"
                required 
                autofocus 
                autocomplete="username"
                style="width: 100%; padding: 0.75rem 1rem; border: 2px solid #e2e8f0; border-radius: 0.5rem; font-size: 1rem; transition: all 0.2s; outline: none; background: #f8fafc;"
                onfocus="this.style.borderColor='#ef4444'; this.style.background='#ffffff'; this.style.boxShadow='0 0 0 4px rgba(239, 68, 68, 0.1)';"
                onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'; this.style.boxShadow='none';"
            >
            @error('email')
                <p style="margin-top: 0.375rem; font-size: 0.8rem; color: #dc2626;">{{ $message }}</p>
            @enderror
        </div>

        <!-- Senha -->
        <div style="margin-bottom: 1.25rem;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.375rem;">
                <label for="password" style="display: block; font-size: 0.875rem; font-weight: 600; color: #0f172a;">
                    Senha
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" style="font-size: 0.8rem; color: #ef4444; text-decoration: none; font-weight: 500; transition: color 0.2s;" onmouseover="this.style.color='#dc2626'" onmouseout="this.style.color='#ef4444'">
                        Esqueceu a senha?
                    </a>
                @endif
            </div>
            <input 
                id="password" 
                type="password"
                name="password"
                placeholder="Digite sua senha"
                required 
                autocomplete="current-password"
                style="width: 100%; padding: 0.75rem 1rem; border: 2px solid #e2e8f0; border-radius: 0.5rem; font-size: 1rem; transition: all 0.2s; outline: none; background: #f8fafc;"
                onfocus="this.style.borderColor='#ef4444'; this.style.background='#ffffff'; this.style.boxShadow='0 0 0 4px rgba(239, 68, 68, 0.1)';"
                onblur="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc'; this.style.boxShadow='none';"
            >
            @error('password')
                <p style="margin-top: 0.375rem; font-size: 0.8rem; color: #dc2626;">{{ $message }}</p>
            @enderror
        </div>

        <!-- Lembrar-me -->
        <div style="margin-bottom: 1.5rem;">
            <label style="display: inline-flex; align-items: center; cursor: pointer;">
                <input 
                    type="checkbox" 
                    name="remember"
                    style="width: 1rem; height: 1rem; border-radius: 0.25rem; border: 2px solid #cbd5e1; color: #ef4444; accent-color: #ef4444; cursor: pointer;"
                >
                <span style="margin-left: 0.5rem; font-size: 0.875rem; color: #475569; font-weight: 500;">Lembrar-me</span>
            </label>
        </div>

        <!-- Botão Entrar -->
        <div style="margin-bottom: 1.5rem;">
            <button 
                type="submit"
                style="width: 100%; padding: 0.875rem 1rem; background: linear-gradient(135deg, #ef4444, #dc2626); color: white; border: none; border-radius: 0.5rem; font-weight: 700; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.05em; cursor: pointer; transition: all 0.3s; box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);"
                onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(239, 68, 68, 0.4)';"
                onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(239, 68, 68, 0.3)';"
            >
                Entrar
            </button>
        </div>

        <!-- Versão mobile com UpSoluctions -->
        <div style="text-align: center; padding-top: 1.5rem; border-top: 1px solid #e2e8f0; display: block;">
            <div style="display: flex; align-items: center; justify-content: center; gap: 0.5rem; flex-wrap: wrap;">
                <span style="color: #94a3b8; font-size: 0.7rem;">Desenvolvido por</span>
                <span style="color: #ef4444; font-size: 0.75rem; font-weight: 600;">UpSoluctions</span>
                <span style="color: #cbd5e1; font-size: 0.75rem;">|</span>
                <span style="color: #94a3b8; font-size: 0.7rem;">v2.5.0</span>
            </div>
        </div>
    </form>
</x-guest-layout>