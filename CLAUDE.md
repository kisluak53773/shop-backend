# Book store backend

- Laravel app, organized by domain: `app/Cart`, `app/Category`, `app/Order`, `app/Product`, `app/User`, plus `app/Filament` for the admin panel.
- Business logic lives in Services (e.g. `app/Order/Services/OrderService.php`); keep controllers thin.
- Multi-step order/cart/stock changes must run inside `DB::transaction` and guard against race conditions.
- Validate input with FormRequests; authorize with policies/gates.
- Avoid N+1 queries; eager load relations.
- Code style: php-cs-fixer (`.php-cs-fixer.dist.php`).
- Tests: `php artisan test`.
