<template>
  <b-col class="side">
    <div class="sideBar-conversations myhc">
      <conversation-list-item
        v-for="conversation of filterConversations"
        v-bind:key="conversation.id"
        :id="conversation.id"
        :conversation="conversation"
        @click.native="selectConversation(conversation)"
      ></conversation-list-item>
    </div>
  </b-col>
</template>

<script>
import { mapState, mapGetters } from "vuex";
import ConversationListItem from "./ConversationListItem";

export default {
  name: "ConversationList",
  data: function() {
    return {
      keyword: ""
    };
  },
  methods: {
    selectConversation(conversation) {
      // Seleccionamos la conversacion para que se carguen los mensajes.
      this.$store.commit("selectConversationById", {
        conversationId: conversation.id
      });

      var userB = this.getUserFromConversation(conversation);
      var user = this.$store.getters.getUserById(userB.id);
      this.$store.state.selectedUser = user;
      //this.$store.commit("selectContact", userId);
    },
    getUserFromConversation(conversation) {
      if (conversation.users[0].id == this.appUser.id) {
        return conversation.users[1];
      }

      return conversation.users[0];
    }
  },
  computed: {
    ...mapState(["conversations", "appUser"]),
    filterConversations: function() {
      return this.conversations.filter(conversation => {
        return conversation.messages.length > 0;
      });
    }
  },
  components: { "conversation-list-item": ConversationListItem }
};
</script>
<style>
</style>
