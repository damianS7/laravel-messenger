<template>
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
</template>

<script>
export default {
  mounted() {
    console.log("Contact Profile mounted.");
  },
  components: {}
};
</script>
<style>
</style>