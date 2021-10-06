<template>
  <div>
    <dropdown-vue :title="{ name: 'Zhan' }" :items="[{ name: 'Zhake' }]" />
  </div>
</template>

<script lang="ts">
import { defineComponent } from "@vue/runtime-core";
import DropdownVue from "@user/common/components/Dropdown.vue";
export default defineComponent({
  components: { DropdownVue },
  setup() {},
});
</script>

<style></style>
