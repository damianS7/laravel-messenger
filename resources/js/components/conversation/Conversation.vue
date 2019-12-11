<template>
  <div class="side">
    <b-col class="icon-menu">
      <a @click="insertIcon" href="#" v-bind:key="index" v-for="(icon, index) of icons">{{ icon }}</a>
    </b-col>
    <div class="message" id="conversation">
      <b-row v-if="!userSelected" class="message-previous">
        <b-col cols="12" class="previous">Select a conversation to load some messages</b-col>
      </b-row>

      <div v-if="emptyChat" class="row message-previous">
        <b-col sm="12" class="previous">
          Don't be shy! Say something to
          <strong>{{ selectedUser.name }}</strong>
        </b-col>
      </div>
      <conversation-message
        v-for="(message, index) of selectedConversation.messages"
        v-bind:key="index"
        :author_id="message.author_id"
        :alias="senderAlias(message.author_id)"
        :message="message.content"
        :name="senderName(message.author_id)"
        :sent_at="message.sent_at"
        :isSender="isSender(message.author_id)"
      ></conversation-message>
    </div>

    <b-row class="reply">
      <b-col cols="3" sm="2" lg="1" class="reply-emojis">
        <i @click="iconMenu" class="fa fa-smile-o fa-2x"></i>
      </b-col>
      <b-col cols="6" sm="8" lg="10" class="reply-main">
        <textarea
          v-on:keyup.enter="sendMessage"
          v-model="input"
          class="form-control"
          rows="1"
          id="comment"
        ></textarea>
      </b-col>

      <b-col cols="3" sm="2" lg="1" class="reply-send">
        <i class="fa fa-send fa-2x" aria-hidden="true" @click="sendMessage"></i>
      </b-col>
    </b-row>
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
    senderName(senderId) {
      if (this.appUser.id === senderId) {
        return this.appUser.name;
      }

      var user = this.getUserById(senderId);
      return user.name;
    },
    senderAlias(senderId) {
      if (this.appUser.id === senderId) {
        return this.appUser.profile.alias;
      }

      var user = this.getUserById(senderId);
      return user.profile.alias;
    },
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
    ...mapState(["selectedConversation", "appUser", "selectedUser"]),
    ...mapGetters(["getUserById"]),
    userSelected: function() {
      if (typeof this.selectedUser.id === "undefined") {
        return false;
      }
      return true;
    },
    emptyChat: function() {
      if (typeof this.selectedUser.id === "undefined") {
        return false;
      }

      if (this.selectedConversation.messages.length > 0) {
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