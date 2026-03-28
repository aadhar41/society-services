---
description: Client Demo Workflow for Multi-Society & License System
---

# Client Demo Workflow

Follow these steps to prepare and execute a successful client demo for the SAMS Multi-Society and License system.

## 1. Environment Preparation

// turbo
### Sync Database
Run the latest migrations and seed the default license tiers.
```powershell
php artisan migrate
php artisan db:seed --class=LicenseSeeder
```

## 2. Test Account Configuration

### Create/Configure Demo User
Assign a **Pro** license to a demo account to demonstrate multi-society capabilities (limit of 5).
```powershell
php artisan tinker --execute="App\Models\User::where('email', 'dezuh@mailinator.com')->update(['license_id' => App\Models\License::where('slug', 'pro')->first()->id])"
```

### Reset Demo Password
Ensure the password is known and easy to use.
```powershell
php artisan tinker --execute="App\Models\User::where('email', 'dezuh@mailinator.com')->update(['password' => Illuminate\Support\Facades\Hash::make('password')])"
```

## 3. Demo Data Creation

### Create Multiple Societies
Add at least 2-3 societies for the demo user to show the switcher in action.
```powershell
php artisan tinker --execute="
$user = App\Models\User::where('email', 'dezuh@mailinator.com')->first();
$s1 = App\Domain\Society\Models\Society::create(['name' => 'Paradise Greens', 'address_line_1' => 'Sector 1']);
$s2 = App\Domain\Society\Models\Society::create(['name' => 'Skyline Heights', 'address_line_1' => 'Sector 2']);
$user->societies()->attach([$s1->id, $s2->id]);
"
```

## 4. Frontend Demonstration Steps

### Login & Society Switching
1.  Login with `dezuh@mailinator.com` / `password`.
2.  Observe the **Society Switcher** dropdown in the Navbar.
3.  Switch between "Paradise Greens" and "Skyline Heights".
4.  Verify that the dashboard data updates based on the selected society.

### License Enforcement
1.  Try to create a 6th society (if more than 5 exist/tried).
2.  Show the `403 Forbidden` error: "Society limit exceeded for your current license."

### Super Admin Bypass
1.  Login with a Super Admin account.
2.  Demonstrate that the "Create Society" button is always available, regardless of limits.

## 5. Cleanup (Post-Demo)

// turbo
### Reset Demo State
```powershell
php artisan migrate:refresh --seed
```
