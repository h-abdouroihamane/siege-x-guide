// Removes accents from the operator's name to access their portrait/icon files
const normalize = (str: string): string => {
    return str
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .replace('ø', 'o')
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
    roles: string[];
    squad: string;
    portrait: string;
    icon: string;

    constructor(
        name: string,
        description: string,
        side: string,
        year: number,
        season: number,
        operationName: string,
        roles: string[],
        squad: string
    ) {
        this.name = name;
        this.description = description;
        this.side = side;
        this.year = year;
        this.season = season;
        this.operationName = operationName;
        this.roles = [...roles];
        this.squad = squad;

        this.cleanName = normalize(this.name);
        this.portrait = `/operatorPortraits/${this.cleanName}.png`;
        this.icon = `/operatorIcons/${this.cleanName}.png`;
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
