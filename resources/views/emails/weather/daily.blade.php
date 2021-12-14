@component('mail::message')
# Previsão do Tempo - {{ date("d/m/Y H:i") }}

<b>Olá, {{ $users['name'] }}. A previsão para {{ $users['forecast']['name'] }} hoje:</b>

<b>{{ ucfirst($users['forecast']['weather'][0]['description']) }},</b>
<b>Temperatura: {{ $users['forecast']['main']['temp'] }} ℃</b><br>
<p>Temp. Mínima: {{ $users['forecast']['main']['temp_min'] }} ℃</p>
<p>Temp. Máxima: {{ $users['forecast']['main']['temp_max'] }} ℃</p>
<p>Sensação Térmica: {{ $users['forecast']['main']['feels_like'] }} ℃</p>
<p>Pressão Atmosférica: {{ $users['forecast']['main']['pressure'] }} Pa</p>
<p>Umidade Relativa do Ar: {{ $users['forecast']['main']['humidity'] }}%</p>

@endcomponent
