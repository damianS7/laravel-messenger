<template>
  <b-col class="side">
    <b-row class="newMessage-heading">
      <b-row class="newMessage-main">
        <b-col cols="2" sm="2" class="newMessage-back">
          <i @click="hide" class="fa fa-arrow-left" aria-hidden="true"></i>
        </b-col>
        <b-col cols="10" sm="10" class="newMessage-title">New Chat</b-col>
      </b-row>
    </b-row>

    <b-row class="searchBox">
      <b-col cols="12" class="searchBox-inner">
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
      </b-col>
    </b-row>

    <div class="compose-sideBar myhc">
      <contact-list-item
        v-for="user of filterContacts"
        v-bind:key="user.id"
        :contact_id="user.id"
        :name="user.name"
        :phone="user.phone"
        :avatar="user.profile.avatar"
        :alias="user.profile.alias"
        @click.native="selectContact(user)"
      ></contact-list-item>
    </div>
  </b-col>
</template>

<script>
import { mapState, mapGetters } from "vuex";
import ContactListItem from "./ContactListItem";

export default {
  name: "ContactList",
  data: function() {
    return {
      keyword: ""
    };
  },
  methods: {
    selectContact(user) {
      // Buscamos la conversacion asociada a este usuario
      var conversation = this.$store.getters.getConversationWith(user.id);

      this.$store.dispatch("selectConversationById", {
        conversationId: conversation.id
      });

      this.$store.dispatch("selectUserById", { userId: user.id });
    },
    hide() {
      var div = document.getElementsByClassName("side-contacts")[0];
      div.style.left = "-100%";
    }
  },
  computed: {
    ...mapState(["contacts", "conversations"]),
    filterContacts: function() {
      return this.contacts.filter(contact =>
        contact.name.toLowerCase().includes(this.keyword.toLowerCase())
      );
    }
  },
  components: {
    "contact-list-item": ContactListItem
  }
};
</script>
<style>
</style>
