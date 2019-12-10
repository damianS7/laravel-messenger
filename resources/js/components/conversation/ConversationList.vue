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
import { mapState, mapGetters, mapActions } from "vuex";
import ConversationListItem from "./ConversationListItem";

export default {
  name: "ConversationList",
  data: function() {
    return {
      keyword: ""
    };
  },
  methods: {
    ...mapActions(["selectConversationById", "selectUserById"]),
    selectConversation(conversation) {
      // Seleccionamos la conversacion para que se carguen los mensajes.
      this.selectConversationById({ conversationId: conversation.id });

      // Obtnemos el usuario que esta al otro lado de la conversacion
      var conversationUser = this.$store.getters
        .getUserFromSelectedConversation;

      // Seleccionamos el usuario
      this.selectUserById({ userId: conversationUser.id });
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
