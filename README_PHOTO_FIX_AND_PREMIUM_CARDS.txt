TravelHub — Photo Display & Premium Cards Update
================================================

Updates in this ZIP:
- Fixed admin destination thumbnails when the database stores local paths such as assets/images/yala-leopard.jpg. Admin pages now correctly resolve them through ../assets/images/....
- Added safe image fallbacks so a failed remote image does not leave a broken-image icon. Foreign destination image URLs continue to load when internet access is available.
- Admin Destinations accepts an existing local photo path or a remote image URL. Example local path: assets/images/mirissa-beach.jpg.
- Admin Tour Packages accepts local image paths and shows premium photo thumbnails in its management table.
- Customer dashboard includes a premium “Explore beautiful places” photo-card strip.
- Public package detail and customer/public cards retain image fallbacks and premium card styling.

Important:
1. Back up the current website folder and database first.
2. Replace the existing website files with this ZIP.
3. Keep your current database if it contains your real data. Do not re-import the full schema just for this visual update.
4. Ensure local photos remain in TravelHub_Website/assets/images/.
5. Foreign destination URLs require internet. If offline, the site shows the local tropical-coast fallback instead of a broken image.
6. After copying files, open pages in WAMP and press Ctrl+F5.
