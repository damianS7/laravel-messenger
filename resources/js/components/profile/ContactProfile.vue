<template>
  <b-col class="p-0 h-100">
    <div class="row newMessage-heading">
      <div class="row newMessage-main">
        <div class="col-2 newMessage-back">
          <i @click="hideProfile" class="fa fa-arrow-left" aria-hidden="true"></i>
        </div>
        <div class="col-5 newMessage-title">Profile</div>
        <div class="col-5 newMessage-title">
          <button @click="deleteContact" class="btn btn-sm btn-danger">DELETE CONTACT</button>
        </div>
      </div>
    </div>

    <div class="row composeBox h-auto">
      <div class="col-12 composeBox-inner heading-avatar h-auto">
        <div class="profile-avatar-icon h-auto">
          <img class="img-fluid" :src="selectedContact.avatar" />
        </div>
      </div>
    </div>

    <div class="compose-sideBar">
      <div class="compose-sideBar myhc">
        <div class="row sideBar-profile">
          <div class="col-12">Alias:</div>
          <div class="col-12">
            <input
              type="text"
              class="form-control"
              v-model="selectedContact.alias"
              @change="updateProfile"
            />
          </div>
          <div class="col-12">About you:</div>
          <div class="col-12">
            <textarea
              class="form-control"
              rows="4"
              v-model="selectedContact.info"
              @change="updateProfile"
            ></textarea>
          </div>
        </div>
      </div>
    </div>
  </b-col>
</template>

<script>
import { mapState, mapGetters, mapMutations } from "vuex";

export default {
  name: "ContactProfile",
  data: function() {
    return {};
  },
  computed: {
    ...mapState(["contacts", "selectedContact"]),
    ...mapGetters(["getContactIndex"]),
    ...mapMutations(["removeContact", "setSelectedContact"])
  },
  methods: {
    hideProfile() {
      var div = document.getElementsByClassName("side-contact-profile")[0];
      div.style.right = "-100%";
    },
    deleteContact() {
      if (typeof this.selected_contact.user_id === "undefined") {
        return;
      }

      var contact_id = this.selected_contact.user_id;
      var index = this.getContactIndex(contact_id);
      //this.$store.commit("removeContactById", index);
      this.$store.dispatch("deleteContact", { contact_id, index });
    },
    updateProfile() {}
  }
};
</script>
<style>
</style>
