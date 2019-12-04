<template>
  <b-col class="p-0 side">
    <b-row class="newMessage-heading">
      <b-row class="newMessage-main">
        <b-col cols="2" sm="2" class="newMessage-back">
          <i @click="hide" class="fa fa-arrow-left" aria-hidden="true"></i>
        </b-col>
        <b-col cols="10" sm="10" class="newMessage-title">Find new contacts</b-col>
      </b-row>
    </b-row>

    <b-row class="composeBox">
      <b-col cols="12" class="composeBox-inner">
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
      </b-col>
    </b-row>

    <div class="compose-sideBar myhc">
      <user-list-item
        v-for="user of filterPeople"
        v-bind:key="user.user_id"
        :user_id="user.user_id"
        :avatar="user.avatar"
        :name="user.name"
      ></user-list-item>
    </div>
  </b-col>
</template>

<script>
import { mapState } from "vuex";
import PeopleListItem from "./PeopleListItem.vue";
export default {
  name: "PeopleList",
  data: function() {
    return {
      keyword: ""
    };
  },
  computed: {
    ...mapState(["people"]),
    filterPeople: function() {
      return this.people.filter(people =>
        people.name.toLowerCase().includes(this.keyword.toLowerCase())
      );
    }
  },
  methods: {
    hide() {
      var div = document.getElementsByClassName("side-people")[0];
      div.style.left = "-100%";
    }
  },
  components: { "user-list-item": PeopleListItem }
};
</script>
<style>
</style>