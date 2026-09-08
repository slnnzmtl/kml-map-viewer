# KML Map Viewer

Interactive regional forestry map for browsing affiliates (філії), forestries (лісництва), and points of interest on Google Maps—with optional KML/KMZ overlays and an authenticated catalog admin.

<p align="center">
  <img src="docs/screenshots/product-logo.png" alt="Product logo" width="280" />
</p>

## Status / portfolio decision

**Do not feature this repository as a primary portfolio or Upwork case study.**

It is a solid domain demo of Laravel + Inertia + Google Maps/KML, but it still has incomplete guest list UX, a filesystem-only KML workflow, unused map dependencies, and limited automated tests. Keep it public as a secondary geospatial sample; leave it unpinned and out of professional emphasis until those gaps are closed.

Customer-specific operational details were already stripped from the public surface (see project history). Seeded geography and Ukrainian UI copy remain as anonymized demo content for a forestry-enterprise style map.

## Purpose

Give the public a simple path from a regional overview map → forestry unit → map or list of objects (recreation sites, cameras, tours, and similar points), while admins maintain categories and point data in a small “База даних” dashboard.

## Capabilities

### Public guest experience

| Capability | Description |
|------------|-------------|
| Regional SVG overview | Clickable affiliate regions; forestries without points are visually disabled |
| Forestry map view | Google Maps hybrid with category-icon markers and info windows |
| Category filter | Multi-select filter for categories present on the current forestry |
| Affiliate / forestry switcher | Jump between forestries that have points |
| KML / KMZ overlays | Toggle Google `KmlLayer` overlays loaded from per-forestry storage |
| List view | Card grid of points for a forestry (see [Limitations](#limitations)) |
| Point detail cards | Title, description, image carousel, optional online camera, 3D tour, and route links |

### Admin (authenticated)

| Capability | Description |
|------------|-------------|
| Categories | CRUD with icon images (Spatie Media Library) |
| Points / objects | CRUD with lat/lng or map placement and multi-image upload |
| Affiliates | Read-only tree of affiliates and forestries |
| Auth | Laravel Breeze register / login / password reset / profile |

### Domain model

`Affiliate` (SVG region `code`) → `Forestry` → `Point` → `PointCategory`, with media handled via Spatie Media Library.

## Screenshots

Product UI assets live in [`docs/screenshots/`](docs/screenshots/).

**Regional overview map** (home page SVG):

![Regional forestry overview map](docs/screenshots/regional-map.svg)

**Supporting UI marks:**

<p>
  <img src="docs/screenshots/category-icon-example.png" alt="Example category marker icon" width="48" />
  &nbsp;
  <img src="docs/screenshots/kml-toggle-icon.svg" alt="KML overlay toggle control" width="48" />
</p>

Live application screenshots (affiliate popup → map markers → info window → admin forms) should be captured against a local run with a valid `VITE_GOOGLE_MAPS_API_KEY` and sample points/KML files. Until then, the assets above are the shipping visual references.

## Tech stack

- **Backend:** PHP 8.1+, Laravel 10, Inertia.js, Sanctum, Spatie Media Library, Ziggy
- **Frontend:** Vue 3, Vite 4, Tailwind CSS, `vue3-google-map`, Headless UI, Heroicons, Vueform Multiselect, vue3-carousel
- **Maps:** Google Maps (primary). Leaflet / Mapbox packages are present in `package.json` but unused in application code.

## Setup

### Requirements

- PHP 8.1+ with Composer
- Node.js 18+ (npm or yarn)
- MySQL (or another Laravel-supported database)
- A browser Google Maps JavaScript API key with Maps and KML-related usage enabled as needed

### Install

```bash
composer install
cp .env.example .env
php artisan key:generate

# Configure DB_* and map keys in .env (see below)
php artisan migrate --seed
php artisan storage:link

npm install
npm run dev          # Vite HMR
# or: npm run build  # production assets

php artisan serve
```

Open the app URL from `APP_URL` (default `http://localhost:8000`).

### Environment

Minimum map-related settings:

```env
APP_URL=http://localhost:8000
VITE_GOOGLE_MAPS_API_KEY=your-browser-maps-key
VITE_APP_URL="${APP_URL}"
```

`VITE_APP_URL` is used when building public URLs for KML files served to Google’s `KmlLayer`. Google must be able to fetch those URLs; `localhost` often fails for KML overlays unless you tunnel or deploy a reachable host.

### Seeded login

After `php artisan migrate --seed`:

- **Email:** `admin@admin.com`
- **Password:** `password`

Affiliate / forestry geography is seeded; point category and image seeders are stubs—create categories and points in the dashboard for a meaningful demo.

### KML files

Place `.kml` / `.kmz` files under:

```text
storage/app/public/kml-data/{forestry_id}/
```

After `php artisan storage:link`, they are exposed as `/storage/kml-data/{forestry_id}/...`. There is no in-app KML upload UI.

### Useful routes

| Route | Role |
|-------|------|
| `GET /` | Regional overview |
| `GET /map/{forestry}` | Google map + KML list for a forestry |
| `GET /list/{forestry}` | Point list for a forestry |
| `GET /dashboard/categories` | Admin categories (auth) |
| `GET /dashboard/points` | Admin points (auth) |
| `GET /dashboard/affiliates` | Admin affiliates tree (auth) |

## Limitations

Honest current boundaries of the product:

- **Not portfolio-ready as a flagship.** Prefer LangGraph / Directus / landing-page flagships for client-facing emphasis.
- **KML workflow is manual** (filesystem drop only); Google `KmlLayer` needs publicly reachable URLs.
- **KML click / info-window wiring is incomplete** in the map page (overlay toggle works; interaction polish does not).
- **Guest list page is incomplete** relative to the map page (switching / multiselect parity issues).
- **Affiliate / forestry admin is read-only** (create UI is commented out).
- **Unused frontend map libraries** (Leaflet, Mapbox, jQuery) remain in dependencies.
- **Legacy image models** coexist with Spatie Media Library.
- **Automated tests** cover Breeze/auth scaffolding, not map or point flows.
- **`npm run build`** uses a typo’d Vite mode flag (`devepment`) in `package.json`.
- Default map center is hard-coded for the Volyn-area demo geography.

## License

MIT (Laravel application skeleton). Confirm reuse intent before treating demo geography, UI copy, or media as a client deliverable template.
