<template>
  <b-col class="p-0 h-100">
    <b-row class="newMessage-heading">
      <b-row class="newMessage-main">
        <b-col cols="2" sm="2" class="newMessage-back">
          <i @click="hideProfile" class="fa fa-arrow-left" aria-hidden="true"></i>
        </b-col>
        <b-col cols="10" sm="10" class="newMessage-title">Profile</b-col>
      </b-row>
    </b-row>

    <b-row class="composeBox h-auto">
      <b-col cols="12" class="composeBox-inner heading-avatar h-auto">
        <div class="profile-avatar-icon h-auto">
          <img v-b-modal.modal-center class="img-fluid" :src="avatarPath" />
        </div>
      </b-col>
    </b-row>

    <div class="compose-sideBar">
      <div class="compose-sideBar myhc">
        <b-row class="sideBar-profile">
          <b-col cols="12">Alias:</b-col>
          <b-col cols="12">
            <input type="text" class="form-control" v-model="appUser.profile.alias" @change="updateProfile" />
          </b-col>
          <b-col cols="12">About you:</b-col>
          <b-col cols="12">
            <textarea class="form-control" rows="4" v-model="appUser.profile.info" @change="updateProfile"></textarea>
          </b-col>
        </b-row>
      </div>
    </div>

    <b-modal id="modal-center" centered title="Select your new avatar">
      <b-row>
        <b-col
          v-for="(avatar,index) of avatars"
          v-bind:key="index"
          sm="3"
          class="avatar-menu-inner"
        >
          <img @click="selectAvatar(avatar)" :src="/images/ + avatar" />
        </b-col>
      </b-row>
    </b-modal>
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
      return "/images/" + this.appUser.profile.avatar;
    }
  },
  methods: {
    avatarMenu() {
      var div = document.getElementsByClassName("avatar-menu")[0];
      if (!this.avatarMenuVisible) {
        div.style.bottom = "50%";
        div.style.left = "10%";
        this.avatarMenuVisible = true;
      } else {
        this.avatarMenuVisible = false;
        div.style.left = "-100%";
        div.style.bottom = "-100%";
      }
    },
    selectAvatar(avatar) {
      this.appUser.profile.avatar = avatar;
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
  left: -100%;
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
