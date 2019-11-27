<template>
  <div class="side">
    <div class="message" id="conversation">
      <conversation-message
        v-for="(message, index) of selected_contact.conversation.messages"
        v-bind:key="index"
        :alias="message.alias"
        :author_id="message.author_id"
        :message="message.content"
        :name="message.name"
        :sent_at="message.sent_at"
        :isSender="isSender(message.author_id)"
      ></conversation-message>
    </div>

    <div class="row reply">
      <div class="col-1 reply-emojis">
        <i class="fa fa-smile-o fa-2x"></i>
      </div>
      <div class="col-10 reply-main">
        <textarea
          v-on:keyup.enter="sendMessage"
          v-model="input"
          class="form-control"
          rows="1"
          id="comment"
        ></textarea>
      </div>

      <div class="col-1 reply-send">
        <i class="fa fa-send fa-2x" aria-hidden="true"></i>
      </div>
    </div>
  </div>
</template>

<script>
import ConversationMessage from "./ConversationMessage.vue";
import { mapState } from "vuex";
export default {
  name: "Conversation",
  data: function() {
    return {
      input: ""
    };
  },
  methods: {
    isSender(author_id) {
      if (author_id == this.$store.state.profile.user_id) {
        return true;
      }
      return false;
    },
    sendMessage(event) {
      this.$store.dispatch("postMessage", this.input);
      this.input = "";
    },
    appendMessage(message) {}
  },
  updated() {
    var div = document.getElementById("conversation");
    div.scrollTop = div.scrollHeight;
  },
  computed: {
    ...mapState(["selected_contact"])
  },
  components: {
    "conversation-message": ConversationMessage
  }
};
</script>
<style>
</style>