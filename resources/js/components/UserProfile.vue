<template>
  <b-col class="side">
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
          <img class="img-fluid" src="https://bootdey.com/img/Content/avatar/avatar1.png" />
        </div>
      </div>
    </div>

    <div class="compose-sideBar myhc">
      <div class="row">
        <div class="col-12">Alias:</div>
        <div class="col-12">
          <input type="text" class="form-control" value />
        </div>
      </div>
      <div class="row">
        <div class="col-12">Description:</div>
        <div class="col-12">
          <input type="text" class="form-control" value />
        </div>
      </div>
    </div>
  </b-col>
</template>

<script>
export default {
  data: function() {
    return {
      isHiddenProfile: true
    };
  },
  mounted() {
    console.log("Component mounted.");
  },
  methods: {
    hideProfile() {
      var div = document.getElementsByClassName("side-profile")[0];
      div.style.left = "-100%";
    }
  },
  components: {}
};
</script>
<style>
</style>