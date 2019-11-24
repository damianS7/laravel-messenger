<template>
  <div id="app-wrap" class="container app">
    <div class="row app-one">
      <div class="col-12 col-sm-4 side">
        <div class="side-left">
          <b-row class="heading">
            <div class="col-6 heading-avatar">
              <div class="heading-avatar-icon">
                <img @click="showProfile" src="https://bootdey.com/img/Content/avatar/avatar1.png" />
              </div>
            </div>
            <div class="col-4 heading-compose float-right">
              <i @click="showPeople" class="fa fa-comments fa-2x float-right" aria-hidden="true"></i>
            </div>
            <div class="col-2 heading-dot float-right">
              <i class="fa fa-ellipsis-v fa-2x float-right" aria-hidden="true"></i>
            </div>
          </b-row>
          <contacts></contacts>
        </div>

        <div class="side-people">
          <people-finder></people-finder>
        </div>

        <div class="side-profile">
          <profile></profile>
        </div>
      </div>

      <div class="col-12 col-sm-8 conversation">
        <div class="row heading">
          <div class="col-4 heading-avatar">
            <div class="heading-avatar-icon">
              <img src="https://bootdey.com/img/Content/avatar/avatar6.png" />
            </div>
          </div>
          <div class="col-6 heading-name">
            <a class="heading-name-meta">John Doe</a>
            <span class="heading-online">Online</span>
          </div>
          <div class="col-2 heading-dot float-right">
            <i class="fa fa-ellipsis-v fa-2x float-right" aria-hidden="true"></i>
          </div>
        </div>
        <conversation></conversation>
      </div>
    </div>
  </div>
</template>

<script>
import UserProfileComponent from "./UserProfile.vue";
import ContactListComponent from "./ContactList.vue";
import ConversationComponent from "./Conversation.vue";
import UserList from "./UserList.vue";
import { mapActions } from "vuex";
export default {
  methods: {
    ...mapActions(["fetchData"]),
    showPeople() {
      var div = document.getElementsByClassName("side-people")[0];
      div.style.left = "0%";
    },
    showProfile() {
      var div = document.getElementsByClassName("side-profile")[0];
      div.style.left = "0%";
    }
  },
  computed: {},
  components: {
    profile: UserProfileComponent,
    contacts: ContactListComponent,
    conversation: ConversationComponent,
    "people-finder": UserList
  },
  mounted() {
    console.log("mounted");
    this.fetchData();
    // Carga los contactos del usuario
    //this.fetchContacts();
  }
};
</script>
<style>
@import "../../../public/css/messenger.css";
</style>