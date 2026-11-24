# Benchmarks

The benchmarks measure the application the way users hit it: full HTTP
requests through the Symfony kernel, and the `app:update-sighting-scores`
console command. [CodSpeed](https://codspeed.io) runs them on every pull
request (`.github/workflows/codspeed.yml`) and reports changes.

| Benchmark | What it measures |
| --- | --- |
| `GET / (homepage)` | Homepage with the 25 latest sightings |
| `GET /_sightings?page=2` | AJAX partial that loads the next page of sightings |
| `GET /sighting/1` | Sighting page with a few comments |
| `GET /sighting/135` | Sighting page with 520 comments (the slow page of the workshop) |
| `app:update-sighting-scores command` | Score computation over all sightings |

Each benchmark is one PHP process, listed in `codspeed.yml` at the root of
the repository. It runs in the `bench` environment (`.env.bench`,
`config/packages/bench/`): no debug, Doctrine caches as in prod, and a SQLite
database in `var/bench.db`, so no database server is needed.

## Run them locally

Requirements: PHP 8.2 with the `pdo_sqlite` extension, the Composer
dependencies, and the [CodSpeed CLI](https://codspeed.io/docs/cli).

```sh
composer install
# Creates var/bench.db and loads the fixtures with a fixed random seed
php benchmarks/setup.php
codspeed run -m simulation
```

Run `php benchmarks/setup.php` again after a change to the entities or the
fixtures.

To run a single request or command outside CodSpeed:

```sh
php benchmarks/request.php /sighting/135
php benchmarks/command.php app:update-sighting-scores
```
