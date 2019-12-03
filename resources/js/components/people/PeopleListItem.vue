<template>
  <div class="row sideBar-body">
    <div class="col-3 sideBar-avatar">
      <div class="avatar-icon">
        <img class="img-fluid" :src="avatarPath" />
      </div>
    </div>
    <div class="col-9 sideBar-main">
      <div class="row h-auto">
        <div class="col-12 sideBar-name">
          <span class="name-meta">{{ name }}</span>
        </div>
      </div>
      <div class="row">
        <div class="col-12 sideBar-time">
          <span class="time-meta">
            <button @click="peopleToContact" class="btn btn-sm btn-primary">ADD</button>
          </span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapState, mapMutations } from "vuex";
export default {
  name: "PeopleListItem",
  props: ["name", "index", "user_id", "avatar"],
  computed: {
    ...mapState(["people"]),
    ...mapMutations(["addContact"]),
    avatarPath: function() {
      return "/images/" + this.avatar;
    }
  },
  methods: {
    peopleToContact() {
      var user_id = this.user_id;
      this.$store.dispatch("saveContact", { user_id });
    }
  }
};
</script>
<style>
</style>
