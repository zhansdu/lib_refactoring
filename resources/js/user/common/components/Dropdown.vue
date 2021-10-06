<template>
  <div class="dropdown">
    <a
      class="dropdown-toggle"
      data-bs-toggle="dropdown"
      :class="title.class"
      :style="title.style"
    >
      <span v-if="title.uppercase">{{ $t(title.name).toUpperCase() }}</span>
      <span v-else>{{ $t(title.name) }}</span>
    </a>
    <ul class="dropdown-menu">
      <li v-for="(item, index) in data" :key="index">
        <a
          class="dropdown-item"
          :target="item.target"
          :href="item.link"
          :class="item.class"
          :style="item.style"
          @click="item_click(item)"
        >
          {{ $t(item.name ?? item) }}
        </a>
      </li>
    </ul>
  </div>
</template>
<script lang="ts">
import { PropType } from "@vue/runtime-core";
import { useI18n } from "vue-i18n";

type Item = {
  name: string;
  value?: string;
  link?: string;
  invisible?: boolean;
  target?: string;
  class?: Array<any> | string | Object;
  style?: Array<any> | string | Object;
};

type Title = {
  name: string;
  uppercase?: boolean;
  class?: Array<any> | string | Object;
  style?: Array<any> | string | Object;
};

export default {
  emits: {
    click: Function,
  },
  props: {
    title: {
      type: Object as PropType<Title>,
      required: true,
    },
    items: {
      type: Array as PropType<Array<Item>>,
      required: true,
    },
  },
  setup(props, context) {
    const { t } = useI18n();
    const item_click = (item: Item) => {
      context.emit("click", item.value ?? item.link ?? item.name);
    };

    const data = props.items.filter((item) => !item.invisible);
    const title = props.title;

    return { t, item_click, data, title };
  },
};
</script>
<style scoped></style>
