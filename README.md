## Smart Bins

De code voor het dashboard van onze smart prullenbak. Bij het dashboard horen:
- Een overzicht van alle prullenbakken
- Een interactieve kaart met de locatie van onze prullenbakken
- Een grafiek per prullenbak die de geschiedenis qua volheid laat zien
- Route planning gebaseerd op de volheid van de prullenbakken
- Voorspelling gebaseerd op de geschiedenis qua volheid van prullenbakken

## Installatie

Volg elke standaard Laravel 8 Jetstream tutorial op, maar aan het einde voer dit commando uit:

```
php artisan client:secret
```

## Libraries

De volgende Libraries zijn gebruikt:
- Vue-chart.js
- Tailwindcss
- Leaflet.js

## API service

We hebben Geoapify's API service gebruikt om routes te renderen tussen prullenbakken.