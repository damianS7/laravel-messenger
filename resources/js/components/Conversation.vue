<template>
  <div class="side">
    <contact-profile></contact-profile>

    <div class="message" id="conversation">
      <conversation-message
        v-for="(message, index) of messages"
        v-bind:key="index"
        :message="message.content"
        :name="message.name"
        :isSender="message.isSender"
      ></conversation-message>
    </div>

    <div class="row reply">
      <div class="col-1 reply-emojis">
        <i class="fa fa-smile-o fa-2x"></i>
      </div>
      <div class="col-10 reply-main">
        <textarea class="form-control" rows="1" id="comment"></textarea>
      </div>

      <div class="col-1 reply-send">
        <i class="fa fa-send fa-2x" aria-hidden="true"></i>
      </div>
    </div>
  </div>
</template>

<script>
import ContactProfile from "./ContactProfile.vue";
import ConversationMessage from "./ConversationMessage.vue";
import { mapState } from "vuex";
export default {
  mounted() {
    console.log("Conversation mounted.");
  },
  computed: {
    ...mapState(["messages"])
  },
  components: {
    "contact-profile": ContactProfile,
    "conversation-message": ConversationMessage
  }
};
</script>
<style>
</style>