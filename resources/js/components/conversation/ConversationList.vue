<template>
  <b-col class="side">
    <div class="sideBar-conversations myhc">
      <conversation-list-item
        v-for="conversation of conversations"
        v-bind:key="conversation.conversation_id"
        :id="conversation.conversation_id"
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
      this.$store.commit("setSelectedConversation", conversation);
    }
  },
  computed: {
    ...mapState(["conversations"]),
    filterConversations: function() {
      return this.contacts.filter(contact =>
        contact.name.toLowerCase().includes(this.keyword.toLowerCase())
      );
    }
  },
  components: { "conversation-list-item": ConversationListItem }
};
</script>
<style>
</style>
