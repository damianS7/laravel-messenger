<template>
  <b-col class="p-0 h-100">
    <div class="row newMessage-heading">
      <div class="row newMessage-main">
        <div class="col-sm-2 col-xs-2 newMessage-back">
          <i @click="hideProfile" class="fa fa-arrow-left" aria-hidden="true"></i>
        </div>
        <div class="col-sm-10 col-xs-10 newMessage-title">Profile</div>
      </div>
    </div>

    <div class="row composeBox h-auto">
      <div class="col-12 composeBox-inner heading-avatar h-auto">
        <div class="profile-avatar-icon h-auto">
          <img class="img-fluid" src="https://bootdey.com/img/Content/avatar/avatar1.png" />
        </div>
      </div>
    </div>

    <div class="compose-sideBar">
      <div class="compose-sideBar myhc">
        <div class="row sideBar-body">
          <div class="col-12">Alias:</div>
          <div class="col-12">
            <input type="text" class="form-control" v-model="profile.alias" @change="updateProfile" />
          </div>
          <div class="col-12">About you:</div>
          <div class="col-12">
            <textarea class="form-control" rows="4" v-model="profile.info" @change="updateProfile"></textarea>
          </div>
        </div>
      </div>
    </div>
  </b-col>
</template>

<script>
import { mapState } from "vuex";

export default {
  name: "ContactProfile",
  data: function() {
    return {
      stateAlias: this.name,
      stateInfo: this.info
    };
  },
  computed: {
    ...mapState(["profile"])
  },
  methods: {
    hideProfile() {
      var div = document.getElementsByClassName("side-profile")[0];
      div.style.left = "-100%";
    },
    updateProfile(e) {
      /*this.$store.commit(
        "updateProfile",
        this.stateAlias,
        this.stateInfo,
        this.stateInfo
      );*/
      this.$store.dispatch("updateProfile");
    }
  },
  mounted() {},
  components: {}
};
</script>
<style>
</style>
