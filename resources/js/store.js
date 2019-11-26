import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        people: [],
        contacts: [],
        selected_contact: { profile: { name: '' }, conversation: {} },
        profile: {}
    },
    getters: {
        getMessagesFromConversation(conversation_index) {
            //return state.conversations[conversation_index].messages;
        },
        getSelectedContact: (state, getters) => {
            return state.selected_contact;
        },
        getContactById: (state, getters, id) => {
            //return state.contacts;
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
            //state.messages.push({ id: 5, name: 'damianS3', content: message, isSender: true });
        },
        pushMessageToConversation(state, message) {
            var conversation_id = message.conversation_id;
            for (var index in state.contacts) {
                var contact = state.contacts[index];
                if (contact.conversation.conversation_id == conversation_id) {
                    contact.conversation.messages.push(message);
                    // Actualizamos la fecha del ultimo mensaje recibido
                }
            }
            //state.messages.push({ id: 5, name: 'damianS3', content: message, isSender: true });
        },
        setSelectedContact(state, contact) {
            state.selected_contact = contact;
        },
        setConversations(state, conversations) {
            state.conversations = conversations;
        }
    },
    actions: {
        // Envia los datos del perfil actualizado a la base de datos
        updateProfile(context) {
            //context.commit('updateProfile', name, info, avatar);

            var profile = this.state.profile;
            axios.post("http://127.0.0.1:8000/profile/" + profile.id, {
                profile: profile,
                _method: "put"
            });
        },
        fetchLastMessages(context) {
            axios.get("http://127.0.0.1:8000/conversations/update").then(function (response) {
                // Si el request tuvo exito (codigo 200)
                if (response.status == 200) {
                    // Agregamos las notas al array
                    // context.commit('setMessages', response["data"]['messages']);
                    // Comprobamos que existan datos para agregar!
                    //return context.getContactById(response['messages']);
                    var data = response['data']['messages'];
                    for (var key in data) {
                        context.commit('pushMessageToConversation', data[key]);
                    }
                }

            });
        },
        fetchData(context) {
            axios.get("http://127.0.0.1:8000/fetchAll/").then(function (response) {
                // Si el request tuvo exito (codigo 200)
                if (response.status == 200) {
                    // Agregamos las notas al array
                    //console.log(response['data']['contacts']);
                    context.commit('setContacts', response['data']['contacts']);
                    ///context.commit('setMessengerUsers', response["data"]['users']);
                }
            });

            axios.get("http://127.0.0.1:8000/profile/").then(function (response) {
                // Si el request tuvo exito (codigo 200)
                if (response.status == 200) {
                    // Agregamos las notas al array
                    context.commit('setProfile', response["data"]['profile'][0]);
                }
            });
        },
        // Envio de mensajes al servidor
        postMessage(context, message) {
            var conversation_id = context.state.selected_contact.conversation.conversation_id;
            axios.post("http://127.0.0.1:8000/conversation/" + conversation_id, {
                message: message
            });
        }
    }
})
