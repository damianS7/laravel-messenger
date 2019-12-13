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
        // Obtiene la conversacion entre 2 usuarios usando el id de usuario
        getConversationWith: (state, getters) => (userId) => {
            return state.conversations.find(conversation =>
                conversation.participants[0].id === userId
                || conversation.participants[1].id === userId);
        },
        // Obtiene una conversacion directamente con el id de conversacion
        getConversationById: (state, getters) => (conversationId) => {
            return state.conversations.find(
                conversation => conversation.id === conversationId);
        },
        // Devuelve el index en el que se encuentra una conversacion en el array
        getConversationIndexById: (state, getters) => (conversationId) => {
            return state.conversations.findIndex(
                conversation => conversation.id === conversationId);
        },
        // Obtiene un usuario del array "People" es decir NO CONTACTOS
        getPeopleById: (state, getters) => (userId) => {
            return state.people.find(user => user.id === userId);
        },
        // Obtiene un usuario del array "Contacts"
        getContactById: (state, getters) => (userId) => {
            return state.contacts.find(user => user.id === userId);
        },
        // Obtiene un usuario buscando en People y Contacts
        getUserById: (state, getters) => (userId) => {
            var user = getters.getContactById(userId);
            if (typeof user === 'undefined') {
                return getters.getPeopleById(userId);
            }
            return user;
        },
        // Comprueba si un usuario esta en nuestra lista de contactos
        isContact: (state, getters) => (userId) => {
            if (typeof getters.getContactById(userId) === 'undefined') {
                return false;
            }
            return true;
        },
        // Devuelve el usuario al otro lado de la conversacion (receiver)
        // El metodo devuelve el usuario cuyo ID no coincide con el del usuario
        // logeado en la aplicacion (nosotros).
        getUserFromSelectedConversation: (state, getters) => {
            // Si nuestro ID de usuario es igual al del primer participante de
            // la conversacion, devolvemos el segundo usuario.
            if (state.appUser.id === state.selectedConversation.participants[0].id) {
                return state.selectedConversation.participants[1];
            }
            return state.selectedConversation.participants[0];
        },
    },
    mutations: {
        // Asigna el usuario de la app
        SET_USER(state, user) {
            state.appUser = user;
        },
        // Asigna los contactos del usuario
        SET_CONTACTS(state, users) {
            state.contacts = users;
        },
        // Asigna los usuarios disponibles en la app que no son contactos
        SET_PEOPLE(state, users) {
            state.people = users;
        },
        // Asigna las conversaciones
        SET_CONVERSATIONS(state, conversations) {
            state.conversations = conversations;
        },
        // Selecciona un usuario
        SET_SELECTED_USER(state, user) {
            state.selectedUser = user;
        },
        // Selecciona una conversacion
        SET_SELECTED_CONVERSATION(state, conversation) {
            state.selectedConversation = conversation;
        },
        // Borra un usuario de contacts
        REMOVE_CONTACT(state, index) {
            Vue.delete(state.contacts, index);
        },
        // Borra un usuario de people
        REMOVE_PEOPLE(state, index) {
            Vue.delete(state.people, index);
        },
        // Agrega un usuario a contacts
        ADD_CONTACT(state, user) {
            state.contacts.push(user);
        },
        // Agrega una nueva conversacion al array que contiene las conversaciones
        ADD_CONVERSATION(state, conversation) {
            state.conversations.push(conversation);
        },
        // Agrega un usuario a people
        ADD_PEOPLE(state, user) {
            state.people.push(user);
        },
        // Mejorar metodo, pasar dos parametros?
        ADD_MESSAGE(state, { index, message }) {
            // Agregamos el mensaje a la conversacion
            state.conversations[index].messages.push(
                {
                    id: message.id,
                    conversation_id: message.conversation_id,
                    sent_at: message.sent_at,
                    author_id: message.author_id,
                    content: message.content
                }
            );
        },
    },
    actions: {
        // Selecciona el usuario con el id indicado
        selectUserById(context, userId) {
            var user = context.getters.getUserById(userId);
            context.commit('SET_SELECTED_USER', user);
        },
        // Selecciona la conversacion con el id indicado
        selectConversationById(context, conversationId) {
            var conversation = context.getters.getConversationById(conversationId);
            context.commit('SET_SELECTED_CONVERSATION', conversation);
        },
        // Elimina el contacto con el id indicado
        removeContactById(context, userId) {
            var contactIndex = context.state.contacts.findIndex(
                user => user.id === userId);
            context.commit('REMOVE_CONTACT', contactIndex);
        },
        // Elimina un usuario del array people con el id indicado
        removePeopleById(context, userId) {
            var peopleIndex = context.state.people.findIndex(
                user => user.id === userId);
            context.commit('REMOVE_PEOPLE', peopleIndex);
        },
        // Este metodo realiza una peticion al backend y recibe los datos necesarios
        // para inicializar la aplicacion
        fetchData(context) {
            axios.get("http://127.0.0.1:8000/messenger/fetch").then(function (response) {
                // Si el request tuvo exito (codigo 200)
                if (response.status == 200) {
                    var data = response["data"];
                    // Userdata
                    context.commit('SET_USER', data['app_user']);
                    // People
                    context.commit('SET_PEOPLE', data['people']);
                    // Contacts
                    context.commit('SET_CONTACTS', data['contacts']);
                    // Conversations
                    context.commit('SET_CONVERSATIONS', data['conversations']);
                }
            });
        },
        // Envia los datos del perfil actualizado a la base de datos
        saveProfile(context) {
            var profile = context.state.appUser.profile;
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
                    // Si no hay datos ...
                    if (response['data'].length == 0) {
                        return;
                    }

                    // Enviamos el mensaje a la conversacion
                    var message = response['data']['message'];
                    context.dispatch('messageToConversation', message);
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
                        // Enviamos el mensaje a la conversacion
                        context.dispatch('messageToConversation', message);
                    }
                }

            });
        },
        // Este metodo envia los mensajes a la conversacion correspondiente.
        // Tambien agrega la conversacion si no existe al array conversations.
        messageToConversation(context, message) {
            // Index de la conversacion a la que enviar los mensajes
            var conversationIndex = context.getters.getConversationIndexById(
                message.conversation_id);

            // Si el index es -1, la conversacion no se encontro.
            if (conversationIndex === -1) {
                // Agregamos el campo de los mensajes al objeto
                message.conversation.messages = [];
                // Agregamos la conversacion
                context.commit('ADD_CONVERSATION', message.conversation);
                // Buscamos de nuevo la conversacion
                conversationIndex = context.getters.getConversationIndexById(
                    message.conversation_id);
            }

            // Agregamos el mensaje a la conversacion indicada
            context.commit('ADD_MESSAGE', { index: conversationIndex, message });
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
                    context.dispatch("removePeopleById", userContact.id);

                    // Agregamos el nuevo contacto usando los datos recibidos
                    context.commit("ADD_CONTACT", userContact);

                    // Comprobamos que no exista ninguna conversacion con el mismo id
                    if (typeof context.getters.getConversationById(
                        conversation.id) === 'undefined') {
                        context.commit('ADD_CONVERSATION', conversation);
                    }
                }
            });
        },
        // Peticion para borrar un contacto
        deleteContact(context, data) {
            axios.post("http://127.0.0.1:8000/contacts/" + data.userId, {
                _method: "delete"
            }).then(function (response) {
                // Si el request tuvo exito (codigo 200)
                if (response.status == 204) {
                    var user = context.getters.getUserById(data.userId);

                    // Borramos el usuario de contactos
                    context.dispatch("removeContactById", user.id);

                    // Movemos el contacto a people
                    context.commit('ADD_PEOPLE', user);
                }
            });
        },
    }
})
