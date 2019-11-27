<template>
  <div class="row message-body">
    <div :class="[ isSender ? ' message-main-sender' : ' message-main-receiver']" class="col-sm-12">
      <div :class="[ isSender ? 'sender' : 'receiver']">
        <div class="message-text">{{ message }}</div>
        <span v-if="alias !== null" class="message-time float-right">{{ alias }}</span>
        <span v-else class="message-time float-right">{{ name }}</span>
        <span class="message-time float-right">{{ sent_at }}</span>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "ConversationMessage",
  props: ["name", "message", "isSender", "alias", "sent_at"]
};
</script>
<style>
</style>