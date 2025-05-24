import { createMemoryHistory, createRouter } from 'vue-router';
import OperatorsView from './pages/OperatorsView.vue';
const routes = [{ path: '/operators', component: OperatorsView, name: 'operators' }];

export const router = createRouter({ history: createMemoryHistory(), routes });
