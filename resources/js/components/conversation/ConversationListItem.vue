<template>
  <div class="row sideBar-body">
    <div class="col-3 sideBar-avatar">
      <div class="avatar-icon">
        <img src="https://bootdey.com/img/Content/avatar/avatar4.png" />
      </div>
    </div>
    <div class="col-9 sideBar-main">
      <div class="row h-auto">
        <div class="col-12 sideBar-name">
          <span class="name-meta">Unkown</span>
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
    ...mapState(["conversations"]),
    lastMessageDate: function() {
      for (var conversation of this.conversations) {
        if (typeof conversation !== "undefined") {
          if (conversation.messages.length > 0) {
            if (conversation.conversation_id == this.id) {
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
