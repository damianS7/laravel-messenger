<template>
  <b-col class="p-0 h-100">
    <b-row class="newMessage-heading">
      <b-row class="newMessage-main">
        <b-col cols="2" class="newMessage-back">
          <i @click="hideProfile" class="fa fa-arrow-left" aria-hidden="true"></i>
        </b-col>
        <b-col cols="5" class="newMessage-title">Profile</b-col>
      </b-row>
    </b-row>

    <b-row class="composeBox composeBox-inner h-auto">
      <b-col cols="6" class="profile-avatar-icon h-auto">
        <img
          v-if="selectedUser.profile.avatar"
          class="img-fluid"
          :src="'/images/' + selectedUser.profile.avatar"
        />
      </b-col>
      <b-col cols="6">
        <button v-if="!isContact" @click="addContact" class="btn btn-sm btn-success">ADD CONTACT</button>
        <button v-if="isContact" @click="removeContact" class="btn btn-sm btn-danger">DELETE CONTACT</button>
      </b-col>
    </b-row>

    <div class="compose-sideBar">
      <div class="compose-sideBar myhc">
        <b-row class="row sideBar-profile">
          <b-col cols="12">Alias:</b-col>
          <b-col cols="12">
            <input type="text" class="form-control" :value="selectedUser.profile.alias" readonly />
          </b-col>
          <b-col cols="12">About me:</b-col>
          <b-col cols="12">
            <textarea class="form-control" rows="4" :value="selectedUser.profile.info" readonly></textarea>
          </b-col>
        </b-row>
      </div>
    </div>
  </b-col>
</template>

<script>
import { mapState, mapGetters, mapMutations, mapActions } from "vuex";

export default {
  name: "ContactProfile",
  computed: {
    ...mapState(["contacts", "selectedUser"]),
    isContact: function() {
      return this.$store.getters.isContact(this.selectedUser.id);
    }
  },
  methods: {
    ...mapActions(["saveContact", "deleteContact"]),
    hideProfile() {
      var div = document.getElementsByClassName("side-contact-profile")[0];
      div.style.right = "-100%";
    },
    // Metodo para agregar contactos
    addContact() {
      // Action
      this.saveContact({ userId: this.selectedUser.id });
    },
    // Metodo para eliminar contactos
    removeContact() {
      // Action
      this.deleteContact({ userId: this.selectedUser.id });
    }
  }
};
</script>
<style>
</style>
