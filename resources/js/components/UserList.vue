<template>
  <b-col class="p-0">
    <div class="row newMessage-heading">
      <div class="row newMessage-main">
        <div class="col-sm-2 col-xs-2 newMessage-back">
          <i @click="hide" class="fa fa-arrow-left" aria-hidden="true"></i>
        </div>
        <div class="col-sm-10 col-xs-10 newMessage-title">New Chat</div>
      </div>
    </div>

    <div class="row composeBox">
      <div class="col-sm-12 composeBox-inner">
        <div class="form-group has-feedback">
          <input
            id="composeText"
            type="text"
            class="form-control"
            name="searchText"
            placeholder="Search People"
            v-model="keyword"
          />
          <span class="glyphicon glyphicon-search form-control-feedback"></span>
        </div>
      </div>
    </div>

    <div class="compose-sideBar">
      <user-list-item
        v-for="(user, index) of filterUsers"
        v-bind:key="index"
        :name="user.name"
        :phone="user.phone"
      ></user-list-item>
    </div>
  </b-col>
</template>

<script>
import { mapState } from "vuex";
import UserListItem from "./UserListItem.vue";
export default {
  data: function() {
    return {
      keyword: ""
    };
  },
  computed: {
    ...mapState(["messenger_users"]),
    filterUsers: function() {
      return this.messenger_users.filter(contact =>
        contact.name.toLowerCase().includes(this.keyword.toLowerCase())
      );
    }
  },
  methods: {
    hide() {
      var div = document.getElementsByClassName("side-two")[0];
      div.style.left = "-100%";
    }
  },
  components: { "user-list-item": UserListItem }
};
</script>
<style>
</style>