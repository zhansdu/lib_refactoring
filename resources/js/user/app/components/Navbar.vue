<template>
  <!-- <dropdown-vue
    :title="title"
    :items="items"
    :options="options"
    @click="dropdown_click"
  /> -->
  <!-- <checkbox-vue @change="dropdown_click" :checked="checked" v-model="checked" /> -->
  <select-vue :title="{ placeholder: 'Zhake', label: 'name' }" :items="items" @change="dropdown_click" v-model="title" />
</template>

<script lang="ts">
import { defineComponent, ref } from "@vue/runtime-core";
import DropdownVue from "@user/common/components/Dropdown.vue";
import CheckboxVue from "@user/common/components/Checkbox.vue";
import SelectVue from "@user/common/components/Select.vue";
export default defineComponent({
  components: { DropdownVue, CheckboxVue, SelectVue },
  setup() {
    const title = ref({ name: "Zhan" });
    const items = ref([{ name: "Zhake" }, { name: "Johny" }]);
    const options = ref({
      title: {
        class: "text-blue cursor-pointer",
        style: "text-decoration:none",
      },
    });
    const dropdown_click = (item) => {
      console.log(item);
    };
    return {
      title,
      items,
      options,
      dropdown_click,
    };
  },
});
</script>
