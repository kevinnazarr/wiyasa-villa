export type CabinStatus = 'ACTIVE' | 'INACTIVE' | 'MAINTENANCE';

export type CabinTranslation = {
    id: number;
    cabin_id: number;
    locale: 'id' | 'en';
    name: string;
    description: string | null;
};

export type CabinSummary = {
    id: number;
    code: string;
    name: string;
    slug: string;
    capacity: number;
    base_occupancy: number;
    status: CabinStatus;
};

export type CabinDetail = CabinSummary & {
    description: string | null;
    check_in_time: string | null;
    check_out_time: string | null;
    translations: Record<string, CabinTranslation>;
    localized_name: string;
};
