import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        messenger_users: [],
        contacts: [],
        conversations: [],
        messages: [
            { id: 1, name: 'damianS7', content: 'Hello1', isSender: false },
            { id: 2, name: 'damianS8', content: 'Hello2', isSender: false },
            { id: 3, name: 'damianS9', content: 'Hello3', isSender: true },
            { id: 4, name: 'damianS2', content: 'Hello4', isSender: false },
            { id: 5, name: 'damianS3', content: 'Hello5', isSender: true },
            { id: 1, name: 'damianS7', content: 'Hello1', isSender: false },
            { id: 2, name: 'damianS8', content: 'Hello2', isSender: false },
            { id: 3, name: 'damianS9', content: 'Hello3', isSender: true },
            { id: 4, name: 'damianS2', content: 'Hello4', isSender: false },
            { id: 5, name: 'damianS3', content: 'Hello5', isSender: true }
        ]
    },
    getters: {

    },
    mutations: {
        setMessengerUsers(state, users) {
            state.messenger_users = users
        },
        setContacts(state, contacts) {
            state.contacts = contacts
        }
    },
    actions: {
        fetchData(context) {
            axios.get("http://127.0.0.1:8000/contacts/").then(function (response) {
                // Si el request tuvo exito (codigo 200)
                if (response.status == 200) {
                    // Agregamos las notas al array
                    context.commit('setContacts', response["data"]['user_contacts']);
                    context.commit('setMessengerUsers', response["data"]['users']);
                }
            });
        }
    }
})
