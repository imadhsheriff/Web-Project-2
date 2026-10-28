TravelHub Premium International Photos Update
=============================================

This update is based on the uploaded TravelHub website ZIP and the provided visual reference.

What changed:
- Added eight bundled international destination photos: Bali, Maldives, Dubai, Singapore, Paris, Rome, Santorini, and the Swiss Alps.
- Updated destination and package image rendering to choose a bundled local image by destination/package name. This avoids broken external image URLs on the public Destinations page, Tour Packages, package details, Customer Dashboard, Admin Destinations, and Admin Packages.
- Kept the existing Sri Lankan destination photos and added consistent premium card styling, hover effects, image crops, form controls, tables, and dashboard cards.
- Updated fresh-install SQL seed references to use bundled local image files rather than fragile external URLs.
- Existing database records do not need to be re-imported: the PHP image helper resolves their image by destination/package name.

Install:
1. Back up the existing website folder and database.
2. Extract the ZIP.
3. Copy the contents of `TravelHub_Website` into your WAMP `www/TravelHub_Website` folder (or your matching project folder).
4. Keep `assets/images/` intact; the new destination photos are stored there.
5. Open the website and press Ctrl+F5.

Important:
- Do not re-import `database/travelhub.sql` over an existing database with real data; it drops/recreates tables.
- Existing database data is preserved by this visual update.
- PHP syntax was checked on all 30 PHP files. ZIP integrity was checked.
- The website was not launched in a live WAMP/browser session in this environment, so verify database connection and user/admin flows locally.
