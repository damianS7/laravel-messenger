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
import { mapState } from "vuex";
export default {
  props: ["name", "id", "index", "alias", "user_id", "avatar"],
  methods: {},
  computed: {
    ...mapState(["contacts"]),
    avatarPath: function() {
      return "/images/" + this.avatar;
    },
    lastMessageDate: function() {
      // Buscamos la conversacion asociada a este usuario
      var conversation = this.$store.getters.getConversationWith(this.user_id);

      if (typeof conversation !== "undefined") {
        if (conversation.messages.length > 0) {
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
