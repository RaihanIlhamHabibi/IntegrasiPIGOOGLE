 BDA System - Laravel Google Drive & Calendar Integration

A professional Laravel website with seamless Google Drive and Google Calendar integration. Upload files directly to Google Drive and manage calendar events — all automatically shared to every registered member.

## What's New (v2.0.0)

- 🔄 **Broadcast System** - Events and files automatically distributed to all connected users
- 🔐 **Google-Only Auth** - Register and login exclusively via Google OAuth
- 📅 **Google Meet Integration** - Auto-generate Meet links when creating calendar events
- 🎨 **Redesigned UI** - Clean card-based interface for Drive and Calendar

## Features

- ✅ **Google OAuth 2.0 Authentication** - Register and login via Google only
- ✅ **Auto-Broadcast Calendar Events** - Events created by any user are automatically inserted into all registered users' Google Calendars
- ✅ **Auto-Broadcast Drive Files** - Files uploaded by any user are automatically copied to all registered users' Google Drive
- ✅ **Google Meet Integration** - Auto-generate Google Meet links when creating events
- ✅ **Google Drive Integration** - Upload, list, and delete files from Google Drive
- ✅ **Google Calendar Integration** - Create, edit, and delete calendar events
- ✅ **Beautiful UI** - Clean card-based responsive design with Sora font
- ✅ **Database Storage** - Track all files and events per user in database
- ✅ **Token Management** - Automatic token refresh and expiration handling
- ✅ **Smart Filtering** - Only users with connected Google accounts receive broadcasts

## How Broadcast Works
User A creates event / uploads file
↓
System queries all registered users with Google token
↓
Loop each user → insert event/file using their own OAuth token
↓
User B, C, D → receive event/file in their own Google account ✅
Users without Google token → skipped with warning log ⚠️
Unregistered users → never queried ❌

**Calendar:** Creator (User A) gets the event + all other connected users get it too
**Drive:** ALL connected users including the uploader get the file

## Requirements

- PHP 8.2+
- Laravel 13.x
- MySQL/MariaDB
- Composer
- Google Developer Account
- Node.js (for frontend assets)

## Installation

### 1. Clone and Install Dependencies

```bash
cd "c:\Integrasi API"
composer install
npm install
```

> Jalankan `composer update` setelah clone agar paket `google/apiclient` dan `laravel/socialite` terpasang (wajib untuk integrasi Google).

### 2. Environment Configuration

Copy the `.env.example` to `.env`:
```bash
cp .env.example .env
```

Update your database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_bda
DB_USERNAME=root
DB_PASSWORD=
```

> If you get an authentication error like `auth_gssapi_client` or `unknown authentication method`, your MySQL user account is using a plugin that the PHP MySQL driver does not support.
> Use `database/mysql-auth-fix.sql` to reset the authentication method to `mysql_native_password` or create a dedicated MySQL user.

### 3. Google OAuth Setup

1. Go to [Google Cloud Console](https://console.cloud.google.com)
2. Create a new project named "BDA System"
3. Enable the following APIs:
   - Google Drive API
   - Google Calendar API

4. Create OAuth 2.0 credentials (Web application):
   - Add authorized JavaScript origins: `http://localhost:8000`
   - Add authorized redirect URIs: `http://localhost:8000/auth/google/callback`

5. Copy your Client ID and Client Secret to `.env`:

```env
GOOGLE_CLIENT_ID=your_client_id_here
GOOGLE_CLIENT_SECRET=your_client_secret_here
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
GOOGLE_API_KEY=your_api_key_here
GOOGLE_DRIVE_FOLDER_ID=your_optional_drive_folder_id
```

### 4. Database Migration

Generate an application key:
```bash
php artisan key:generate
```

Run migrations:
```bash
php artisan migrate
```

### 5. Build Frontend Assets

```bash
npm run build
```

Or for development with hot reload:
```bash
npm run dev
```

## Running the Application

Start the Laravel development server:
```bash
php artisan serve
```

The application will be available at: `http://localhost:8000`

## Usage

### 1. Authentication

- Visit the home page → click **"Masuk dengan Google"** or **"Daftar sekarang"**
- Both register and login go through Google OAuth — no email/password required
- Authorize the application to access your Google Drive and Calendar
- You'll be logged in and redirected to the Dashboard automatically

### 2. Google Drive

- Navigate to **"Google Drive"** section
- Click **"Upload file"** to upload any file (all formats supported, max 100MB)
- The file is automatically uploaded to **every connected user's** Google Drive
- View all uploaded files as cards with file type icons
- Delete files directly from the application

### 3. Google Calendar

