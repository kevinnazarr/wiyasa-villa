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
