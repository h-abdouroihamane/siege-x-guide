const publicPath = import.meta.env.BASE_URL;

// Removes accents from the operator's name to access their portrait/icon files
export const normalize = (str: string): string => {
    return str
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .replace('ø', 'o')
        .replace(/\s+/g, '-')
        .toLowerCase();
};

export class Operator {
    name: string;
    cleanName: string;
    description: string;
    side: string;
    year: number;
    season: number;
    operationName: string;
    operationReleaseDate: string;
    reworked: boolean;
    roles: string[];
    squad: string;
    queerIdentites: string[] | null;
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
        squad: string,
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
        this.squad = squad;
        this.queerIdentities = queerIdentities;

        this.cleanName = normalize(this.name);
        this.portrait = `${publicPath}operatorPortraits/${this.cleanName}.png`;
        this.icon = `${publicPath}operatorIcons/${this.cleanName}.png`;
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
