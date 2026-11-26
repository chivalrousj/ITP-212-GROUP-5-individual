{{-- Weather widget. $weather and $weatherLabel come from the View composer in AppServiceProvider --}}
<section class="panel" style="margin-bottom: 18px;">
    <div class="panel-head">
        <div>
            <h3>Weather</h3>
            <small>Today at campus</small>
        </div>
    </div>

    @if ($weather)
        <p style="font-size: 18px; font-weight: 700; margin-bottom: 6px;">{{ $weatherLabel }}</p>
        <p style="font-size: 14px;">
            Temperature: <strong>{{ data_get($weather, 'temperature_2m') }} &deg;C</strong> &middot;
            Humidity: <strong>{{ data_get($weather, 'relative_humidity_2m') }} %</strong> &middot;
            Wind: <strong>{{ data_get($weather, 'wind_speed_10m') }} km/h</strong>
        </p>
    @else
        <p style="font-size: 14px;">Weather is currently unavailable</p>
    @endif

    <small style="display:block; margin-top: 10px;">
        Weather data by <a href="https://open-meteo.com" target="_blank" rel="noopener">Open-Meteo</a>
    </small>
</section>
