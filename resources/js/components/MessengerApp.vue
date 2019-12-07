<template>
  <b-container id="app-wrap" class="app">
    <b-row class="app-one">
      <b-col cols="5" sm="5" md="5" lg="3" class="side">
        <div class="side-left">
          <b-row class="heading">
            <b-col cols="5" sm="3" class="heading-avatar">
              <div class="heading-avatar-icon">
                <img @click="showProfile" :src="appUserAvatarPath" />
              </div>
            </b-col>

            <b-col cols="7" sm="5" class="heading-name">
              <a class="heading-name-meta">{{ appUser.profile.alias }}</a>
            </b-col>

            <b-col cols="3" sm="2" class="heading-compose">
              <i @click="showContacts" class="fa fa-comments fa-2x float-right"></i>
            </b-col>

            <b-col cols="3" sm="2" class="heading-compose">
              <i @click="showPeople" class="fa fa-user-plus fa-2x float-right"></i>
            </b-col>
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
      </b-col>

      <b-col cols="7" sm="7" md="7" lg="9" class="conversation">
        <div class="side-contact-profile">
          <contact-profile></contact-profile>
        </div>
        <b-row class="heading">
          <b-col cols="5" sm="3" md="3" lg="1" class="heading-avatar">
            <div v-if="selectedUserAvatarPath" class="heading-avatar-icon">
              <img @click="showContactProfile" :src="selectedUserAvatarPath" />
            </div>
          </b-col>
          <b-col cols="5" sm="8" md="8" lg="10" class="heading-name">
            <a
              v-if="selectedUserName"
              @click="showContactProfile"
              class="heading-name-meta"
            >{{ selectedUser.name }}</a>
          </b-col>
          <b-col cols="2" sm="1" md="1" lg="1" class="heading-dot float-right">
            <i class="fa fa-ellipsis-v fa-2x float-right" aria-hidden="true"></i>
          </b-col>
        </b-row>
        <conversation></conversation>
      </b-col>
    </b-row>
  </b-container>
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
      if (typeof this.appUser.profile.avatar === "undefined") {
        return false;
      }

      return "/images/" + this.appUser.profile.avatar;
    },
    selectedUserAvatarPath: function() {
      if (typeof this.selectedUser.profile.avatar === "undefined") {
        return false;
      }
      return "/images/" + this.selectedUser.profile.avatar;
    },
    selectedUserName: function() {
      if (typeof this.selectedUser.name === "undefined") {
        return false;
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