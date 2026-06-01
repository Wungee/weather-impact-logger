# Weather Impact Logger

A Laravel + Vue.js web application that tracks how weather conditions affect construction site delays.

**Live demo:** https://weather-impact-logger-production.up.railway.app
---

## What It Does

- Create construction sites and associate them with a location
- Log daily weather conditions (rainy, sunny, windy, overcast, cloudy) with temperature and precipitation
- Log project delays with hours lost, reason, and notes
- View a **correlation analysis**: what percentage of rainy days had delays vs. sunny days
- Browse a timeline of weather and delay data side by side

## Tech Stack

- **Backend:** PHP 8.4 / Laravel 13 — REST API with Eloquent ORM
- **Frontend:** Vue.js 3 with Vue Router — single-page application
- **Database:** SQLite (local) / SQLite on Railway (production)
- **Build tool:** Vite
- **Deployment:** Railway

