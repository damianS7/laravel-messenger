<template>
  <b-col class="side">
    <div class="sideBar-conversations myhc">
      <conversation-list-item
        v-for="conversation of filterConversations"
        v-bind:key="conversation.id"
        :id="conversation.id"
        :avatar="avatarPath"
        :name="contactName"
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
    selectConversation(conversationId) {
      this.$store.commit("selectConversationById", { conversationId });

      var conversation = this.$store.getters.getConversationById(
        conversationId
      );

      var user = this.$store.getters.getPeopleById(
        conversation.messages[0].author_id
      );

      // this.$store.commit("selectContact", conversation);
    }
  },
  computed: {
    ...mapState(["conversations"]),
    contactName: function() {
      // var user = this.$store.getters.getPeopleById();
    },
    avatarPath: function() {
      // var user = this.$store.getters.getPeopleById();
    },
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
