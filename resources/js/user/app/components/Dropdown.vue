<template>
  <div class="dropdown">
    <a
      class="link dropdown-toggle"
      data-toggle="dropdown"
      :class="title_classes"
    >
      <span v-if="title.uppercase">{{ $t(title.link).toUpperCase() }}</span>
      <span v-else>{{ $t(title.link) }}</span>
    </a>
    <div class="dropdown-menu" :class="menu_classes">
      <a
        :target="links ? '_blank' : ''"
        :href="item.link"
        class="dropdown-item link"
        v-for="(item, index) in data"
        :key="index"
        @click="item_on_click(item)"
        v-if="!item.invisible"
      >
        {{ $t(item.name ? item.name : item) }}
      </a>
    </div>
  </div>
</template>
<script lang="ts">
export default {
  emits: {
    click: Function,
  },
  props: {
    title: String,
  },
};
</script>
<style scoped></style>
