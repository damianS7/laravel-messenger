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
  props: ["id", "avatarPath"],
  computed: {
    ...mapState(["conversations", "contacts"]),
    avatarPath: function() {
      var conversation = this.$store.getters.getConversationById(this.id);
      var user = this.$store.getters.getPeopleById(
        conversation.messages[0].author_id
      );
      return "/images/" + user.avatar;
    },
    contactName: function() {
      // Get contactBy??s
      for (var contact of this.contacts) {
        if (typeof conversation !== "undefined") {
          if (contact.conversation_id == this.id) {
            return contact.name;
          }
        }
      }

      var conversation = this.$store.getters.getConversationById(this.id);
      var user = this.$store.getters.getPeopleById(
        conversation.messages[0].author_id
      );
      return user.phone;
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
