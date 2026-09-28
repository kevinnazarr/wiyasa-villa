export type CabinSummary = {
    id: number;
    code: string;
    slug: string;
    name: string;
    description: string | null;
    capacity: number;
    base_occupancy: number;
    status: string;
};

export type CabinDetail = CabinSummary;

export type ReservationSummary = {
    code: string;
    status?: string | null;
};

export type BookingSummary = {
    code: string;
    status?: string | null;
};
