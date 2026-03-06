import type { Staff } from '../types';

interface StaffListProps {
  staff: Staff[];
}

export function StaffList({ staff }: StaffListProps) {
  if (staff.length === 0) {
    return <p className="empty">No staff members yet.</p>;
  }

  return (
    <table className="table">
      <thead>
        <tr>
          <th>Name</th>
          <th>Role</th>
          <th>Phone</th>
        </tr>
      </thead>
      <tbody>
        {staff.map((member) => (
          <tr key={member.id}>
            <td>{member.name}</td>
            <td className="capitalize">{member.role}</td>
            <td>{member.phone_number}</td>
          </tr>
        ))}
      </tbody>
    </table>
  );
}