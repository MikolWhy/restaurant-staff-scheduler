# Restaurant Staff Scheduling System

A full-stack web application for restaurant managers to manage staff members and schedule shifts.


## Tech Stack

| Layer | Technology | Version |
|-------|------------|---------|
| Backend | Laravel | 12.53.0 |
| Backend | PHP | 8.3 |
| Database | MySQL | 8.0 |
| Frontend | React | 19.2.4 |
| Frontend | TypeScript | 5.x |
| Frontend | Vite | 7.x |
| Infrastructure | Docker Compose | 2.x |

## Features

- Create and list staff members (name, role, phone number)
- Create and list shifts (day, start time, end time, role)
- Assign/reassign staff to shifts
- Staff dropdown filters by matching role
- Form validation with error messages
- Responsive design (mobile + desktop)

## Using the App

Once running, open **http://localhost:5173** in your browser.

### Staff

Use the **Staff** tab to view all staff members and add new ones. Field constraints:

| Field | Constraint |
|---|---|
| Name | Letters, spaces, hyphens, and apostrophes only |
| Role | Must be one of: Server, Cook, Manager |
| Phone | 10–15 digits, numbers only |

### Shifts

Use the **Shifts** tab to create shifts and assign staff to them.

- **Day** — must be today or a future date; past dates are rejected
- **Start / End Time** — must be valid times; end time must be after start time
- **Role Needed** — determines which staff appear in the assignment dropdown; only staff with a matching role are shown
- **Assign To** — optional; a shift can be saved unassigned and assigned later via the dropdown in the shift list

### Date & Time Input Browser Differences

The date and time fields use native browser inputs (`type="date"` / `type="time"`), so their appearance varies:

| Browser | Behaviour |
|---|---|
| Chrome (Windows/Linux) PREFERRED | Segement Scroller - click anywhere on the field (hour, minute) then scroll |

| Firefox | Free-text field — click directly in `HH:MM` format or on specific segements (hours/minutes/years/etc) to type directly |


You can always type directly into the Chrome spinner by clicking the hours segment and typing the digits, then Tab to minutes.

## Quick Start

### Prerequisites

- Docker Desktop (running)
- Node.js 18+ and npm

### Setup

```bash
# 1. Clone repository
git clone https://github.com/MikolWhy/restaurant-staff-scheduler.git
cd restaurant-staff-scheduler
```

```bash
# 2. Create your .env file from the example template
cp .env.example .env        # Mac/Linux
copy .env.example .env      # Windows (Command Prompt)
```

Open `.env` and update the database section to match the Docker MySQL service.
Replace the default SQLite block with:

```env
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=restaurant_scheduler
DB_USERNAME=laravel
DB_PASSWORD=secret
```

> `DB_HOST=db` refers to the MySQL container name defined in `docker-compose.yml`, not `localhost`.

```bash
# 3. Start backend containers (PHP, MySQL, Nginx)
docker compose up -d --build
```

```bash
# 4. Install PHP dependencies (Composer) inside the app container
docker compose exec app composer install
```

```bash
# 5. Generate the Laravel application key (populates APP_KEY in .env)
docker compose exec app php artisan key:generate
```

```bash
# 6. Set write permissions on Laravel's storage and cache directories (Linux/Mac only)
docker compose exec app chmod -R 775 storage bootstrap/cache
```

```bash
# 7. Run database migrations and seed sample data
#    Wait ~10 seconds after step 3 for MySQL to finish initializing before running this.
#    Remove --seed to start with an empty database.
docker compose exec app php artisan migrate --seed
```

```bash
# 8. Install and start the frontend
#    Open a new terminal window first — npm run dev is a blocking process.
cd frontend
npm install
npm run dev
```

### Access

| Service | URL |
|---------|-----|
| Frontend | http://localhost:5173 |
| Backend API | http://localhost:8000/api |

## API Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | /api/staff | List all staff members |
| POST | /api/staff | Create a staff member |
| GET | /api/staff/{id} | Get a single staff member with their shifts |
| DELETE | /api/staff/{id} | Delete a staff member (their shifts become unassigned) |
| GET | /api/shifts | List all shifts (includes assigned staff) |
| POST | /api/shifts | Create a shift |
| PATCH | /api/shifts/{id} | Assign/unassign staff to a shift |


## Useful Commands

### Testing
```bash
# Run all tests (Feature + Unit)
docker compose exec app php artisan test

# Run specific test file
docker compose exec app php artisan test --filter=StaffApiTest
docker compose exec app php artisan test --filter=ShiftApiTest
docker compose exec app php artisan test --filter=StoreStaffRequestTest
```

