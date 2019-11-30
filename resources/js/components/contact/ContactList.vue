<template>
  <b-col class="side">
    <div class="row newMessage-heading">
      <div class="row newMessage-main">
        <div class="col-sm-2 col-xs-2 newMessage-back">
          <i @click="hide" class="fa fa-arrow-left" aria-hidden="true"></i>
        </div>
        <div class="col-sm-10 col-xs-10 newMessage-title">New Chat</div>
      </div>
    </div>

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
      <contact-list-item
        v-for="contact of filterContacts"
        v-bind:key="contact.user_id"
        :contact_id="contact.user_id"
        :name="contact.name"
        :alias="contact.alias"
        :phone="contact.phone"
        @click.native="selectContact(contact)"
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
    selectContact(contact) {
      this.$store.commit("setSelectedContact", contact);
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
