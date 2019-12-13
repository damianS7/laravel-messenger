<template>
  <b-row class="sideBar-body">
    <b-col cols="5" sm="4" class="sideBar-avatar">
      <div class="avatar-icon">
        <img :src="avatarPath" />
      </div>
    </b-col>
    <b-col cols="7" sm="8" class="sideBar-main">
      <b-row class="row h-auto">
        <b-col cols="12" class="sideBar-name">
          <span class="name-meta">{{ contactName }}</span>
        </b-col>
      </b-row>
      <b-row class="row">
        <b-col cols="12" class="sideBar-time">
          <span class="time-meta">
            <i>{{ lastMessageDate }}</i>
          </span>
        </b-col>
      </b-row>
    </b-col>
  </b-row>
</template>

<script>
import { mapState, mapGetters } from "vuex";
export default {
  name: "ConversationListItem",
  props: ["id", "conversation"],
  data: function() {
    return {};
  },
  methods: {
    // Devuelve el otro participante de la conversacion.
    getUserFromConversation() {
      // Si el participante [0] somos nosotros, devolvemos el participante [1]
      if (this.conversation.participants[0].id == this.appUser.id) {
        return this.conversation.participants[1];
      }

      return this.conversation.participants[0];
    }
  },
  computed: {
    ...mapState(["conversations", "contacts", "appUser"]),
    ...mapGetters(["getUserById", "isContact"]),
    // Path del avatar que aparece en el item de la conversacion
    avatarPath: function() {
      var userConversation = this.getUserFromConversation();
      var user = this.getUserById(userConversation.id);
      return "/images/" + user.profile.avatar;
    },
    // Nombre de usuario que aparece en el item de la conversacion
    contactName: function() {
      var userConversation = this.getUserFromConversation();
      var user = this.getUserById(userConversation.id);

      // Si la conversacion es de un usuario, mostramos el alias
      if (this.isContact(user.id)) {
        return user.profile.alias;
      }

      // Si no es contacto mostramos el telefono
      return user.phone;
    },
    // Fecha del ultimo mensaje de la conversacion
    lastMessageDate: function() {
      // Si la conversacion tiene mensajes ...
      if (this.conversation.messages.length > 0) {
        return this.conversation.messages[this.conversation.messages.length - 1]
          .sent_at;
      }
      return "Never";
    }
  }
};
</script>
<style>
</style>
