<template>
  <div class="row message-body">
    <div :class="[ isSender ? ' message-main-sender' : ' message-main-receiver']" class="col-sm-12">
      <div :class="[ isSender ? 'sender' : 'receiver']">
        <div class="message-text">{{ message }}</div>
        <span class="message-time float-right">{{ name }}</span>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["name", "message", "isSender"],
  mounted() {
    console.log("Conversation mounted.");
  }
};
</script>
<style>
</style>