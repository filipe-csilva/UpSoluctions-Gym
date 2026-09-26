@extends('adminlte::page')

@section('title', 'Nova mensagem')

@section('content_header')<h1>Nova mensagem</h1>@stop

@section('content')
    <x-alerts />
    <div class="card"><div class="card-body">
        <form method="POST" action="{{ route('messages.store') }}">
            @csrf
            @if (in_array($role, ['admin', 'manager'], true))
                <div class="mb-3"><label class="form-label" for="audience">Enviar para</label><select id="audience" name="audience" class="form-select" required><option value="all">Todos</option><option value="unit">Alunos da unidade</option><option value="direct">Um aluno específico</option></select></div>
                <div id="unit-field" class="mb-3"><label class="form-label" for="unit_id">Unidade</label><select id="unit_id" name="unit_id" class="form-select"><option value="">Selecione a unidade</option>@foreach($units as $unit)<option value="{{ $unit->id }}">{{ $unit->name }}</option>@endforeach</select></div>
                <div id="recipient-field" class="mb-3 d-none"><label class="form-label" for="recipient_id">Destinatário</label><select id="recipient_id" name="recipient_id" class="form-select"><option value="">Selecione o destinatário</option>@foreach($recipients as $recipient)<option value="{{ $recipient->id }}">{{ $recipient->name }}</option>@endforeach</select></div>
            @else
                @if ($role === 'student')
                    <div class="mb-3"><label class="form-label" for="audience">Enviar para</label><select id="audience" name="audience" class="form-select" required><option value="direct">Meu instrutor</option><option value="reception">Recepção da unidade</option></select></div>
                    <div class="mb-3"><label class="form-label" for="recipient_id">Instrutor</label><select id="recipient_id" name="recipient_id" class="form-select"><option value="">Selecione o instrutor</option>@foreach($recipients as $recipient)<option value="{{ $recipient->id }}">{{ $recipient->name }}</option>@endforeach</select></div>
                    <input type="hidden" name="unit_id" value="{{ auth()->user()->unit_id }}">
                @else
                    <input type="hidden" name="audience" value="direct">
                    <div class="mb-3"><label class="form-label" for="recipient_id">Destinatário</label><select id="recipient_id" name="recipient_id" class="form-select" required><option value="">Selecione</option>@foreach($recipients as $recipient)<option value="{{ $recipient->id }}">{{ $recipient->name }}</option>@endforeach</select></div>
                @endif
            @endif
            <div class="mb-3"><label class="form-label" for="subject">Assunto</label><input id="subject" name="subject" class="form-control" required maxlength="150"></div>
            <div class="mb-3"><label class="form-label" for="body">Mensagem</label><textarea id="body" name="body" class="form-control" rows="6" required></textarea></div>
            <button class="btn btn-primary" type="submit">Enviar mensagem</button>
        </form>
    </div></div>
@stop

@if (in_array($role, ['admin', 'manager'], true))
    @push('js')
        <script>
            (() => {
                const audience = document.getElementById('audience');
                const unitField = document.getElementById('unit-field');
                const recipientField = document.getElementById('recipient-field');

                const updateFields = () => {
                    unitField.classList.toggle('d-none', audience.value !== 'unit');
                    recipientField.classList.toggle('d-none', audience.value !== 'direct');
                };

                audience.addEventListener('change', updateFields);
                updateFields();
            })();
        </script>
    @endpush
@endif
