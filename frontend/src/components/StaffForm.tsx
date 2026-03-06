import { useState } from 'react';
import type { Role } from '../types';

interface StaffFormProps {
  onSubmit: (data: { name: string; role: Role; phone_number: string }) => Promise<void>;
}

export function StaffForm({ onSubmit }: StaffFormProps) {
  const [name, setName] = useState('');
  const [role, setRole] = useState<Role>('server');
  const [phoneNumber, setPhoneNumber] = useState('');
  const [submitting, setSubmitting] = useState(false);
  const [error, setError] = useState<string | null>(null);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setSubmitting(true);
    setError(null);

    try {
      await onSubmit({ name, role, phone_number: phoneNumber });
      setName('');
      setRole('server');
      setPhoneNumber('');
    } catch (err) {
      setError(err instanceof Error ? err.message : 'Failed to create staff');
    } finally {
      setSubmitting(false);
    }
  };

  return (
    <form onSubmit={handleSubmit} className="form">
      <h3>Add Staff Member</h3>

      {error && <div className="form-error">{error}</div>}

      <div className="form-group">
        <label htmlFor="name">Name</label>
        <input
          id="name"
          type="text"
          value={name}
          onChange={(e) => setName(e.target.value)}
          required
        />
      </div>

      <div className="form-group">
        <label htmlFor="role">Role</label>
        <select
          id="role"
          value={role}
          onChange={(e) => setRole(e.target.value as Role)}
          required
        >
          <option value="server">Server</option>
          <option value="cook">Cook</option>
          <option value="manager">Manager</option>
        </select>
      </div>

      <div className="form-group">
        <label htmlFor="phone">Phone Number</label>
        <input
          id="phone"
          type="tel"
          value={phoneNumber}
          onChange={(e) => setPhoneNumber(e.target.value)}
          required
        />
      </div>

      <button type="submit" disabled={submitting}>
        {submitting ? 'Adding...' : 'Add Staff'}
      </button>
    </form>
  );
}