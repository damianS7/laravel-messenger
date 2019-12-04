<template>
  <b-col class="p-0 h-100">
    <div class="row newMessage-heading">
      <div class="row newMessage-main">
        <div class="col-2 newMessage-back">
          <i @click="hideProfile" class="fa fa-arrow-left" aria-hidden="true"></i>
        </div>
        <div class="col-5 newMessage-title">Profile</div>
      </div>
    </div>

    <div class="row composeBox composeBox-inner h-auto">
      <div class="col-6 profile-avatar-icon h-auto">
        <img class="img-fluid" :src="'/images/' + selectedUser.avatar" />
      </div>
      <div class="col-6">
        <button v-if="!isContact" @click="addContact" class="btn btn-sm btn-success">ADD CONTACT</button>
        <button v-if="isContact" @click="deleteContact" class="btn btn-sm btn-danger">DELETE CONTACT</button>
      </div>
    </div>

    <div class="compose-sideBar">
      <div class="compose-sideBar myhc">
        <div class="row sideBar-profile">
          <div class="col-12">Alias:</div>
          <div class="col-12">
            <input type="text" class="form-control" :value="selectedUser.alias" readonly />
          </div>
          <div class="col-12">About me:</div>
          <div class="col-12">
            <textarea class="form-control" rows="4" :value="selectedUser.info" readonly></textarea>
          </div>
        </div>
      </div>
    </div>
  </b-col>
</template>

<script>
import { mapState, mapGetters, mapMutations, mapActions } from "vuex";

export default {
  name: "ContactProfile",
  data: function() {
    return {};
  },
  computed: {
    ...mapState(["contacts", "selectedUser"]),
    ...mapMutations(["removeContact", "setSelectedContact"]),
    isContact: function() {
      return this.$store.getters.isContact(this.selectedUser.user_id);
    }
  },
  methods: {
    hideProfile() {
      var div = document.getElementsByClassName("side-contact-profile")[0];
      div.style.right = "-100%";
    },
    addContact() {
      this.$store.dispatch("saveContact", {
        userId: this.selectedUser.user_id
      });
    },
    deleteContact() {
      this.$store.dispatch("deleteContact", {
        userId: this.selectedUser.user_id
      });
    }
  }
};
</script>
<style>
</style>
