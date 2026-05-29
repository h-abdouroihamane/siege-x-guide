// Shapes returned by the Laravel API resources (read-only public site).

export interface OperationDTO {
    id: string;
    name: string;
    release_date: string;
}

export interface OperatorDTO {
    name: string;
    description: string;
    side: string;
    year: number;
    season: number;
    reworked: boolean;
    operation: OperationDTO;
    roles: string[];
    queerIdentities: string[];
}

// Inertia page props for the operators page.
export interface OperatorsPageProps {
    operators: { data: OperatorDTO[] };
    [key: string]: unknown;
}
