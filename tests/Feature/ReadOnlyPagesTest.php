<?php

use Illuminate\Testing\Fluent\AssertableJson;
use Inertia\Testing\AssertableInertia;

use function Pest\Laravel\get;

beforeEach(function () {
    $this->seed();
});

it('renders the read-only Inertia pages', function (
    string $uri,
    string $component,
) {
    get($uri)->assertOk()->assertInertia(
        // shouldExist=false: we assert the controller returns the right
        // Inertia component, not that Inertia can resolve the .vue file
        // (the frontend build covers that).
        fn(AssertableInertia $page) => $page->component($component, false),
    );
})->with([
    'home' => ['/', 'Home'],
    'about' => ['/about', 'AboutMe'],
    'operators' => ['/operators', 'OperatorsView'],
    'squads' => ['/squads', 'SquadsView'],
    'secondary gadgets' => ['/secondary-gadgets', 'SecondaryGadgetView'],
    'vocabulary' => ['/vocabulary', 'Vocabulary'],
]);

it('serves the JSON data endpoints', function (string $uri) {
    get($uri)->assertOk()->assertJson(fn(AssertableJson $json) => $json->etc());
})->with([
    'operators/all' => ['/operators/all'],
    'squads/all' => ['/squads/all'],
    'secondary-gadgets/all' => ['/secondary-gadgets/all'],
]);