- Navigate to **"Google Calendar"** section
- Click **"Buat acara"** to create a new calendar event
- Toggle **Google Meet** to auto-generate a meeting link
- The event is automatically inserted into **every connected user's** Google Calendar
- Edit or delete events using the action buttons on each card

## Project Structure
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── GoogleAuthController.php
│   │       ├── GoogleDriveController.php
│   │       ├── GoogleCalendarController.php
│   │       └── AuthController.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── GoogleToken.php
│   │   ├── GoogleDriveFile.php
│   │   └── GoogleCalendarEvent.php
│   └── Services/
│       ├── GoogleDriveService.php
│       ├── GoogleCalendarService.php
│       └── Concerns/
│           └── ManagesGoogleClient.php
├── database/
│   └── migrations/
│       ├── create_users_table.php
│       ├── create_google_tokens_table.php
│       ├── create_google_drive_files_table.php
│       └── create_google_calendar_events_table.php
├── resources/
│   └── views/
│       ├── layouts/app.blade.php
│       ├── auth/
│       │   ├── login.blade.php
│       │   └── register.blade.php
│       ├── google-drive/
│       │   └── index.blade.php
│       └── google-calendar/
│           ├── index.blade.php
│           ├── create.blade.php
│           └── edit.blade.php
├── routes/
│   └── web.php
└── config/
└── services.php

## Database Schema

### Users Table
- id, name, email, password, email_verified_at, remember_token, timestamps

### Google Tokens Table
- id, user_id, google_id, access_token, refresh_token, expires_at, token_type, scope, timestamps

### Google Drive Files Table
- id, user_id, google_file_id, file_name, file_path, mime_type, file_size, web_view_link, google_drive_folder_id, description, timestamps

### Google Calendar Events Table
- id, user_id, google_event_id, event_title, event_description, event_start, event_end, location, calendar_id, hangout_link, status, all_day, timestamps

## API Endpoints

### Authentication
- `GET /auth/google` - Redirect to Google OAuth
- `GET /auth/google/callback` - Google OAuth callback
- `POST /logout` - Logout user

### Google Drive
- `GET /google-drive` - List all files
- `POST /google-drive/upload` - Upload new file (broadcast to all users)
- `DELETE /google-drive/files/{id}` - Delete file
- `GET /google-drive/list` - API endpoint for file list

### Google Calendar
- `GET /google-calendar` - List all events
- `GET /google-calendar/create` - Show create event form
- `POST /google-calendar/store` - Create new event (broadcast to all users)
- `GET /google-calendar/{id}/edit` - Show edit event form
- `PUT /google-calendar/{id}` - Update event
- `DELETE /google-calendar/{id}` - Delete event
- `GET /google-calendar/list` - API endpoint for event list

## Error Handling

The application includes comprehensive error handling:
- Invalid Google tokens are automatically refreshed via `ManagesGoogleClient` trait
- If one user's token fails during broadcast → skipped with warning log, others continue
- Failed API calls are logged to `storage/logs/laravel.log`
- User-friendly error messages are displayed on all pages

## Security Notes

- All Google API requests use OAuth 2.0 with automatic token refresh
- Register and login exclusively via Google — no passwords stored by users
- CSRF protection is enabled on all forms
- Database queries use prepared statements to prevent SQL injection
- File uploads are validated and sanitized
- Only registered users with valid Google tokens receive broadcasts

## Troubleshooting

### Issue: "Could not resolve host: repo.packagist.org"
This is a network connectivity issue. Ensure you have internet connection or:
```bash
COMPOSER_DISABLE_NETWORK=1 composer install
```

### Issue: "SQLite driver not found"
Switch to MySQL in your `.env` file as shown in the installation steps.

### Issue: "Google API returns 401 Unauthorized"
Your access token has expired. Try logging out and logging back in to refresh the token.

### Issue: "File upload fails"
- Ensure the `storage/app` directory has write permissions
- Check the maximum file upload size in your `php.ini`

### Issue: "Event/file not received by other users"
- Ensure the other users have logged in via Google OAuth at least once
- Check `storage/logs/laravel.log` for warning messages about skipped users
- Users without `googleToken` in DB will always be skipped

## Contributing

Feel free to submit issues and enhancement requests!

## License

This project is open-sourced software licensed under the MIT license.

## Support

For support and questions, please refer to:
- [Laravel Documentation](https://laravel.com/docs)
- [Google Drive API Docs](https://developers.google.com/drive/api)
- [Google Calendar API Docs](https://developers.google.com/calendar)

## Version

**BDA System v2.0.0** - May 2026

### Changelog
- **v2.0.0** - Broadcast system, Google-only auth, Google Meet integration, redesigned UI
- **v1.0.0** - Initial release with basic Drive & Calendar integration

---

**Created with ❤️ for seamless cloud integration**