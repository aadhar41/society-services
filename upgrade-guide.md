# Task: Society Accounting Upgrade (Laravel 12 + Vite)

Upgrade the legacy Laravel 8 project to the latest stable versions of Laravel, PHP, and modern tooling (Vite).

## Sub-tasks
- [ ] Research & Analysis [/]
  - [x] Check current dependencies (Laravel 8.x, PHP 7.3+)
  - [x] Check system environment (PHP 8.3 found)
  - [ ] Identify breaking changes in third-party packages [/]
- [ ] Backend Upgrade (Laravel 8 -> 12)
  - [ ] Update [composer.json](file:///d:/wamp64_2/www/society-accounting/composer.json) requirements [ ]
  - [ ] Update Middleware and Kernel configuration [ ]
  - [ ] Swap `fruitcake/laravel-cors` for built-in CORS [ ]
  - [ ] Swap `facade/ignition` for `spatie/laravel-ignition` [ ]
  - [ ] Run `composer update` [ ]
- [ ] Frontend Upgrade (Mix -> Vite)
  - [ ] Create [vite.config.js](file:///d:/wamp64_2/www/portfolio/portfolio-backend/vite.config.js) [ ]
  - [ ] Update [package.json](file:///d:/wamp64_2/www/society-accounting/package.json) dependencies and scripts [ ]
  - [ ] Update Layout files to use `@vite` instead of `mix()` [ ]
  - [ ] Remove [webpack.mix.js](file:///d:/wamp64_2/www/society-accounting/webpack.mix.js) [ ]
  - [ ] Run `npm install` and `npm run build` [ ]
- [ ] Verification & Fixes
  - [ ] Run basic smoke tests (Home page, Login) [ ]
  - [ ] Check `yajra/laravel-datatables` compatibility [ ]
  - [ ] Fix any remaining breaking changes [ ]
