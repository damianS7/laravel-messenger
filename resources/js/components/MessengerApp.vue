<template>
  <div id="app-wrap" class="container app">
    <div class="row app-one">
      <div class="col-12 col-sm-4 side">
        <div class="side-left">
          <b-row class="heading">
            <div class="col-4 heading-avatar">
              <div class="heading-avatar-icon">
                <img @click="showProfile" src="https://bootdey.com/img/Content/avatar/avatar1.png" />
              </div>
            </div>

            <div class="col-4 heading-name">
              <a class="heading-name-meta">{{ profile.alias }}</a>
              <span class="heading-online">Online</span>
            </div>

            <div class="col-3 heading-compose">
              <i @click="showPeople" class="fa fa-comments fa-2x float-right" aria-hidden="true"></i>
            </div>
            <div class="col-1 heading-dot">
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
            <a class="heading-name-meta">{{ selected_contact.name }}</a>
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
import UserProfile from "./profile/UserProfile";
import ContactList from "./contact/ContactList";
import Conversation from "./conversation/Conversation";
import PeopleList from "./people/PeopleList";
import { mapGetters, mapState, mapActions } from "vuex";
export default {
  name: "MessengerApp",
  methods: {
    showPeople() {
      var div = document.getElementsByClassName("side-people")[0];
      div.style.left = "0%";
    },
    showProfile() {
      var div = document.getElementsByClassName("side-profile")[0];
      div.style.left = "0%";
    },
    init() {
      // Peticion al servidor de los datos necesarios para inicializar la app
      this.$store.dispatch("fetchData");

      // Timer para enviar peticiones al servidor en busca de mensajes nuevos
      window.setInterval(() => {
        this.$store.dispatch("fetchLastMessages");
      }, 2000);
    }
  },
  computed: {
    ...mapState(["profile", "selected_contact"]),
    ...mapActions(["fetchData"])
  },
  components: {
    profile: UserProfile,
    contacts: ContactList,
    conversation: Conversation,
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