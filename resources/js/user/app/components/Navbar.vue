<template>
  <dropdown-vue
    :title="title"
    :items="items"
    :options="options"
    @click="dropdownClick"
  />
  <checkbox-vue @change="dropdownClick" :checked="checked" v-model="checked" />
  <select-vue
    :title="{ placeholder: 'Zhake' }"
    :items="select_items"
    v-model="name"
  />
</template>

<script lang="ts">
import { defineComponent, ref } from "@vue/runtime-core";
import DropdownVue from "@user/common/components/Dropdown.vue";
import CheckboxVue from "@user/common/components/Checkbox.vue";
import SelectVue from "@user/common/components/Select.vue";
export default defineComponent({
  components: { DropdownVue, CheckboxVue, SelectVue },
  setup() {
    const title = {
      name: "Zhake",
    };
    const items = ref([{ name: "Zhan" }]);
    const checked = ref(true);
    const dropdownClick = () => {};
    const name = ref([1999]);
    const select_items = ref([19990, 1000, 100001, 2090]);

    checked.value = false;

    const options = {
      title: {
        class: ["text-hover-orange", "cursor-pointer"],
        style: "text-decoration:none",
      },
      item: {
        class: ["text-blue text-hover-red"],
      },
    };

    return {
      title,
      items,
      dropdownClick,
      checked,
      name,
      select_items,
      options,
    };
  },
});
</script>

<style></style>
