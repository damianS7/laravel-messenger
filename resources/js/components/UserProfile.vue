<template>
  <div class="row heading">
    <div class="col-6 heading-avatar">
      <div class="heading-avatar-icon">
        <img src="https://bootdey.com/img/Content/avatar/avatar1.png" />
      </div>
    </div>
    <div class="col-4 heading-compose float-right">
      <i @click="show" class="fa fa-comments fa-2x float-right" aria-hidden="true"></i>
    </div>
    <div class="col-2 heading-dot float-right">
      <i class="fa fa-ellipsis-v fa-2x float-right" aria-hidden="true"></i>
    </div>
  </div>
</template>

<script>
export default {
  data: function() {
    return {
      isHidden: true
    };
  },
  mounted() {
    console.log("Component mounted.");
  },
  methods: {
    show() {
      var div = document.getElementsByClassName("side-two")[0];
      div.style.left = "0";
    },
    hide() {
      var div = document.getElementsByClassName("side-two")[0];
      div.style.left = "0";
    }
  },
  components: {}
};
</script>
<style>
</style>