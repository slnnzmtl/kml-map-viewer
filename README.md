# KML Map Viewer

Interactive regional forestry map for browsing affiliates (філії), forestries (лісництва), and points of interest on Google Maps—with optional KML/KMZ overlays and an authenticated catalog admin.

## Capabilities

**Public**
- Regional SVG overview with clickable affiliate regions
- Google Maps view with category markers, filters, and info windows
- Optional KML/KMZ overlays per forestry
- List view of points with photos, camera, 3D tour, and route links

**Admin**
- Category and point CRUD (Spatie Media Library for images)
- Read-only affiliate / forestry tree
- Laravel Breeze auth

**Model:** `Affiliate` → `Forestry` → `Point` → `PointCategory`

## Screenshots

![Regional overview map](docs/screenshots/regional-map.svg)

## Tech stack

- Laravel 10, Inertia.js, Vue 3, Vite, Tailwind CSS
- Google Maps via `vue3-google-map`
- Spatie Media Library, Sanctum, Ziggy

## Setup

**Requirements:** PHP 8.1+, Composer, Node.js 18+, MySQL, Google Maps JavaScript API key

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
npm install && npm run dev
php artisan serve
```

**Environment:**

```env
APP_URL=http://localhost:8000
VITE_GOOGLE_MAPS_API_KEY=your-browser-maps-key
VITE_APP_URL="${APP_URL}"
```

`VITE_APP_URL` builds public KML URLs for Google `KmlLayer`. Google must be able to fetch them; `localhost` usually needs a tunnel or deployed host.

**Seeded admin:** `admin@admin.com` / `password`

**KML files:** place `.kml` / `.kmz` in `storage/app/public/kml-data/{forestry_id}/` (no upload UI).

**Routes:** `/`, `/map/{forestry}`, `/list/{forestry}`, `/dashboard/{categories|points|affiliates}`

## Limitations

- KML is filesystem-only; overlays need publicly reachable URLs
- KML info-window interaction is incomplete
- Guest list page is less complete than the map page
- Affiliate / forestry admin is read-only
- Leaflet / Mapbox packages in `package.json` are unused
- Tests cover auth scaffolding, not map flows

## License

MIT
