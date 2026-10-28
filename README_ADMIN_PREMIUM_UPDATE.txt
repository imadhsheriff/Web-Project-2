TravelHub Admin Premium Update
================================

This package keeps the existing PHP/database-driven admin features and applies one shared premium visual layout to all admin pages:
- Overview
- Bookings
- Tour packages
- Destinations
- Tourists
- Payments
- Reviews
- Contact messages

Demo data:
- `database/travelhub.sql` includes clearly labelled demonstration rows for a fresh install.
- `database/travelhub_demo_data.sql` adds the sample records to an existing `travelhub` database and is safe to import more than once.
- Demo tourists use emails under `@travelhub.local`; sample bookings include a DEMO RECORD note; payment references begin `DEMO-`; reviews and enquiries are clearly marked DEMO.
- These records are illustrative only. They are not real customers or actual payment transactions.
- New real records continue to be read from the same database tables by the existing admin queries.

IMPORTANT for an existing WAMP project:
1. Back up your current website folder and database first.
2. Replace your project files with this ZIP's `TravelHub_Website` folder.
3. If your existing database already contains real data, DO NOT import `database/travelhub.sql` because it recreates the schema and resets tables.
4. In phpMyAdmin, select the existing `travelhub` database, then import `database/travelhub_demo_data.sql` to add sample records without resetting existing tables.
5. Open the admin pages and press Ctrl+F5 to refresh cached CSS.
6. The site still needs a functioning local MySQL database and the correct settings in `includes/config.php`.

Admin credentials for a fresh database are documented in `database/travelhub.sql`:
admin@travelhub.local / Admin123!
