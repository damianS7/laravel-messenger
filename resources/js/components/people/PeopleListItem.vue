<template>
  <b-row class="sideBar-body">
    <b-col cols="3" class="sideBar-avatar">
      <div class="avatar-icon">
        <img class="img-fluid" :src="avatarPath" />
      </div>
    </b-col>
    <b-col cols="9" class="sideBar-main">
      <b-row class="h-auto">
        <b-col cols="12" class="sideBar-name">
          <span v-if="alias !== null" class="name-meta">{{ alias }}</span>
          <span v-else class="name-meta">{{ name }}</span>
        </b-col>
      </b-row>
      <b-row>
        <b-col cols="12" class="sideBar-time">
          <span class="time-meta">
            <button @click="addContact" class="btn btn-sm btn-primary">ADD</button>
          </span>
        </b-col>
      </b-row>
    </b-col>
  </b-row>
</template>

<script>
import { mapState, mapActions } from "vuex";
export default {
  name: "PeopleListItem",
  props: ["name", "alias", "user_id", "avatar"],
  computed: {
    ...mapState(["people"]),
    avatarPath: function() {
      return "/images/" + this.avatar;
    }
  },
  methods: {
    ...mapActions(["saveContact"]),
    addContact() {
      this.saveContact({ userId: this.user_id });
    }
  }
};
</script>
<style>
</style>
