import { createMemoryHistory, createRouter } from 'vue-router';
import OperatorsView from './pages/OperatorsView.vue';
import SquadsView from './pages/SquadsView.vue';
const routes = [
    { path: '/operators', component: OperatorsView, name: 'operators' },
    { path: '/squads', component: SquadsView, name: 'squads' },
];

export const router = createRouter({ history: createMemoryHistory(), routes });
