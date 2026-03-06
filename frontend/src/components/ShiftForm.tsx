import { useState } from 'react';
import type { Staff, Role } from '../types';

interface ShiftFormProps {
  staff: Staff[];
  onSubmit: (data: {
    day: string;
    start_time: string;
    end_time: string;
    role: Role;
    staff_id?: number | null;
  }) => Promise<void>;
}

export function ShiftForm({ staff, onSubmit }: ShiftFormProps) {
  const [day, setDay] = useState('');
  const [startTime, setStartTime] = useState('09:00');
  const [endTime, setEndTime] = useState('17:00');
  const [role, setRole] = useState<Role>('server');
  const [staffId, setStaffId] = useState<string>('');
  const [submitting, setSubmitting] = useState(false);
  const [error, setError] = useState<string | null>(null);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setSubmitting(true);
    setError(null);

    try {
      await onSubmit({
        day,
        start_time: startTime,
        end_time: endTime,
        role,
        staff_id: staffId ? Number(staffId) : null,
      });
      setDay('');
      setStartTime('09:00');
      setEndTime('17:00');
      setRole('server');
      setStaffId('');
    } catch (err) {
      setError(err instanceof Error ? err.message : 'Failed to create shift');
    } finally {
      setSubmitting(false);
    }
  };

  // Filter staff by selected role
  const availableStaff = staff.filter((s) => s.role === role);

  return (
    <form onSubmit={handleSubmit} className="form">
      <h3>Create Shift</h3>

      {error && <div className="form-error">{error}</div>}

      <div className="form-row">
        <div className="form-group">
          <label htmlFor="day">Day</label>
          <input
            id="day"
            type="date"
            value={day}
            onChange={(e) => setDay(e.target.value)}
            required
          />
        </div>

        <div className="form-group">
          <label htmlFor="start">Start Time</label>
          <input
            id="start"
            type="time"
            value={startTime}
            onChange={(e) => setStartTime(e.target.value)}
            required
          />
        </div>

        <div className="form-group">
          <label htmlFor="end">End Time</label>
          <input
            id="end"
            type="time"
            value={endTime}
            onChange={(e) => setEndTime(e.target.value)}
            required
          />
        </div>
      </div>

      <div className="form-row">
        <div className="form-group">
          <label htmlFor="shift-role">Role Needed</label>
          <select
            id="shift-role"
            value={role}
            onChange={(e) => {
              setRole(e.target.value as Role);
              setStaffId(''); // Reset staff when role changes
            }}
            required
          >
            <option value="server">Server</option>
            <option value="cook">Cook</option>
            <option value="manager">Manager</option>
          </select>
        </div>

        <div className="form-group">
          <label htmlFor="assign">Assign To (Optional)</label>
          <select
            id="assign"
            value={staffId}
            onChange={(e) => setStaffId(e.target.value)}
          >
            <option value="">-- Unassigned --</option>
            {availableStaff.map((s) => (
              <option key={s.id} value={s.id}>
                {s.name}
              </option>
            ))}
          </select>
        </div>
      </div>

      <button type="submit" disabled={submitting}>
        {submitting ? 'Creating...' : 'Create Shift'}
      </button>
    </form>
  );
}