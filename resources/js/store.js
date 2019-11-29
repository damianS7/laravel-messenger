import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        // Datos de usuario y perfil
        user: {},
        // Perfil del usuario logeado en la app
        profile: { id: '', user_id: '', name: '', alias: '', avatar: '', info: '' },
        // Gente disponible en la app para "conectar"
        people: [],
        // Contactos del usuarios
        contacts: [],
        // Conversaciones entre el usuario y cada contacto        
        conversations: [],
        // Contacto seleccionado actualmente
        selected_contact: {},
    },
    getters: {
        getSelectedContactConversation: (state, getters) => {
            if (typeof state.selected_contact.conversation_id === 'undefined') {
                return [];
            }
            return getters.getConversationById(state.selected_contact.conversation_id);
        },
        getConversationById: (state, getters) => (id) => {
            return state.conversations.find(conversation => conversation.conversation_id === id);
        },
        getContactIndex: (state, getters) => (user_id) => {
            for (var index in state.contacts) {
                var contact = state.contacts[index];
                if (contact.user_id === user_id) {
                    return index;
                }
            }
        },
        getPeopleIndex: (state, getters) => (user_id) => {
            for (var index in state.people) {
                var contact = state.people[index];
                if (contact.user_id === user_id) {
                    return index;
                }
            }
        }
    },
    mutations: {
        setContacts(state, contacts) {
            state.contacts = contacts
        },
        setProfile(state, profile) {
            state.profile = profile
        },
        setSelectedContact(state, contact) {
            state.selected_contact = contact;
        },
        setPeople(state, people) {
            state.people = people
        },
        setConversations(state, conversations) {
            state.conversations = conversations;
        },
        removeContact(state, index) {
            Vue.delete(state.contacts, index);
        },
        removePeopleById(state, peopleId) {
            var peopleIndex = state.people.findIndex(people => people.id === peopleId);
            Vue.delete(state.people, peopleIndex);
        },
        addContact(state, contact) {
            state.contacts.push(contact);
        },
        addConversation(state, conversation) {
            state.conversations.push(conversation);
        },
        // ==================
        pushMessageToConversation(state, payload) {
            // Agregamos el mensaje a la conversacion
            payload.conversation.messages.push(payload.message);
            // Actualizamos la fecha del ultimo mensaje recibidos
        },
    },
    actions: {
        // Envia los datos del perfil actualizado a la base de datos
        saveProfile(context) {
            var profile = this.state.profile;
            axios.post("http://127.0.0.1:8000/profile/" + profile.id, {
                profile: profile,
                _method: "put"
            });
        },
        // Envio de mensajes al servidor
        postMessage(context, message) {
            var conversation_id = context.state.selected_contact.conversation_id;
            axios.post("http://127.0.0.1:8000/conversation/" + conversation_id, {
                message: message
            });
        },
        // Peticion para borrar un contacto
        deleteContact(context, data) {
            axios.post("http://127.0.0.1:8000/contacts/" + data.contact_id, {
                _method: "delete"
            }).then(function (response) {
                // Si el request tuvo exito (codigo 200)
                if (response.status == 204) {
                    context.commit("removeContact", data.index);
                    context.commit("setSelectedContact", {});
                }
            });
        },
        // Peticion para borrar un contacto
        saveContact(context, data) {
            axios.post("http://127.0.0.1:8000/contacts/", {
                user_id: data.user_id
            }).then(function (response) {
                // Si el request tuvo exito (codigo 200)
                if (response.status == 200) {
                    var conversation = response['data']['conversation'];
                    // Agregamos el nuevo contacto usando los datos recibidos
                    context.commit("addContact", response['data']['contact']);

                    // Agregamos una nueva conversacion si existe el objeto
                    if (conversation.length != 0) {
                        context.commit("addConversation", conversation);
                    }
                    // Borramos a la persona que hemos agregado de People
                    context.commit("removePeopleById", data.user_id);
                }
            });
        },
        // Peticion al servidor para recibir los nuevos mensajes
        fetchLastMessages(context) {
            axios.get("http://127.0.0.1:8000/conversations/update").then(function (response) {
                // Si el request tuvo exito (codigo 200)
                if (response.status == 200) {
                    var data = response['data']['messages'];
                    // Si no hay datos ...
                    if (data.length == 0) {
                        return;
                    }

                    // Iteramos sobre los datos
                    for (var index in data) {
                        var conversation = context.getters.getConversationById(
                            data[index].conversation_id);
                        var message = data[index];
                        context.commit('pushMessageToConversation',
                            { message, conversation });
                    }
                }

            });
        },
        // ---------------------
        fetchData(context) {
            // People
            axios.get("http://127.0.0.1:8000/people/").then(function (response) {
                // Si el request tuvo exito (codigo 200)
                if (response.status == 200) {
                    // Agregamos las notas al array
                    context.commit('setPeople', response['data']['people']);
                }
            });
            // Contactos (con los perfiles)
            axios.get("http://127.0.0.1:8000/contacts/").then(function (response) {
                // Si el request tuvo exito (codigo 200)
                if (response.status == 200) {
                    // Agregamos las notas al array
                    context.commit('setContacts', response['data']['contacts']);
                }
            });
            // Contact Conversations
            axios.get("http://127.0.0.1:8000/conversations/").then(function (response) {
                // Si el request tuvo exito (codigo 200)
                if (response.status == 200) {
                    // Agregamos las notas al array
                    context.commit('setConversations', response['data']['conversations']);
                }
            });
            // Datos de usuario y perfil
            axios.get("http://127.0.0.1:8000/profile/").then(function (response) {
                // Si el request tuvo exito (codigo 200)
                if (response.status == 200) {
                    // Agregamos las notas al array
                    context.commit('setProfile', response["data"]['profile'][0]);
                }
            });
        },
    }
})
