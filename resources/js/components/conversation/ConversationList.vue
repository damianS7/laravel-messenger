<template>
  <b-col class="side">
    <div class="sideBar-conversations myhc">
      <conversation-list-item
        v-for="conversation of filterConversations"
        v-bind:key="conversation.id"
        :id="conversation.id"
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
      console.log("Selected conver id: " + conversation.user_a_id);

      // Buscamos el usuario al otro lado de la conversacion
      // if user_a_id == appUser.id userId = user_b_id
      // conversation.user_a_id findPeople
      // conversation.user_a_id findContacts
      // Seleccionamos el usuario para poder cargar el perfil

      //var user = this.$store.getters.getPeopleById(
      //        conversation.messages[0].author_id
      //);

      // this.$store.commit("selectContact", conversation);
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
