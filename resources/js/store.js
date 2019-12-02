import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        // Datos de usuario y perfil
        appUser: {},
        // Gente disponible en la app para "conectar"
        people: [],
        // Contactos del usuarios
        contacts: [],
        // Conversaciones entre el usuario y cada contacto        
        conversations: [],
        // Contacto seleccionado actualmente
        selectedContact: {},
        // Conversacion seleccionada
        selectedConversation: {}
    },
    getters: {
        getSelectedContactConversation: (state, getters) => {
            if (typeof state.selectedContact.conversation_id === 'undefined') {
                return [];
            }
            return getters.getConversationById(state.selectedContact.conversation_id);
        },
        getSelectedConversation: (state, getters) => {
            if (typeof state.selectedConversation.id === 'undefined') {
                return null;
            }
            return state.selectedConversation;
        },
        getSelectedConversationMessages: (state, getters) => {
            var conversation = getters.getSelectedConversation;
            if (conversation == null) {
                return;
            }

            return conversation.messages;
        },
        getConversationById: (state, getters) => (conversationId) => {
            return state.conversations.find(conversation => conversation.id === conversationId);
        },
        getPeopleById: (state, getters) => (userId) => {
            return state.people.find(people => people.id === userId);
        },
        getContactById: (state, getters) => (userId) => {
            return state.contacts.find(contact => contact.user_id === userId);
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
        // Asigna el usuario de la app
        setUser(state, appUser) {
            state.appUser = appUser
        },
        // Asigna los contactos del usuario
        setContacts(state, contacts) {
            state.contacts = contacts
        },
        // Asigna los usuarios disponibles en la app que no son contactos
        setPeople(state, people) {
            state.people = people
        },
        // Asigna las conversaciones
        setConversations(state, conversations) {
            state.conversations = conversations;
        },
        // Selecciona un contacto
        selectContact(state, contact) {
            state.selectedContact = contact;
        },
        // Selecciona un contacto basado en su id
        selectContactById(state, payload) {
            var contactIndex = state.contacts.findIndex(contact =>
                contact.user_id === payload.userId
            );
            state.selectedContact = state.contacts[contactIndex];
        },
        // Selecciona una conversacion
        selectConversation(state, conversation) {
            state.selectedConversation = conversation;
        },
        // Selecciona una conversacion por su id
        selectConversationById(state, payload) {
            var conversationIndex = state.conversations.findIndex(conversation =>
                conversation.id === payload.conversationId
            );
            state.selectedConversation = state.conversations[conversationIndex];
        },
        removeContact(state, index) {
            Vue.delete(state.contacts, index);
        },
        removeContactById(state, payload) {
            var contactIndex = state.contacts.findIndex(
                contact => contact.user_id === payload.userId);
            Vue.delete(state.contacts, contactIndex);
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
        addMessage(state, payload) {
            // Agregamos el mensaje a la conversacion
            payload.conversation.messages.push(payload.message);
        },
    },
    actions: {
        // Envia los datos del perfil actualizado a la base de datos
        saveProfile(context) {
            var profile = this.state.appUser;
            axios.post("http://127.0.0.1:8000/profile/" + profile.id, {
                profile: profile,
                _method: "put"
            });
        },
        // Envio de mensajes al servidor
        postMessage(context, message) {
            var conversation_id = context.state.selectedConversation.id;
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
                    var messages = response['data']['messages'];
                    // Si no hay datos ...
                    if (messages.length == 0) {
                        return;
                    }

                    // Iteramos sobre los datos
                    for (var index in messages) {
                        var message = messages[index];

                        var conversation = context.getters.getConversationById(
                            message.conversation_id);

                        if (typeof conversation === 'undefined') {
                            conversation = { id: message.conversation_id, messages: [] };
                            context.commit('addConversation', conversation);
                        }

                        context.commit('addMessage', { message, conversation });
                    }
                }

            });
        },
        // ---------------------
        fetch(context) {
            axios.get("http://127.0.0.1:8000/messenger/fetch").then(function (response) {
                // Si el request tuvo exito (codigo 200)
                if (response.status == 200) {
                    var data = response["data"];
                    // Userdata
                    context.commit('setUser', data['app_user']);
                    // People
                    context.commit('setPeople', data['people']);
                    // Contacts
                    context.commit('setContacts', data['contacts']);
                    // Conversations
                    context.commit('setConversations', data['conversations']);
                }
            });
        },
    }
})
