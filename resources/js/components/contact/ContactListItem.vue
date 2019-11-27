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
          <span v-if="alias !== null" class="name-meta">{{ alias }}</span>
          <span v-else class="name-meta">{{ name }}</span>
        </div>
      </div>
      <div class="row">
        <div class="col-12 sideBar-time">
          <span class="time-meta">{{ lastMessageDate }}</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapState } from "vuex";
export default {
  props: ["name", "id", "index", "alias"],
  methods: {},
  computed: {
    ...mapState(["contacts"]),
    lastMessageDate: function() {
      var contacts = this.$store.state.contacts;
      for (var index in contacts) {
        var contact = contacts[index];
        var count = contact.conversation.messages.length;
        return contact.conversation.messages[count - 1].sent_at;
      }
      //return this.$store.getters.lastMessageTime;
    }
  }
};
</script>
<style>
</style>
