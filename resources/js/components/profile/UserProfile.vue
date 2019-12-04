<template>
  <b-col class="p-0 h-100">
    <b-row class="avatar-menu">
      <b-col v-for="(avatar,index) of avatars" v-bind:key="index" sm="3" class="avatar-menu-inner">
        <img @click="selectAvatar(avatar)" :src="/images/ + avatar" />
      </b-col>
    </b-row>

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
          <img @click="avatarMenu" class="img-fluid" :src="avatarPath" />
        </div>
      </div>
    </div>

    <div class="compose-sideBar">
      <div class="compose-sideBar myhc">
        <div class="row sideBar-profile">
          <div class="col-12">Alias:</div>
          <div class="col-12">
            <input type="text" class="form-control" v-model="appUser.alias" @change="updateProfile" />
          </div>
          <div class="col-12">About you:</div>
          <div class="col-12">
            <textarea class="form-control" rows="4" v-model="appUser.info" @change="updateProfile"></textarea>
          </div>
        </div>
      </div>
    </div>
  </b-col>
</template>

<script>
import { mapState } from "vuex";

export default {
  name: "UserProfile",
  data: function() {
    return {
      avatarMenuVisible: false,
      avatars: [
        "avatar1.png",
        "avatar2.png",
        "avatar3.png",
        "avatar4.png",
        "avatar5.png",
        "avatar6.png"
      ]
    };
  },
  computed: {
    ...mapState(["appUser"]),
    avatarPath: function() {
      return "/images/" + this.appUser.avatar;
    }
  },
  methods: {
    avatarMenu() {
      var div = document.getElementsByClassName("avatar-menu")[0];
      if (!this.avatarMenuVisible) {
        div.style.bottom = "50%";
        this.avatarMenuVisible = true;
      } else {
        this.avatarMenuVisible = false;
        div.style.bottom = "-100%";
      }
    },
    selectAvatar(avatar) {
      this.appUser.avatar = avatar;
      this.$store.dispatch("saveProfile");
    },
    hideProfile(event) {
      var div = document.getElementsByClassName("side-profile")[0];
      div.style.left = "-100%";
    },
    updateProfile(event) {
      this.$store.dispatch("saveProfile");
    }
  }
};
</script>
<style scoped>
.avatar-menu {
  position: absolute;
  bottom: -100%;
  left: 35px;
  width: 100%;
  z-index: 3;
  background-color: antiquewhite;
  border-radius: 4px;
  border: 2px solid black;
  height: auto;
  -webkit-transition: bottom 1s ease;
  transition: bottom 1s ease;
}

.avatar-menu-inner {
  padding: 5px;
  text-align: center;
}

.avatar-menu-inner img {
  border-radius: 50%;
  height: 40px;
  width: 40px;
}
</style>
