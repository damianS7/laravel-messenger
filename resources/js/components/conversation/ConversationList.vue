<template>
  <b-col class="side">
    <b-row class="searchBox">
      <div class="col-12 searchBox-inner">
        <div class="form-group has-feedback">
          <input
            id="searchText"
            type="text"
            class="form-control"
            name="searchText"
            placeholder="Search"
            v-model="keyword"
          />
          <span class="glyphicon glyphicon-search form-control-feedback"></span>
        </div>
      </div>
    </b-row>

    <div class="sideBar myhc">
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
