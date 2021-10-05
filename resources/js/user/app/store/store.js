import { createStore } from "vuex";

import actions from './actions';
import getters from './getters';
import mutations from './mutations';
import state from './state';

const store = createStore({
    actions,
    getters,
    mutations,
    state
});

export default store;