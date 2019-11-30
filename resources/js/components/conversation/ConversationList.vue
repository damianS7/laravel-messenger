<template>
  <b-col class="side">
    <div class="sideBar-conversations myhc">
      <conversation-list-item
        v-for="conversation of conversations"
        v-bind:key="conversation.id"
        :conversation_id="conversation.id"
        @click.native="selectConversation(conversation.id)"
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
    selectConversation(contact) {
      // this.$store.commit("setSelectedConversation", contact);
    }
  },
  computed: {
    ...mapState(["conversations"]),
    filterContacts: function() {
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
