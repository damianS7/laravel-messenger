<template>
  <div id="app-wrap" class="container app">
    <div class="row app-one">
      <div class="col-12 col-sm-4 side">
        <div class="side-left">
          <b-row class="heading">
            <div class="col-3 heading-avatar">
              <div class="heading-avatar-icon">
                <img @click="showProfile" :src="appUserAvatarPath" />
              </div>
            </div>

            <div class="col-5 heading-name">
              <a class="heading-name-meta">{{ appUser.alias }}</a>
            </div>

            <div class="col-2 heading-compose">
              <i @click="showContacts" class="fa fa-comments fa-2x float-right"></i>
            </div>
            <div class="col-2 heading-compose">
              <i @click="showPeople" class="fa fa-user-plus fa-2x float-right"></i>
            </div>
          </b-row>
          <conversation-list></conversation-list>
        </div>

        <div class="side-contacts">
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
        <div class="side-contact-profile">
          <contact-profile></contact-profile>
        </div>
        <div class="row heading">
          <div class="col-4 heading-avatar">
            <div v-if="selectedUserAvatarPath" class="heading-avatar-icon">
              <img @click="showContactProfile" :src="selectedUserAvatarPath" />
            </div>
          </div>
          <div class="col-6 heading-name">
            <a @click="showContactProfile" class="heading-name-meta">{{ selectedUser.name }}</a>
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
import UserProfile from "./profile/UserProfile";
import ContactProfile from "./profile/ContactProfile";
import ContactList from "./contact/ContactList";
import Conversation from "./conversation/Conversation";
import PeopleList from "./people/PeopleList";
import ConversationList from "./conversation/ConversationList";
import { mapGetters, mapState, mapActions } from "vuex";
export default {
  name: "MessengerApp",
  methods: {
    showPeople() {
      var div = document.getElementsByClassName("side-people")[0];
      div.style.left = "0%";
    },
    showContacts() {
      var div = document.getElementsByClassName("side-contacts")[0];
      div.style.left = "0%";
    },
    showProfile() {
      var div = document.getElementsByClassName("side-profile")[0];
      div.style.left = "0%";
    },
    showContactProfile() {
      var div = document.getElementsByClassName("side-contact-profile")[0];
      div.style.right = "0%";
    },
    init() {
      // Peticion al servidor de los datos necesarios para inicializar la app
      this.$store.dispatch("fetch");

      // Timer para enviar peticiones al servidor en busca de mensajes nuevos
      window.setInterval(() => {
        this.$store.dispatch("fetchLastMessages");
      }, 2000);
    }
  },
  computed: {
    ...mapState(["appUser", "selectedContact", "selectedUser"]),
    ...mapActions(["fetchData"]),
    appUserAvatarPath: function() {
      return "/images/" + this.appUser.avatar;
    },
    selectedUserAvatarPath: function() {
      if (typeof this.selectedUser.avatar === "undefined") {
        return false;
      }
      return "/images/" + this.selectedUser.avatar;
    },
    selectedUserName: function() {
      if (typeof this.selectedUser.name === "undefined") {
        return "";
      }
      return this.selectedUser.name;
    }
  },
  components: {
    profile: UserProfile,
    contacts: ContactList,
    conversation: Conversation,
    "conversation-list": ConversationList,
    "contact-profile": ContactProfile,
    "people-finder": PeopleList
  },
  mounted() {
    this.init();
  }
};
</script>
<style>
@import "../../../public/css/messenger.css";
</style>