### Database
```bash
# Reset database with fresh sample data
docker compose exec app php artisan migrate:fresh --seed

# Run migrations only
docker compose exec app php artisan migrate

# Run seeder only
docker compose exec app php artisan db:seed
```

### Docker
```bash
# Start containers
docker compose up -d --build

# Stop containers
docker compose down

# Stop and delete database volume
docker compose down -v

# View logs
docker compose logs -f
```

### Debugging
```bash
# Laravel interactive shell
docker compose exec app php artisan tinker

# View registered routes
docker compose exec app php artisan route:list

# Clear all caches
docker compose exec app php artisan optimize:clear
```

## Project Structure
```
restaurant-staff-scheduler/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── StaffController.php
│   │   │   └── ShiftController.php
│   │   └── Requests/
│   │       ├── StoreStaffRequest.php
│   │       ├── StoreShiftRequest.php
│   │       └── UpdateShiftRequest.php
│   └── Models/
│       ├── Staff.php
│       └── Shift.php
├── database/
│   ├── migrations/
│   │   ├── xxxx_create_staff_table.php
│   │   └── xxxx_create_shifts_table.php
│   └── seeders/
│       └── DatabaseSeeder.php
├── routes/
│   └── api.php
├── tests/
│   ├── Feature/
│   │   ├── StaffApiTest.php
│   │   └── ShiftApiTest.php
│   └── Unit/
│       └── StoreStaffRequestTest.php
├── frontend/
│   ├── src/
│   │   ├── components/
│   │   │   ├── StaffList.tsx
│   │   │   ├── StaffForm.tsx
│   │   │   ├── ShiftList.tsx
│   │   │   └── ShiftForm.tsx
│   │   ├── api.ts
│   │   ├── types.ts
│   │   ├── App.tsx
│   │   └── App.css
│   ├── package.json
│   └── vite.config.ts
├── docker-compose.yml
├── Dockerfile
└── README.md
```

## Validation Rules

### Staff
| Field | Rules |
|-------|-------|
| name | Required, letters/spaces/hyphens/apostrophes only |
| role | Required, must be: server, cook, or manager |
| phone_number | Required, 10–15 digits only |

### Shift
| Field | Rules |
|-------|-------|
| day | Required, cannot be in the past |
| start_time | Required, HH:MM format |
| end_time | Required, must be after start_time |
| role | Required, must be: server, cook, or manager |
| staff_id | Optional, must exist in staff table if provided |

## Assumptions & Tradeoffs

### Assumptions
- No overnight Shifts (eg 11pm - 2am) but if need easily enabled by modifying a business rule.
- Users will know how to operate the software (no specific instructions)
- Single timezone operation
- Phone Formats of Minimum 10, Max 15 for international numbers
- Staff role should match shift role (enforced in UI dropdown filter)
- Shifts can exist unassigned (open shifts needing coverage)

### Tradeoffs
| Decision | Rationale |
|----------|-----------|
| No edit/delete | Not specified in requirements |
| No authentication | Explicitly out of scope per assignment |
| Frontend validation only for UX | Backend validates for security; frontend catches most errors first |
| Native date/time inputs | Simple, no extra dependencies; small click target is browser limitation |
| No pagination | Acceptable for expected data size |

### What I'd Add With More Time
- Edit and delete functionality for staff and shifts (instead of relying on commands)
- Backend validation that checks staff role matches shift role on assignment (the frontend dropdown already filters by role, but the API itself does not reject a mismatched `staff_id`) if you were to call directly via CURL
- Scheduling conflict detection (overlapping shifts for same staff)
- Date range filtering for shifts view
- Information Page including some of the browser differences or fix to make it consistent (eg: Currently Chrome/Edge should allow the click/scroll inputs while FireFox etc may require click/type for inputs.)

## Development Notes

- Used Laravel Form Requests for validation (clean separation from controllers)
- Used Eloquent relationships (Staff hasMany Shifts, Shift belongsTo Staff)
- Used eager loading (`Shift::with('staff')`) to avoid N+1 queries
- Frontend uses controlled components with React useState
- API errors parsed from Laravel's `error.errors` format for field-specific messages
- Docker setup uses PHP-FPM + nginx + MySQL (production-like architecture)

## Time Spent

Approximately 6 hours spaced over 1.5 days:
- Preparation before starting the project: PHP, Laravel, and other useful readings
- Environment setup (Docker, Laravel, React): ~30 minutes
- Backend API + validation + tests: ~2-3 hours
- Frontend UI + styling: ~2-3 hours
- Documentation, cleanup, bug-fixing, modifications: ~30 minutes