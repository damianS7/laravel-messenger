<template>
  <div class="row sideBar-body">
    <div class="col-3 sideBar-avatar">
      <div class="avatar-icon">
        <img :src="avatarPath" />
      </div>
    </div>
    <div class="col-9 sideBar-main">
      <div class="row h-auto">
        <div class="col-12 sideBar-name">
          <span class="name-meta">{{ contactName }}</span>
        </div>
      </div>
      <div class="row">
        <div class="col-12 sideBar-time">
          <span class="time-meta">
            <i>{{ lastMessageDate }}</i>
          </span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapState } from "vuex";
export default {
  name: "ConversationListItem",
  props: ["id", "conversation"],
  data: function() {
    return {};
  },
  methods: {
    getUserConversation() {
      if (this.conversation.user_a_id == this.appUser.id) {
        return this.conversation.user_b_id;
      }

      return this.conversation.user_a_id;
    }
  },
  computed: {
    ...mapState(["conversations", "contacts", "appUser"]),
    avatarPath: function() {
      var userId = this.getUserConversation();
      var user = this.$store.getters.getUserById(userId);
      return "/images/" + user.avatar;
    },
    contactName: function() {
      var userId = this.getUserConversation();
      var user = this.$store.getters.getUserById(userId);

      // Si la conversacion es de un usuario, mostramos el nombre
      if (this.$store.getters.isContact(userId)) {
        return user.name;
      }

      // Si no es contacto mostramos el telefono
      return user.phone;
    },
    lastMessageDate: function() {
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
