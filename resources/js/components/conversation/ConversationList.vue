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

      var userId = this.$store.getters.getUserIdFromSelectedConversation({});
      var user = this.$store.getters.getUserById(userId);
      this.$store.state.selectedUser = user;
      //this.$store.commit("selectContact", userId);
    }
  },
  computed: {
    ...mapState(["conversations"]),
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
