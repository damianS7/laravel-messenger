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
      <contact-list-item
        v-for="(contact, index) of filterContacts"
        v-bind:key="index"
        :contact="contact"
        :name="contact.name"
        :phone="contact.phone"
        @click.native="fetchContactData(contact)"
      ></contact-list-item>
    </div>
  </b-col>
</template>

<script>
import { mapState } from "vuex";
import ContactListItem from "./ContactListItem.vue";

export default {
  data: function() {
    return {
      keyword: ""
    };
  },
  methods: {
    fetchContactData(contact) {
      this.$store.commit("setSelectedContact", contact);
      this.$store.dispatch("fetchConversation", contact.id);
    }
  },
  computed: {
    ...mapState(["contacts"]),
    filterContacts: function() {
      return this.contacts.filter(contact =>
        contact.name.toLowerCase().includes(this.keyword.toLowerCase())
      );
    }
  },
  components: { "contact-list-item": ContactListItem }
};
</script>
<style>
</style>
