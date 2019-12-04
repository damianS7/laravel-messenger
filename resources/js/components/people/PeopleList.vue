<template>
  <b-col class="p-0 side">
    <div class="row newMessage-heading">
      <div class="row newMessage-main">
        <div class="col-sm-2 col-xs-2 newMessage-back">
          <i @click="hide" class="fa fa-arrow-left" aria-hidden="true"></i>
        </div>
        <div class="col-sm-10 col-xs-10 newMessage-title">Find new contacts</div>
      </div>
    </div>

    <div class="row composeBox">
      <div class="col-12 composeBox-inner">
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