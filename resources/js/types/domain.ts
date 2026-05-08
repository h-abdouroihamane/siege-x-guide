/**
 * Domain types derived from the API Resources shipped to Inertia pages.
 * Source of truth: app/Http/Resources/*.php
 */

/** Shape returned by OperationResource (embedded in OperatorResource) */
export interface OperationData {
    id: string;
    name: string;
    release_date: string;
}

/**
 * Shape returned by OperatorResource (inside a collection wrapper).
 * Consumed by OperatorsView (as raw data) and OperatorForm (as a single
 * operator for editing).
 */
export interface OperatorData {
    id: string;
    name: string;
    description: string;
    side: string;
    year: number;
    season: number;
    reworked: boolean;
    operation: OperationData;
    roles: string[];
    squad: string;
    queerIdentities: string[];
}

/**
 * Shape returned by SecondaryGadgetResource.
 * `operators` is a plucked string collection, serialised as string[].
 */
export interface SecondaryGadgetData {
    name: string;
    operators: string[];
}

/** Shape returned by RoleResource (used by VocabularyController). */
export interface RoleData {
    name: string;
    definition: string;
    operators: string[];
}

/**
 * Shape returned by OperationResource when used standalone
 * (edit operator form operation dropdown).
 */
export interface OperationOptionData {
    id: string;
    name: string;
    release_date: string;
}
