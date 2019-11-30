<template>
  <div class="side">
    <div class="message" id="conversation">
      <conversation-message
        v-for="(message, index) of getSelectedConversationMessages"
        v-bind:key="index"
        :author_id="message.author_id"
        :alias="message.author_alias"
        :message="message.content"
        :name="message.author_name"
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
        <i class="fa fa-send fa-2x" aria-hidden="true" @click="sendMessage"></i>
      </div>
    </div>
  </div>
</template>

<script>
import ConversationMessage from "./ConversationMessage";
import { mapState, mapGetters } from "vuex";
export default {
  name: "Conversation",
  data: function() {
    return {
      input: ""
    };
  },
  methods: {
    isSender(author_id) {
      if (author_id == this.appUser.id) {
        return true;
      }
      return false;
    },
    sendMessage(event) {
      // No hay nada que enviar
      if (this.input.length == 0) {
        return;
      }

      // Ningun usuario seleccionado
      if (typeof this.selectedConversation.id !== "undefined") {
        this.$store.dispatch("postMessage", this.input);
      }

      // Borrar texto del input
      this.input = "";
    }
  },
  updated() {
    var div = document.getElementById("conversation");
    div.scrollTop = div.scrollHeight;
  },
  computed: {
    ...mapState(["selectedConversation", "appUser"]),
    ...mapGetters([
      "getSelectedConversation",
      "getSelectedConversationMessages"
    ])
  },
  components: {
    "conversation-message": ConversationMessage
  }
};
</script>
<style>
</style>