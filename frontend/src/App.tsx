import { useState, useEffect } from 'react';
import type { Staff, Shift } from './types';
import { getShifts, getStaff , createStaff, createShift, updateShift} from './api';
import { StaffForm } from './components/StaffForm';
import { StaffList } from './components/StaffList';
import { ShiftList } from './components/ShiftList';
import { ShiftForm } from './components/ShiftForm';
import './App.css';

function App() {
  const [staff, setStaff] = useState<Staff[]>([]);
  const [shifts, setShifts] = useState<Shift[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  // Load staff and shifts from the API on mount and sets error state if req fails
  const loadData = async () => {
    try {
      setLoading(true);
      const [staffData, shiftsData] = await Promise.all([
        getStaff(),
        getShifts(),
      ]);
      
      setStaff(staffData);
      setShifts(shiftsData);
      setError(null);
    } catch (err) {
      // set error state to the error message or default message
      setError(err instanceof Error ? err.message : 'Failed to load data');
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    loadData();
  }, []);

  // create staff
  const handleCreateStaff = async (data: {
    name: string;
    role: string;
    phone_number: string;
  }) => {
    const newStaff = await createStaff(data);
    setStaff((prev) => [...prev, newStaff]);

  }

  const handleCreateShift = async (data: {
    day: string;
    start_time: string;
    end_time: string;
    role: string;
    staff_id?: number | null;
  }) => {
    const newShift = await createShift(data);
    setShifts((prev) => [...prev, newShift]);
  };

  const handleAssignShift = async (shiftId: number, staffId: number | null) => {
    const updatedShift = await updateShift(shiftId, staffId);
    setShifts((prev) =>
      prev.map((s) => (s.id === shiftId ? updatedShift : s))
    );
  };

  
  if (loading) return <div className="container">Loading...</div>;
  if (error) return <div className="container error">{error}</div>;

  // Layout for staff and shift management sections

  return (
    <div className="container">
      <h1>Restaurant Staff Scheduler</h1>

      <section>
        <h2>Staff Management</h2>
        <StaffList staff={staff} />
        <StaffForm onSubmit={handleCreateStaff} />
        
      </section>

      <section>
        <h2>Shift Scheduling</h2>
        <ShiftList
          shifts={shifts}
          staff={staff}
          onAssign={handleAssignShift}
        />
        <ShiftForm staff={staff} onSubmit={handleCreateShift} />
      </section>
    </div>
  );
}

export default App;