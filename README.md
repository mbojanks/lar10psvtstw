# Laravel 10 + Vite + Svelte + Tailwind CSS

This starter template includes:

-   Laravel 10
    -   with Passport package - so recommended OAuth 2.0 with PKCE could also be implemented if needed; Passport version is such that Passport::Routes() command is still needed)
    -   jwt token for authenticated users transfered via cookie
    -   a model property (and its corresponding database table field) containing a file path / address can be automatically synchronized with a file it refers to (a new file is created on executing a regular model's create() command; an old file is deleted on a successful update or deletion of a record via model methods)
-   Vite
-   Svelte
    -   with tinro Svelte client router
    -   browser can be refreshed on client routes and appropriate route will be displayed (although client user session is destroyed and a user should login again)
    -   svelte-fa Svelte Font Awesome component
    -   i18n support - in the same .json files with server-side translation strings; it could be synched with server-side locale
    -   Skeleton UI library - based on Tailwind and Svelte
-   Tailwind CSS (with `@tailwindcss/forms` plugin)
-   Axios (is optional, built-in `fetch()` is already OK to send API calls from client side)
-   TypeScript

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

<p align="center"><a href="https://vitejs.dev" target="_blank"><img src="https://vitejs.dev/logo-with-shadow.png" width="400"></a> <a href="https://svelte.dev" target="_blank"><img src="https://svelte.dev/images/twitter-thumbnail.jpg" width="400"></a> <a href="https://tailwindcss.com/" target="_blank"><img src="https://tailwindcss.com/_next/static/media/tailwindcss-logotype.ed60a6f85c663923c4d6ee9d85f359cd.svg" width="400"></a></p>

## Information

-   The client Svelte app is located in [`resources/js/`](/resources/js/).
-   You can use [`resources/css/`](/resources/css/) for styling.
-   Tailwind CSS styles should be applied via `@apply` in Svelte component's styles within &lt;style&gt; (like recommended <a href="https://kaan.fyi/feed/making-sense-of-tailwind-in-svelte/">here</a>)...
-   ... or remove Tailwind CSS styles (classes) if you don't want to use them by commenting lines in [`resources/css/app.css`](/resources/css/app.css) file
-   ... Comment those lines too when Skeleton UI is installed, for it takes care about those Tailwind styles as well when its all.css styles are imported like in [`resources/js/App.svelte`](/resources/js/App.svelte)
-   Responsive design is performed by means of Tailwind classes and by using a [`resources/js/components/SideMenus.svelte`](/resources/js/components/SideMenus.svelte) Svelte component as the same content for the Drawer Skeleton UI Svelte component on small screens as well as for the Left Sidebar menu on wider screens.

## Installation

Install the composer dependencies (often is better to remove the composer.lock file first):

```bash
composer install
```

Make a copy of the `.env.example` file named `.env` - as following in Linux:

```bash
cp .env.example .env
```
(`xcopy` instead of `cp` in Windows command prompt)

Generate an app key:

```bash
php artisan key:generate
```

Fill the `.env` file with database information and run migrations (create database tables and their columns, indices etc.):

```bash
php artisan migrate:fresh
```
(could be without `:fresh` if database has not already been used)

Fill database tables with test (fake) data by running db seeders:

```bash
php artisan db:seed
```

Create Passport clients to enable user authentication:

```bash
php artisan passport:install
```

Create a symlink to provide public access to files:

```bash
php artisan storage:link
```

Server part is good to go now!

Install client (Svelte) part with all `package.json` dependencies (maybe better with `package-lock.json` file removed):

```bash
npm install
```

Run vite to server your assets/bundle:

```bash
npm run dev
```

Open a new terminal instance and serve the application:

```bash
php artisan serve
```

## Deployment

Build the app:

```bash
npm run build
```

Now all you have to do is serve the Laravel app in a production environment.
