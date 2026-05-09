# Siege X Guide Roadmap

## Prep phase

1. Plan out the database
2. Import data from the previous version of the Guide
3. Create /get-all route

## Front-end

4. Fetch and display operator cards
5. Add the description bar at the bottom + onClick interaction
6. Filters (Attackers/Defenders, Show queer flags, Alphabetical/Date sorting)

## Back-end

7. Add the admin role
8. Admin login page
9. Add operator function
10. Edit operator function

## Bonus

11. Page that shows every squad

## Follow-ups

- **OperatorSelection redesign** (`resources/js/pages/OperatorSelection.vue` + `OperatorController::selectForEditing` / `selectPost`).
  The current admin "pick an operator to edit" interstitial is a plain native `<select>` of every operator name, then a submit-and-redirect round-trip. With 70+ operators the list is hard to scan and gives no visual cue.
  Replace with a filterable Reka Combobox (already in the codebase from the operator form redesign) showing each operator's portrait, side glow, and squad — mirroring the dossier aesthetic established in the new `OperatorForm`. Likely also fold `selectPost` into a direct `<Link :href="route('operator.edit', op.id)">` per row, removing the round-trip entirely.
