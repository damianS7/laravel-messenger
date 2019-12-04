<template>
  <div class="side">
    <b-col class="icon-menu">
      <a @click="insertIcon" href="#" v-bind:key="index" v-for="(icon, index) of icons">{{ icon }}</a>
    </b-col>
    <div class="message" id="conversation">
      <div v-if="!userSelected" class="row message-previous">
        <div class="col-sm-12 previous">Select a conversation to load some messages</div>
      </div>

      <div v-if="emptyChat" class="row message-previous">
        <div class="col-sm-12 previous">
          Don't be shy! Say something to
          <strong>{{ selectedUser.name }}</strong>
        </div>
      </div>
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
      <div class="col-3 col-sm-2 col-lg-1 reply-emojis">
        <i @click="iconMenu" class="fa fa-smile-o fa-2x"></i>
      </div>
      <div class="col-6 col-sm-8 col-lg-10 reply-main">
        <textarea
          v-on:keyup.enter="sendMessage"
          v-model="input"
          class="form-control"
          rows="1"
          id="comment"
        ></textarea>
      </div>

      <div class="col-3 col-sm-2 col-lg-1 reply-send">
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
      icons: [
        "😀",
        "😁",
        "😂",
        "😅",
        "😈",
        "😇",
        "😍",
        "😎",
        "😘",
        "😥",
        "😭",
        "😱",
        "😷",
        "😰"
      ],
      input: "",
      iconMenuVisible: false
    };
  },
  methods: {
    iconMenu() {
      var div = document.getElementsByClassName("icon-menu")[0];
      if (!this.iconMenuVisible) {
        div.style.bottom = "5%";
        this.iconMenuVisible = true;
      } else {
        this.iconMenuVisible = false;
        div.style.bottom = "-100%";
      }
    },
    insertIcon(event) {
      this.input += event.target.innerHTML;
    },
    isSender(author_id) {
      if (author_id == this.appUser.user_id) {
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
    ...mapState(["selectedConversation", "appUser", "selectedUser"]),
    ...mapGetters([
      "getSelectedConversation",
      "getSelectedConversationMessages"
    ]),
    userSelected: function() {
      if (typeof this.selectedUser.user_id === "undefined") {
        return false;
      }
      return true;
    },
    emptyChat: function() {
      if (typeof this.selectedUser.user_id === "undefined") {
        return false;
      }

      if (this.getSelectedConversationMessages.length > 0) {
        return false;
      }

      return true;
    }
  },
  components: {
    "conversation-message": ConversationMessage
  }
};
</script>
<style scoped>
.icon-menu {
  position: absolute;
  bottom: -100%;
  left: 35px;
  width: 300px;
  z-index: 3;
  background-color: antiquewhite;
  border-radius: 4px;
  padding: 5px;
  border: 2px solid black;
  -webkit-transition: bottom 1s ease;
  transition: bottom 1s ease;
}
</style>