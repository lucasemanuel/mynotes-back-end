@component('mail::message')
# Token para Recuperaçãp de Senha

Cole o código abaixo no campo da página de recuperação.<br>

## Código:

@component('mail::panel')
    {{ $token }}
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
