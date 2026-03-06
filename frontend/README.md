# Restaurant Staff Scheduler — Frontend

React + TypeScript frontend for the Restaurant Staff Scheduling System. Communicates with the Laravel backend API to let restaurant managers manage staff and shifts.

## Tech Stack

| Technology | Version |
|------------|---------|
| React | 19.x |
| TypeScript | 5.x |
| Vite | 7.x |

## Getting Started

From the `frontend/` directory:

```bash
npm install
npm run dev
```

The dev server starts at **http://localhost:5173** (or the next available port).

The backend API must be running at `http://localhost:8000`. See the [root README](../README.md) for Docker setup instructions.

## Available Scripts

| Command | Description |
|---------|-------------|
| `npm run dev` | Start the Vite dev server with HMR |
| `npm run build` | Type-check and produce a production build in `dist/` |
| `npm run preview` | Preview the production build locally |
| `npm run lint` | Run ESLint across all source files |

## Project Structure

```
src/
├── components/
│   ├── StaffList.tsx     # Displays the full staff roster
│   ├── StaffForm.tsx     # Form to add a new staff member
│   ├── ShiftList.tsx     # Displays all shifts with assigned staff
│   └── ShiftForm.tsx     # Form to create a shift and assign staff
├── api.ts                # Typed fetch wrappers for every API endpoint
├── types.ts              # Shared TypeScript interfaces (Staff, Shift)
├── App.tsx               # Root component — tab layout, state, data fetching
└── App.css               # Global styles and responsive layout
```

## Features

- View all staff members and add new ones (name, role, phone number)
- View all shifts and create new ones (day, start/end time, role)
- Assign or reassign a staff member to a shift via a role-filtered dropdown
- Inline validation error messages sourced from the Laravel backend
- Responsive layout — works on mobile and desktop

## API Integration

All backend calls are centralised in `api.ts`. The base URL is configured via the `VITE_API_URL` environment variable (defaults to `http://localhost:8000/api`). Errors returned by Laravel's validation layer (`422 Unprocessable Entity`) are parsed field-by-field and displayed next to the relevant form inputs.
