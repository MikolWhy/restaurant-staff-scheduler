import type { Staff, Shift } from './types';

const API_BASE = '/api';

// GET all staff from the API and Throws if the req fails
export async function getStaff(): Promise<Staff[]> {
  const response = await fetch(`${API_BASE}/staff`);
  if (!response.ok) throw new Error('Failed to fetch staff');
  return response.json();
}

// POST new staff member
// data from form fields, returns the added staff or throws error
export async function addStaff(data: {
  name: string;
  role: string;
  phone_number: string;
}): Promise<Staff> {
  const response = await fetch(`${API_BASE}/staff`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json',
      'Accept': 'application/json',
     },
    body: JSON.stringify(data),
  });
  if (!response.ok) {
    const error = await response.json();
    const message = error.errors
      ? Object.values(error.errors).flat().join(' ')
      : error.message || 'Failed to add staff';
    throw new Error(message);
  }
  return response.json();
}

// GET all shifts from the API or throw on req fail
export async function getShifts(): Promise<Shift[]> {
  const response = await fetch(`${API_BASE}/shifts`);
  if (!response.ok) throw new Error('Failed to fetch shifts');
  return response.json();
}

// POST a new shift, staff_id is optional for unassigned
export async function addShift(data: {
  day: string;
  start_time: string;
  end_time: string;
  role: string;
  staff_id?: number | null;
}): Promise<Shift> {
  const response = await fetch(`${API_BASE}/shifts`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' ,
      'Accept': 'application/json',
    },
    body: JSON.stringify(data),
  });
  if (!response.ok) {
    const error = await response.json();
    const message = error.errors
      ? Object.values(error.errors).flat().join(' ')
      : error.message || 'Failed to add shift';
    throw new Error(message);
  }
  return response.json();
}

// PATCH shift to assign or unassign a staff member. staffId null = unassigned 
export async function updateShift(
  shiftId: number,
  staffId: number | null
): Promise<Shift> {
  const response = await fetch(`${API_BASE}/shifts/${shiftId}`, {
    method: 'PATCH',
    headers: { 'Content-Type': 'application/json',
      'Accept': 'application/json',
     },
    body: JSON.stringify({ staff_id: staffId }),
  });
  if (!response.ok) {
    const error = await response.json();
    const message = error.errors
      ? Object.values(error.errors).flat().join(' ')
      : error.message || 'Failed to update shift';
    throw new Error(message);
  }
  return response.json();
}