# laravel-soggy

This is an opinionated Laravel frontend setup for using [vue-cli](https://cli.vuejs.org) with [vue-soggy](https://github.com/truefrontier/vue-soggy) in a [Laravel](https://laravel.com/) project.

## How to setup

### Prerequisites

- [Laravel Installer](https://laravel.com/docs/7.x#installing-laravel) (optional, but recommended)
- [vue-cli](https://cli.vuejs.org/guide/installation.html)

### Setup

#### 1. Create your laravel project
`$ laravel new my-project && cd my-project`

---

#### 2. Create the directory to hold your vue-cli app
`$ mkdir resources/vue && cd resources/vue/`

---

#### 3. Create your vue-cli app
`$ vue create app` - Be sure to add `vue-router` in history mode

---

#### 4. Add vue.config.js
See [vue.config.js](https://github.com/truefrontier/laravel-soggy/blob/master/resources/vue/app/vue.config.js) from this repo. Save it to `/resources/vue/app/vue.config.js`

---

#### 5. 
See [SoggyMakeRoutes.php](https://github.com/truefrontier/laravel-soggy/blob/master/app/Console/Commands/SoggyMakeRoutes.php) from this repo. Save it to `/app/Console/Commands/SoggyMakeRoutes.php`

---

#### 6. Add the following scripts to your `package.json`

__package.json__
```
{
  ...
  "scripts": {
    ...
    "preserve": "php artisan soggy:make-routes",
    "serve": "cd resources/vue/app && yarn serve",
    "prebuild": "php artisan soggy:make-routes",
    "build": "cd resources/vue/app && yarn build"
  },
}
```

---

#### 7. Add AppController
See [AppController.php](https://github.com/truefrontier/laravel-soggy/blob/master/app/Http/Controllers/AppController.php) from this repo. Save it to `/app/Http/Controllers/AppController.php`

---

#### 8. Configure your routes

__routes/web.php__
```
Route::get('/', 'AppController@welcome')->name('app.welcome');
```

Or you can group your `app` routes like this:
```
Route::group(['name' => 'app.'], function () {
  Route::get('/', 'AppController@welcome')->name('welcome');
  // Add more `app.` routes here...
});
```

__NOTE:__ The `->name('app.welcome')` is important here. The `php artisan soggy:make-routes --prefix=app --dest=resources/vue/app/src/router/routes.json` command looks for all the routes with a name that starts with `app.` (or whatever you pass as the `--prefix` and save a `routes.json` so that [vue-soggy](https://github.com/truefrontier/vue-soggy) can use it for your routes in your vue app.

---

#### 9. Inject data for initial pageload
Add the following to `<head>` in `/resources/vue/app/public/index.html`

__index.html__
```
<script>
  window.SoggyState = <% if (NODE_ENV === 'production') { %>@json(array_merge($data, []))<% } else { %>{}<% } %>;
</script>
```

---

#### 10. Setup [vue-soggy](https://github.com/truefrontier/vue-soggy)
Complete the setup instructions for [vue-soggy](https://github.com/truefrontier/vue-soggy/blob/master/Readme.md#how-to-setup)

