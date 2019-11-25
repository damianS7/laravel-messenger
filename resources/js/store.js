import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        people: [],
        contacts: [],
        selected_contact: {},
        profile: {},
        conversation: [],
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
        getSelectedContact: (state, getters) => {
            return state.selected_contact;
        },
    },
    mutations: {
        setMessengerUsers(state, users) {
            state.people = users
        },
        setContacts(state, contacts) {
            state.contacts = contacts
        },
        setProfile(state, profile) {
            state.profile = profile
        },
        updateProfile(state, name, info, avatar) {
            state.profile.name = name;
            state.profile.info = info;
            state.profile.avatar = avatar;
        },
        pushMessage(state, message) {
            state.messages.push({ id: 5, name: 'damianS3', content: message, isSender: true });
        },
        setMessages(state, messages) {
            state.messages = messages;
        },
        setSelectedContact(state, contact) {
            state.selected_contact = contact;
        }
    },
    actions: {
        updateProfile(context, name, info, avatar) {
            context.commit('updateProfile', name, info, avatar);
        },
        fetchConversation(context, contact_id) {
            axios.get("http://127.0.0.1:8000/conversation/" + contact_id).then(function (response) {
                // Si el request tuvo exito (codigo 200)
                if (response.status == 200) {
                    // Agregamos las notas al array
                    context.commit('setMessages', response["data"]['messages']);
                }
            });
        },
        fetchData(context) {
            axios.get("http://127.0.0.1:8000/contacts/").then(function (response) {
                // Si el request tuvo exito (codigo 200)
                if (response.status == 200) {
                    // Agregamos las notas al array
                    context.commit('setContacts', response["data"]['user_contacts']);
                    context.commit('setMessengerUsers', response["data"]['users']);
                }
            });

            axios.get("http://127.0.0.1:8000/profile/").then(function (response) {
                // Si el request tuvo exito (codigo 200)
                if (response.status == 200) {
                    // Agregamos las notas al array
                    context.commit('setProfile', response["data"]['profile'][0]);
                }
            });
        }
    }
})
