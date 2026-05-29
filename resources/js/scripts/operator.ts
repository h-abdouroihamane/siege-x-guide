// Static assets live in Laravel's public/ and are served from the web
// root. They are NOT Vite-managed, so they must use a root-absolute
// path ("/...") — never import.meta.env.BASE_URL, which resolves to
// "/build/" in production builds and would 404.
export const publicAsset = (path: string): string =>
    '/' + path.replace(/^\/+/, '');

// Removes accents from the operator's name to access their portrait/icon files
export const normalize = (str: string): string => {
    return str
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .replace('ø', 'o')
        .replace(/\s+/g, '-')
        .toLowerCase();
};

export const operatorIcon = (name: string): string =>
    publicAsset(`operatorIcons/${normalize(name)}.png`);

export const operatorPortrait = (name: string): string =>
    publicAsset(`operatorPortraits/${normalize(name)}.png`);

export const gadgetLogo = (name: string): string =>
    publicAsset(
        `secondaryGadgets/${name.toLowerCase().replace(/ +/g, '-')}.png`,
    );

export class Operator {
    name: string;
    cleanName: string;
    description: string;
    side: string;
    year: number;
    season: number;
    operationName: string;
    operationReleaseDate: Date;
    reworked: boolean;
    roles: string[];
    queerIdentities: string[] | null;
    portrait: string;
    icon: string;

    constructor(
        name: string,
        description: string,
        side: string,
        year: number,
        season: number,
        operationName: string,
        operationReleaseDate: string,
        roles: string[],
        queerIdentities: string[] | null,
        reworked: boolean = false,
    ) {
        this.name = name;
        this.description = description;
        this.side = side;
        this.year = year;
        this.season = season;
        this.operationName = operationName;
        this.operationReleaseDate = new Date(operationReleaseDate);
        this.reworked = reworked;
        this.roles = [...roles];
        this.queerIdentities = queerIdentities;

        this.cleanName = normalize(this.name);
        this.portrait = operatorPortrait(this.name);
        this.icon = operatorIcon(this.name);
    }

    isAttacker(): boolean {
        return this.side === 'Attack';
    }

    isDefender(): boolean {
        return this.side === 'Defense';
    }

    compareName(otherOperator: Operator): number {
        if (this.name === otherOperator.name) {
            return 0;
        }

        return this.name < otherOperator.name ? -1 : 1;
    }

    compareRelease(otherOperator: Operator): number {
        if (this.year !== otherOperator.year) {
            return this.year > otherOperator.year ? -1 : 1;
        }

        if (this.season !== otherOperator.season) {
            return this.season > otherOperator.season ? -1 : 1;
        }

        if (this.side !== otherOperator.side) {
            return this.isAttacker() ? -1 : 1;
        }

        return this.compareName(otherOperator);
    }
}
