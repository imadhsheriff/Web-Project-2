# TravelHub — Tourist Management and Tour Package Booking System

A student/demo website based on the supplied TravelHub milestone documentation and diagrams. It uses PHP + MySQL and includes destination photos, tour packages, tourist accounts, bookings, demo payments, reviews, and an administrator area.

## 1. Requirements
- XAMPP or WAMP with Apache, PHP 8+, and MySQL/MariaDB
- A browser
- Destination photos are bundled locally in assets/images, so they do not require an internet connection

## 2. Install in XAMPP (Windows)
1. Extract the ZIP file.
2. Copy the `TravelHub_Website` folder into `C:\xampp\htdocs\`.
3. Open the XAMPP Control Panel and start **Apache** and **MySQL**.
4. In your browser, open `http://localhost/phpmyadmin/`.
5. Select **Import** → **Choose File** → select `C:\xampp\htdocs\TravelHub_Website\database\travelhub.sql` → **Import**. This script creates the `travelhub` database and demo records. **Importing it again drops and recreates the tables, so do not re-import after you have saved real test data.**
6. Open `C:\xampp\htdocs\TravelHub_Website\includes\config.php`. The default settings are database `travelhub`, username `root`, and an empty password. If your MySQL password is different, update `$user` and `$pass` there.
7. Visit `http://localhost/TravelHub_Website/`.

## 3. Install in WAMP
1. Extract the ZIP.
2. Copy `TravelHub_Website` into your WAMP `www` folder (commonly `C:\wamp64\www\`).
3. Start WAMP services and wait until the WAMP icon is green.
4. Open `http://localhost/phpmyadmin/`, import `TravelHub_Website/database/travelhub.sql`, and confirm the database `travelhub` exists.
5. Check the credentials in `includes/config.php` if your MySQL setup uses a password.
6. Visit `http://localhost/TravelHub_Website/` (adjust the URL if your folder name differs).

## 4. Demo administrator login
- Email: `admin@travelhub.local`
- Password: `Admin123!`

Log in using the **email address**, not the username. Change the demo credentials before any public deployment.

## 5. Main features
- Home page with locally bundled Sri Lankan travel photography
- Browse/search destinations and tour packages (four package cards per row on desktop; responsive on smaller screens)
- Package details
- Tourist registration, login, logout, and profile management
- Booking form and booking history
- Demo payment flow and printable receipt
- Reviews and ratings
- Contact form
- Admin dashboard with booking status updates, package management, destination management, review moderation, registered tourist directory, payment records, and contact-message inbox

## 6. Database tables
The SQL schema includes `administrators`, `tourists`, `destinations`, `tour_packages`, `package_destination`, `activities`, `itinerary`, `bookings`, `booking_passengers`, `payments`, `reviews`, and `contact_messages`, reflecting the main entities in the supplied ER diagram and relational schema.

## 7. Troubleshooting
- **Database connection failed:** confirm MySQL is running, import the SQL file, and check `includes/config.php` database name/user/password.
- **Unknown database `travelhub`:** import `database/travelhub.sql` in phpMyAdmin.
- **Photos not loading:** the site uses remote Unsplash image URLs; check your internet connection.
- **Admin login not working:** import the SQL file on a fresh database and use the exact demo email and password listed above. Do not type the username in the email field.
- **404 Not Found:** check that the folder is directly inside `htdocs` (XAMPP) or `www` (WAMP), and that the URL folder name matches.

## Important academic-demo notes
- Payment is simulated only. No real money is transferred; do not enter real card or bank details.
- The website is for local academic demonstration. Before public deployment, add CSRF protection, stronger authorization/security review, HTTPS, backups, and proper payment-provider integration.
- This SQL file resets the tables to seed data when imported. Back up your database before re-importing.


## Uploaded Sri Lankan travel photos
All photos extracted from the supplied `trvalhub(1).rar` archive are included in `assets/images/` as optimized local JPEGs. The seeded destination and package records use local image paths, so these photos do not depend on remote image hosts. The home page displays four destination cards and four tour-package cards on wide screens.

## Database import safety
Export/backup your existing `travelhub` database before importing `database/travelhub.sql`. The script rebuilds the demo tables and replaces existing data. It drops child tables before parent tables and disables foreign-key checks during the reset.


## Troubleshooting update (session / foreign key errors)

- The current version stores tourist sessions in `tourist_id` and administrator sessions separately in `admin_id`.
- After replacing the files, log out once and log in again. This clears stale sessions from earlier versions.
- Booking, payment, and profile pages require a valid tourist record. Administrator accounts cannot create tourist bookings or reviews.
- Import `database/travelhub.sql` only after exporting a backup if the existing `travelhub` database contains data you need. The SQL script rebuilds the demo tables and sample data.
- Demo administrator: `admin@travelhub.local` / `Admin123!`.
- Local WAMP URL: `http://localhost/TravelHub_Website/` (use your actual extracted folder name if different).


## Final design/data update
- Includes 12 destinations and 12 tour packages: Sri Lankan destinations plus Bali, Maldives, Dubai, Singapore, Phuket and Paris.
- Home page displays all 12 destinations in a four-column desktop grid and all 12 active packages.
- Destination cards use a four-column desktop grid and responsive tablet/mobile layouts.
- Added a TravelHub SVG brand logo and a trip-planning enquiry form.
- Some international destination photos load from Unsplash and therefore need an internet connection. Local Sri Lankan photos are bundled in `assets/images`.
- Import `database/travelhub.sql` into phpMyAdmin to create a clean database. This script drops and recreates the TravelHub tables, so back up any bookings/accounts you need before importing.


## Visual refresh (13 October 2026)
- Added a responsive photo collage below “Journeys made memorable”; the collage has no price labels.
- Kept tour package cards in a separate section, with prices shown only on package cards.
- Registration and login pages now use a wide two-column photo-backed layout on desktop, with stacked responsive layout on mobile.
- Widened the shared content container and refined spacing, image overlays, card hover states, and form backgrounds.
- Photos in the collage and form visual panels are bundled locally; international destination thumbnails use the seeded image URLs and may require internet access.


## Visual review update (13 October 2026)
- Replaced the home-page heading “Find your next favourite trip” with “Handpicked journeys for you”.
- Added page-specific photo-backed hero backgrounds, softly photo-backed form panels, and a more consistent logo in the footer.
- Refined shared navigation spacing and administrator dashboard styling.
- International package/destination photos in the supplied database use remote image URLs and require internet access; bundled local images provide fallbacks in some cards.
- PHP syntax and ZIP integrity checks were run after these changes. A live WAMP/MySQL browser test is still required on the target computer.


## Administrator workspace update (13 October 2026)
- Added dedicated admin pages for booking management, registered tourists, payment records, and contact messages.
- Added a shared admin navigation menu so all admin pages link to each other consistently.
- Admin can update booking/payment statuses, toggle package visibility, review customer accounts, and manage local contact/review records.
- Payment tools update demonstration records only; no real payment gateway is connected.
- Admin login: `admin@travelhub.local` / `Admin123!` (demo only).
- Use the existing `travelhub` database. No additional SQL tables are required for these admin pages.
