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
          <span v-if="alias !== null" class="name-meta">{{ alias }}</span>
          <span v-else class="name-meta">{{ name }}</span>
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
  props: ["name", "id", "index", "alias", "contact_id", "avatar"],
  methods: {},
  computed: {
    ...mapState(["contacts"]),
    avatarPath: function() {
      return "/images/" + this.avatar;
    },
    lastMessageDate: function() {
      for (var contact of this.contacts) {
        var conversation = this.$store.getters.getConversationById(
          contact.conversation_id
        );

        if (typeof conversation !== "undefined") {
          if (conversation.messages.length > 0) {
            if (contact.id == this.contact_id) {
              return conversation.messages[conversation.messages.length - 1]
                .sent_at;
            }
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
