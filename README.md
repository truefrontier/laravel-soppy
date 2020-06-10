# laravel-soggy

This is an opinionated Laravel frontend setup for using [vue-cli](https://cli.vuejs.org) with [vue-soggy](https://github.com/truefrontier/laravel-soggy) in a [Laravel](https://laravel.com/) project.

## How to set up

### Prerequisites

- [Laravel Installer](https://laravel.com/docs/7.x#installing-laravel) (recommended)
- [vue-cli](https://cli.vuejs.org/guide/installation.html)

### Setup

1. `$ laravel new my-project && cd my-project`
2. `$ mkdir resources/vue && cd resources/vue/`
3. `$ vue create app` - Add `vue-router` in history mode
4. Save [vue.config.js](https://github.com/truefrontier/laravel-soggy/blob/master/resources/vue/vue.config.js) to `/resources/vue/vue.config.js`
5. Save [SoggyMakeRoutes command](https://github.com/truefrontier/laravel-soggy/blob/master/app/Console/Commands/SoggyMakeRoutes.php) to `/app/Console/Commands/SoggyMakeRoutes.php`
6. Add the following scripts to your `package.json`

__package.json__
```
{
  ...
  "scripts": {
    ...
    "serve": "cd resources/vue/app && yarn serve",
    "prebuild": "php artisan soggy:make-routes",
    "build": "cd resources/vue/app && yarn build"
  },
}
```

7. Save [AppController](https://github.com/truefrontier/laravel-soggy/blob/master/app/Http/Controllers/AppController.php) to `/app/Http/Controllers/AppController.php`
8. Configure routes to use AppController and be seen by `php artisan soggy:make-routes` command

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

9. Finish the setup instructions for [vue-soggy](https://github.com/truefrontier/vue-soggy)

