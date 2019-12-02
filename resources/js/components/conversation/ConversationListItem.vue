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
  props: ["id"],
  computed: {
    ...mapState(["conversations", "contacts", "appUser"]),
    avatarPath: function() {
      var conversation = this.$store.getters.getConversationById(this.id);

      var userId = -1;
      if (conversation.user_a_id == this.appUser.id) {
        userId = conversation.user_b_id;
      } else {
        userId = conversation.user_a_id;
      }

      var user = this.$store.getters.getPeopleById(userId);

      // Usuario no encontrado
      if (typeof user === "undefined") {
        user = this.$store.getters.getContactById(userId);
      }

      return "/images/" + user.avatar;
    },
    contactName: function() {
      var conversation = this.$store.getters.getConversationById(this.id);

      var userId = -1;
      if (conversation.user_a_id == this.appUser.id) {
        userId = conversation.user_b_id;
      } else {
        userId = conversation.user_a_id;
      }

      var user = this.$store.getters.getPeopleById(userId);

      if (typeof user !== "undefined") {
        return user.phone;
      }

      user = this.$store.getters.getContactById(userId);
      return user.name;
    },
    lastMessageDate: function() {
      for (var conversation of this.conversations) {
        if (conversation.messages.length > 0) {
          if (conversation.id == this.id) {
            return conversation.messages[conversation.messages.length - 1]
              .sent_at;
          }
        }
      }
      return "Never";
    }
  }
};
</script>
<style>
</style>
