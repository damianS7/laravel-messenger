<template>
  <b-row class="sideBar-body">
    <b-col cols="5" sm="4" class="sideBar-avatar">
      <div class="avatar-icon">
        <img :src="avatarPath" />
      </div>
    </b-col>
    <b-col cols="7" sm="8" class="sideBar-main">
      <b-row class="h-auto">
        <b-col cols="12" class="sideBar-name">
          <span v-if="alias !== null" class="name-meta">{{ alias }}</span>
          <span v-else class="name-meta">{{ name }}</span>
        </b-col>
      </b-row>
      <b-row>
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
  props: ["name", "alias", "user_id", "avatar"],
  computed: {
    ...mapState(["contacts"]),
    ...mapGetters(["getConversationWith"]),
    avatarPath: function() {
      return "/images/" + this.avatar;
    },
    lastMessageDate: function() {
      // Buscamos la conversacion asociada a este usuario
      var conversation = this.getConversationWith(this.user_id);

      // Si la conversacion existe ...
      if (typeof conversation !== "undefined") {
        // Si en la conversacion hay mensajes ...
        if (conversation.messages.length > 0) {
          // Buscamos el ultimo mensaje de la conversacion y obtenemos la fecha
          return conversation.messages[conversation.messages.length - 1]
            .sent_at;
        }
      }
      return "Never";
    }
  }
};
</script>
<style>
</style>
