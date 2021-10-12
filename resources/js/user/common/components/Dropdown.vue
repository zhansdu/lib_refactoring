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
          :target="item.target"
          :to="item.link"
          :class="option(options, 'item', 'class')"
          :style="option(options, 'item', 'style')"
          @click="
            () => {
              item_click(item);
            }
          "
          v-if="item.link != null"
        >
          {{ $t(item.name ?? item) }}
        </router-link>
        <a
          class="dropdown-item"
          :class="option(options, 'item', 'class')"
          :style="option(options, 'item', 'style')"
          @click="
            () => {
              item_click(item);
            }
          "
          v-else
        >
          {{ $t(item.name ?? item) }}
        </a>
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
    modelValue: [Object, String, Boolean, Number, Array],
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
      context.emit("click", item);
      context.emit("update:modelValue", item);
    };

    const data = props.items.filter((item) => !item.invisible);

    return { item_click, data, title, option };
  },
});
</script>
