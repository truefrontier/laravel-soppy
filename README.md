# laravel-soppy

This is an opinionated Laravel frontend setup for using [vue-cli](https://cli.vuejs.org) with [vue-soppy](https://github.com/truefrontier/vue-soppy) in a [Laravel](https://laravel.com/) project. You can even [run multiple vue-cli projects](https://github.com/truefrontier/laravel-soppy/blob/master/README.md#multiple-vue-cli-projects) (eg. `app` and `admin`). You can even [share tailwindcss config](https://github.com/truefrontier/laravel-soppy/blob/master/README.md#share-tailwindcss-assets-too) between the two!

## What and Why?
Check out [this article on Medium](https://medium.com/@kevinkirchner/a-ready-to-try-concept-in-response-to-second-guessing-the-modern-web-6946ec4d0598) to get a better understanding why vue-soppy exists.

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
See [vue.config.js](https://github.com/truefrontier/laravel-soppy/blob/master/resources/vue/app/vue.config.js) from this repo. Save it to `/resources/vue/app/vue.config.js`

---

#### 5. Create `soppy:make-routes` command
See [SoppyMakeRoutes.php](https://github.com/truefrontier/laravel-soppy/blob/master/app/Console/Commands/SoppyMakeRoutes.php) from this repo. Save it to `/app/Console/Commands/SoppyMakeRoutes.php`

---

#### 6. Add the following scripts to your `package.json`

__package.json__
```
{
  ...
  "scripts": {
    ...
    "routes": "php artisan soppy:make-routes"
    "preserve": "npm run routes",
    "serve": "cd resources/vue/app && yarn serve",
    "prebuild": "npm run routes",
    "build": "cd resources/vue/app && yarn build"
  },
}
```

---

#### 7. Add AppController
See [AppController.php](https://github.com/truefrontier/laravel-soppy/blob/master/app/Http/Controllers/AppController.php) from this repo. Save it to `/app/Http/Controllers/AppController.php`

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

__NOTE:__ The `->name('app.welcome')` is important here. The `php artisan soppy:make-routes` command looks for all the routes with a name that starts with `app.` so that [vue-soppy](https://github.com/truefrontier/vue-soppy) can use it for your routes in your vue app.

---

#### 9. Inject data for initial pageload
Add the following to `<head>` in `/resources/vue/app/public/index.html`

__index.html__
```
<script>
  window.SoppyState = <% if (NODE_ENV === 'production') { %>@json(array_merge($data, []))<% } else { %>{}<% } %>;
</script>
```

---

#### 10. Setup [vue-soppy](https://github.com/truefrontier/vue-soppy)
Complete the setup instructions for [vue-soppy](https://github.com/truefrontier/vue-soppy/blob/master/Readme.md#how-to-setup)

---


## Multiple vue-cli projects
Let's say you have an `app` vue-cli project and you also want an `admin` vue-cli project. It's really simple:

Start with Step 3 above, but now use `admin` (or whatever you prefer) instead of `app`

__Step 3__
- `$ cd my-project/resources/vue/`
- `$ vue create admin` - Again, with `vue-router` in history mode and `vuex`

__Step 4__
- Change `AREA` to `admin` and save to `/resources/vue/admin/vue.config.js`

__Step 5__
The `soppy:make-routes` command can take two params. By default, it runs:

```
soppy:make-routes --prefix=app --dest=resources/vue/app/src/router/routes.json
```

Now, see Step 6 👇🏾

__Step 6__
```
"routes:admin": "php artisan soppy:make-routes --prefix=admin"
"preserve:admin": "npm run routes:admin",
"serve:admin": "cd resources/vue/admin && yarn serve",
"prebuild:admin": "npm run routes:admin",
"build:admin": "cd resources/vue/admin && yarn build",
```

__Step 7__
Copy `AppController.php` and rename it `AdminController.php`

__Step 8__
Use `admin.` instead of `app.`; For example, `->name('admin.dashboard')`

__Step 9__
Add it to `resources/vue/admin/public/index.html` instead

__Step 10__
You may consider installing packages that will be used in both projects in `resources/vue/`.

```
$ cd my-project/resources/vue/
$ echo "{}" >> package.json
$ npm i --save vue-soggy
```

---

### Sharing components and assets between the two

#### 1. Create a shared directory
```
$ mkdir my-project/resources/vue/shared && cd my-project/resources/vue/
```

#### 2. Add alias in both `vue.config.js` files
__resources/vue/[app/admin]/vue.config.js__
```
modules.export = {
  // ...
  configureWebpack: {
    resolve: {
      alias: {
        '@@': path.join(__dirname, '../'),
      },
    },
  },
  // ...
}
```


#### 3. Import shared files from `@@/shared/your-file`

---

### Share Tailwindcss assets too

__resources/vue/tailwind.config.js__
```
module.exports = {
  purge: [
    // '../js/**/*.js',
    // '../js/**/*.vue',
    // '../sass/**/*.scss',
    // '../views/**/*.blade.php',
    './*/src/**/*.js',
    './*/src/**/*.vue',
    './*/src/**/*.scss',
    './*/public/**/*.html',
  ],
  theme: {
    extend: {},
  },
  variants: {},
  plugins: [],
};
```

__resources/vue/postcss.config.js__
```
const path = require('path');

module.exports = {
  plugins: [
    require('tailwindcss')(path.join(__dirname, './tailwind.config.js')),
    require('autoprefixer')(),
  ],
};
```

Set custom config path for postcss in `vue.config.js`

__resources/vue/[app/admin]/vue.config.js__
```
modules.export = {
  // ...
  css: {
    loaderOptions: {
      postcss: {
        config: {
          path: '../postcss.config.js',
        },
      },
    },
  },
  // ...
}
```

In each vue-cli project, you'll need to add to the `main.js` file:

__resources/vue/[app/admin]/src/main.js__
```
require('@@/shared/assets/scss/main.scss');
```

__resources/vue/shared/assets/scss/main.scss__
```
@tailwind base;
@tailwind components;
@tailwind utilities;
```

