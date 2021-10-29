<template>
  <div class="dropdown">
    <a
      class="dropdwon-toggle"
      data-bs-toggle="dropdown"
      :class="option(options, 'title', 'class')"
      :style="option(options, 'title', 'style')"
    >
      <span v-if="title.uppercase">{{ $t(title.name).toUpperCase() }}</span>
      <span v-else>{{ $t(title.name) }}</span>
    </a>
    <ul class="dropdown-menu">
      <li v-for="(item, index) in data" :key="index">
        <router-link
          class="dropdown-item"
          :target="item.target ?? ''"
          :to="item.link ?? ''"
          :class="option(options, 'item', 'class')"
          :style="option(options, 'item', 'style')"
          @click="
            () => {
              item_click(item);
            }
          "
        >
          {{ $t(item.name ?? item) }}
        </router-link>
      </li>
    </ul>
  </div>
</template>
<script lang="ts">
import { PropType, defineComponent } from "@vue/runtime-core";
import { option } from "./mixins/Option";

type Item = {
  name: string;
  link?: string | Object;
  invisible?: boolean;
  target?: string;
};

type Title = {
  name: string;
  uppercase?: boolean;
};

export default defineComponent({
  emits: ["click", "update:modelValue"],
  props: {
    modelValue: [String],
    title: {
      type: Object as PropType<Title>,
      required: true,
    },
    items: {
      type: Array as PropType<Array<Item>>,
      required: true,
    },
    options: {
      type: Object,
      required: false,
    },
  },
  setup(props, context) {
    const title = props.title;

    const item_click = (item: Item): void => {
      context.emit("update:modelValue", item.name ?? item.toString());
      context.emit("click", item);
    };

    const data = props.items.filter((item) => !item.invisible);

    return { item_click, data, title, option };
  },
});
</script>
