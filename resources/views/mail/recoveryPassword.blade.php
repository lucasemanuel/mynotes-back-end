@component('mail::message')
# Recuperação de Senha

Você solicitou a recuperação de senha de acesso ao MyNotes.<br>
Clique no botão abaixo para cadastrar nova senha.<br>

@component('mail::button', ['url' => $url])
    Recuperar minha senha
@endcomponent

Link:<br>
{{ $url }}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
