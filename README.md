# NovaCMS (MVC scaffolding)
This repository contains a PHP MVC scaffolding (no CMS logic implemented) suitable as a starting point for NovaCMS.

It contains:
* NGINX webserver
* PHP FastCGI Process Manager with PDO MySQL support
* MariaDB (GPL MySQL fork)
* PHPMyAdmin
* Composer
* Composer package [nikic/fast-route](https://github.com/nikic/FastRoute) for routing

## Structure

- `app/src/Controllers/` — controllers (e.g., `HomeController`, `HelloController`)
- `app/src/Models/` — models (placeholder)
- `app/src/Views/` — views
  - `app/src/Views/layouts/` — layout templates (e.g., `main.php`)
  - `app/src/Views/partials/` — partials (e.g., `header.php`, `footer.php`)
  - `app/src/Views/home/`, `app/src/Views/hello/` — example view folders
- `app/src/ViewModels/` — view models (placeholder)
- `app/src/Enums/` — enums (placeholder)
- `app/src/Core/` — core base classes (placeholder)
- `app/public/` — web root and router entrypoint (`index.php`)

## Setup

1. Install Docker Desktop on Windows or Mac, or Docker Engine on Linux.
1. Clone the project

## Usage

In a terminal, from the cloned project folder, run:
```bash
docker compose up
```

### Composer Autoload

This template is configured to use Composer for PSR-4 autoloading:

- Namespace `NovaCMS\\` is mapped to `app/src/`.

To install dependencies and generate the autoloader, run:

```bash
docker compose run --rm php composer install
```

If you add new classes or change namespaces, regenerate the autoloader:

```bash
docker compose run --rm php composer dump-autoload
```

Entry routing is configured in `app/public/index.php` using FastRoute. Example controllers are under `app/src/Controllers/`.

### NGINX

NGINX will now serve files in the app/public folder.

Go to [http://localhost/hello.php](http://localhost/hello.php). You should see a hello world message.

### PHPMyAdmin

PHPMyAdmin provides basic database administration. It is accessible at [localhost:8080](localhost:8080).

Credentials are defined in `docker-compose.yml`. They are: developer/secret123


### Stopping the docker container

If you want to stop the containers, press Ctrl+C. 

Or run:
```bash
docker compose down
```

## Project rename

To rename the folder to `NovaCMS` locally (optional), run:

```bash
mv /Users/kiankhatibi/RiderProjects/web_development_1_boilerplate /Users/kiankhatibi/RiderProjects/NovaCMS
```

Then open the new folder in your IDE and re-run:

```bash
docker compose run --rm php composer dump-autoload
```
