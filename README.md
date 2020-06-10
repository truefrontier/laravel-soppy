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
`$ vue create app` - Be sure to add `vue-router` in history mode and `vuex`

---

#### 4. Add vue.config.js
See [vue.config.js](https://github.com/truefrontier/laravel-soggy/blob/master/resources/vue/app/vue.config.js) from this repo. Save it to `/resources/vue/app/vue.config.js`

---

#### 5. Create `soggy:make-routes` command
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

__NOTE:__ The important part is to return JSON when the request wants JSON and the view when it doesn't, like this:

```
public function someRouteAction(Request $request) {
  $data = [
    // Whatever date you want to be either injected on page load or return as JSON
  ];
  return $request->wantsJson() ? $data : view('app', ['data' => $data]);
}
```

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

__NOTE:__ The `->name('app.welcome')` is important here. The `php artisan soggy:make-routes` command looks for all the routes with a name that starts with `app.` so that [vue-soggy](https://github.com/truefrontier/vue-soggy) can use it for your routes in your vue app.

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

---


### Multiple vue-cli projects
Let's say you have an `app` vue-cli project and you also want an `admin` vue-cli project. It's really simple:

#### 1. Do steps above again, but with `admin` instead of `app`

__Step 3__
- `$ cd my-project/resources/vue/`
- `$ vue create admin` - Again, with `vue-router` in history mode and `vuex`

__Step 4__
- Change `AREA` to `admin` and save to `/resources/vue/admin/vue.config.js`

__Step 5__
The `soggy:make-routes` command can take two params. By default, it runs:

```
soggy:make-routes --prefix=app --dest=resources/vue/app/src/router/routes.json
```

Now, see Step 6 👇🏾

__Step 6__
```
"preserve:admin": "php artisan soggy:make-routes --prefix=admin --dest=resources/vue/admin/src/router/routes.json",
"serve:admin": "cd resources/vue/admin && yarn serve",
"prebuild:admin": "php artisan soggy:make-routes --prefix=admin --dest=resources/vue/admin/src/router/routes.json",
"build:admin": "cd resources/vue/admin && yarn build",
```

__Step 7__
Copy `AppController.php` and rename it `AdminController.php`

__Step 8__
Use `admin.` instead of `app.`; For example, `->name('admin.dashboard')`

__Step 9__
Add it to `resources/vue/admin/public/index.html` instead
