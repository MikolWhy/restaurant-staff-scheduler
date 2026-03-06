import { useState, useEffect } from 'react';
import type { Staff, Shift } from './types';
import { getShifts, getStaff , addStaff} from './api';
import { StaffForm } from './components/StaffForm';
import { StaffList } from './components/StaffList';
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
  const handleAddStaff = async (data: {
    name: string;
    role: string;
    phone_number: string;
  }) => {
    const newStaff = await addStaff(data);
    setStaff((prev) => [...prev, newStaff]);

  }
  
  if (loading) return <div className="container">Loading...</div>;
  if (error) return <div className="container error">{error}</div>;

  // Main layout: staff section and shifts section

  return (
    <div className="container">
      <h1>Restaurant Staff Scheduler</h1>

      <section>
        <h2>Staff Management</h2>
        <StaffList staff={staff} />
        <StaffForm onSubmit={handleAddStaff} />
        
      </section>

      <section>
        <h2>Shift Scheduling</h2>
        <p>Shift count: {shifts.length}</p>
        {/* ShiftList and ShiftForm go here soon */}
      </section>
    </div>
  );
}

export default App;