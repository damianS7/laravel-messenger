<template>
  <div class="row sideBar-body">
    <div class="col-3 sideBar-avatar">
      <div class="avatar-icon">
        <img src="https://bootdey.com/img/Content/avatar/avatar4.png" />
      </div>
    </div>
    <div class="col-9 sideBar-main">
      <div class="row">
        <div class="col-8 sideBar-name">
          <span class="name-meta">{{ name }}</span>
        </div>
        <div class="col-4 float-right sideBar-time">
          <span class="time-meta float-right">18:18</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapState } from "vuex";
export default {
  props: ["name"],
  computed: {
    ...mapState(["contacts"])
  }
};
</script>
<style>
</style>
