import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        // Datos de usuario y perfil
        appUser: { profile: {} },
        // Gente disponible en la app para "conectar"
        people: [],
        // Contactos del usuarios
        contacts: [],
        // Conversaciones entre el usuario y cada contacto        
        conversations: [],
        // Usuario seleccionado actualmente, ya sea contacto o no
        selectedUser: { profile: {} },
        // Conversacion seleccionada
        selectedConversation: {}
    },
    getters: {
        getConversationWith: (state, getters) => (userId) => {
            return state.conversations.find(conversation =>
                conversation.users[0].id === userId
                || conversation.users[1].id === userId);
        },
        getConversationById: (state, getters) => (conversationId) => {
            return state.conversations.find(
                conversation => conversation.id === conversationId);
        },
        getPeopleById: (state, getters) => (userId) => {
            return state.people.find(user => user.id === userId);
        },
        getContactById: (state, getters) => (userId) => {
            return state.contacts.find(user => user.id === userId);
        },
        getUserById: (state, getters) => (userId) => {
            var user = getters.getContactById(userId);
            if (typeof user === 'undefined') {
                return getters.getPeopleById(userId);
            }
            return user;
        },
        isContact: (state, getters) => (userId) => {
            if (typeof getters.getContactById(userId) === 'undefined') {
                return false;
            }
            return true;
        },
        // Mejorar nombre de metodo
        getUserFromSelectedConversation: (state, getters) => {
            if (state.selectedConversation.users[0].id == state.appUser.id) {
                return state.selectedConversation.users[1];
            }

            return state.selectedConversation.users[0];
        },
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
        // Selecciona un usuario
        selectUser(state, user) {
            state.selectedUser = user;
        },
        // Selecciona un contacto basado en su id
        // Este metodo es un action, selectContact es la mutacion
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
        // Este metodo es un action, selectConversation es la mutacion
        selectConversationById(state, payload) {
            var conversationIndex = state.conversations.findIndex(conversation =>
                conversation.id === payload.conversationId
            );
            state.selectedConversation = state.conversations[conversationIndex];
        },
        removeContact(state, index) {
            Vue.delete(state.contacts, index);
        },
        // action
        removeContactById(state, payload) {
            var contactIndex = state.contacts.findIndex(
                contact => contact.user_id === payload.userId);
            Vue.delete(state.contacts, contactIndex);
        },
        // action
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
        // state, message
        addMessage(state, payload) {
            // Agregamos el mensaje a la conversacion
            payload.conversation.messages.push(payload.message);
        },
        // state, people
        addPeople(state, payload) {
            state.people.push(payload.people);
        },
    },
    actions: {
        selectUserById(context, data) {
            var user = context.getters.getUserById(data.userId);
            context.commit('selectUser', user);
        },
        selectConversationById(context, data) {
            var conversation = context.getters.getConversationById(data.conversationId);
            context.commit('selectConversation', conversation);
        },
        messageToConversation(context, message) {
            var conversation = context.getters.getConversationById(
                message.conversation_id);

            if (typeof conversation === 'undefined') {
                conversation = { id: message.conversation_id, messages: [] };
                context.commit('addConversation', conversation);
            }

            context.commit('addMessage', { message, conversation });
        },
        // Envia los datos del perfil actualizado a la base de datos
        saveProfile(context) {
            var profile = this.state.appUser.profile;
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
            }).then(function (response) {
                // Si el request tuvo exito (codigo 200)
                if (response.status == 200) {
                    var message = response['data'];
                    // Si no hay datos ...
                    if (message.length == 0) {
                        return;
                    }

                    context.dispatch('messageToConversation', message);
                }
            });
        },
        // Peticion para borrar un contacto
        // context, user
        deleteContact(context, data) {
            axios.post("http://127.0.0.1:8000/contacts/" + data.userId, {
                _method: "delete"
            }).then(function (response) {
                // Si el request tuvo exito (codigo 200)
                if (response.status == 204) {
                    var user = context.getters.getUserById(data.userId);

                    // Borramos el usuario de contactos
                    context.commit("removeContactById", { userId: data.userId });

                    // Movemos el contacto a people
                    context.commit('addPeople', { people: user });
                }
            });
        },
        // Peticion para agregar un contacto
        saveContact(context, data) {
            axios.post("http://127.0.0.1:8000/contacts/", {
                user_id: data.userId
            }).then(function (response) {
                // Si el request tuvo exito (codigo 200)
                if (response.status == 200) {
                    // Agregamos una nueva conversacion si existe el objeto
                    if (response['data'].length == 0) {
                        return;
                    }

                    var userContact = response['data']['contact'];
                    var conversation = response['data']['conversation'];

                    // Borramos a la persona que hemos agregado de People
                    context.commit("removePeopleById", userContact.id);

                    // Agregamos el nuevo contacto usando los datos recibidos
                    context.commit("addContact", userContact);
                    context.commit('addConversation', conversation);

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
                        context.dispatch('messageToConversation', message);
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
