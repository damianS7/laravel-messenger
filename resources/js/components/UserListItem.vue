<template>
  <div class="row sideBar-body">
    <div class="col-3 sideBar-avatar">
      <div class="avatar-icon">
        <img class="img-fluid" src="https://bootdey.com/img/Content/avatar/avatar5.png" />
      </div>
    </div>
    <div class="col-9 sideBar-main">
      <div class="row p-0 m-0">
        <div class="col-8 sideBar-name">
          <span class="name-meta">{{ name }}</span>
        </div>
      </div>
      <div class="row p-0 m-0">
        <div class="col-12 float-right sideBar-time">
          <span class="time-meta float-right">
            <button class="btn btn-sm btn-primary">ADD</button>
          </span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapState } from "vuex";
export default {
  props: ["name"],
  computed: {}
};
</script>
<style>
</style>
