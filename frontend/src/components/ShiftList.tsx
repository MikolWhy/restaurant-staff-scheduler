import type { Shift, Staff } from '../types';

interface ShiftListProps {
  shifts: Shift[];
  staff: Staff[];
  onAssign: (shiftId: number, staffId: number | null) => Promise<void>;
}

export function ShiftList({ shifts, staff, onAssign }: ShiftListProps) {
  if (shifts.length === 0) {
    return <p className="empty">No shifts scheduled yet.</p>;
  }

  const handleAssignChange = async (shift: Shift, newStaffId: string) => {
    try {
      const staffId = newStaffId ? Number(newStaffId) : null;
      await onAssign(shift.id, staffId);
    } catch (err) {
      alert(err instanceof Error ? err.message : 'Failed to assign shift');
    }
    
  };

  // Format time for display (remove seconds if present)
  const formatTime = (time: string) => {
    return time.slice(0, 5);
  };

  return (
    <table className="table">
      <thead>
        <tr>
          <th>Day</th>
          <th>Time</th>
          <th>Role</th>
          <th>Assigned To</th>
        </tr>
      </thead>
      <tbody>
        {shifts.map((shift) => {
          const availableStaff = staff.filter((s) => s.role === shift.role);

          return (
            <tr key={shift.id}>
              <td>{shift.day}</td>
              <td>
                {formatTime(shift.start_time)} - {formatTime(shift.end_time)}
              </td>
              <td className="capitalize">{shift.role}</td>
              <td>
                <select
                  value={shift.staff_id || ''}
                  onChange={(e) => handleAssignChange(shift, e.target.value)}
                  className="assign-select"
                >
                  <option value="">-- Unassigned --</option>
                  {availableStaff.map((s) => (
                    <option key={s.id} value={s.id}>
                      {s.name}
                    </option>
                  ))}
                </select>
              </td>
            </tr>
          );
        })}
      </tbody>
    </table>
  );
}