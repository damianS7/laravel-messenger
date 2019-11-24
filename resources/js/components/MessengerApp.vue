<template>
  <div class="container app">
    <div class="row app-one">
      <div class="col-4 side">
        <div class="side-one">
          <user-profile-menu></user-profile-menu>
          <user-contacts></user-contacts>
        </div>

        <div class="side-two">
          <contact-finder></contact-finder>
        </div>
      </div>

      <div class="col-8 conversation">
        <contact-conversation></contact-conversation>
      </div>
    </div>
  </div>
</template>

<script>
import UserProfileComponent from "./UserProfile.vue";
import ContactListComponent from "./ContactList.vue";
import ContactProfileComponent from "./ContactProfile.vue";
import ConversationComponent from "./Conversation.vue";
import FindContactComponent from "./FindContactList.vue";
import { mapActions } from "vuex";
export default {
  methods: {
    ...mapActions(["fetchContacts"])
  },
  computed: {},
  components: {
    "user-profile-menu": UserProfileComponent,
    "contact-profile-menu": ContactProfileComponent,
    "user-contacts": ContactListComponent,
    "contact-conversation": ConversationComponent,
    "contact-finder": FindContactComponent
  },
  mounted() {
    console.log("mounted");
    this.fetchContacts();
    // Carga los contactos del usuario
    //this.fetchContacts();
  }
};
</script>
<style>
@import "../../../public/css/messenger.css";
</style>