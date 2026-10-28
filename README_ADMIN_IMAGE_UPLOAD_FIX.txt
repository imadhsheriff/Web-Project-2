Travel Hub admin correction
===========================

Changes limited to the requested admin workflow:
1. Admin > Tour Packages: adding a package saves it to the database and redirects back to the Existing packages table, where the new package is listed.
2. Admin > Destinations: adding a destination saves it to the database and redirects back to the Destinations list, where the new destination is shown.
3. Removed the Photo URL / image-path text fields from both forms. Admins now choose an image file directly from their computer.
4. Supported upload formats: JPG/JPEG, PNG, WEBP. Maximum accepted file size in application code: 8 MB.
5. Uploaded photos are stored in assets/images/uploads and their relative paths are stored in the existing database image_url column. No database schema change is required.

Important:
- Existing database records and other site features are retained.
- If PHP/WAMP's upload_max_filesize or post_max_size is lower than the selected file size, reduce the image size or adjust those PHP settings.
- Back up your current website folder before replacing it with this archive.
