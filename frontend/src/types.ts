export interface Staff {
    id: number;
    name: string;
    role: 'server' | 'cook' | 'manager';
    phone_number: string;
    created_at: string;
    updated_at: string;
}

export interface Shift {
    id: number;
    staff_id: number | null;
    staff: Staff | null;
    day: string;
    start_time: string;
    end_time: string;
    role: 'server' | 'cook' | 'manager';
    created_at: string;
    updated_at: string;
}

export type Role = 'server' | 'cook' | 'manager';