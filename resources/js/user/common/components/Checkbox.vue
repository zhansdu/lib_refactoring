<template>
  <div
    class="relative checkbox border-darkgrey cursor-pointer"
    :class="{
      'd-flex align-items-center justify-content-center border-orange clicked':
        state,
      'disabled border-darkgrey': disabled,
    }"
    :style="state ? option(options, 'clicked', 'style') : ''"
    @click="item_click"
  />
</template>
<script lang="ts">
import { defineComponent, ref, watch } from "@vue/runtime-core";
import { option } from "./mixins/Option";
export default defineComponent({
  emits: ["change", "update:modelValue"],
  props: {
    modelValue: [Object, String, Boolean, Number, Array],
    disabled: Boolean,
    checked: Boolean,
    value: Object,
    options: Object,
  },
  setup(props, context) {
    const state = ref(props.checked);

    const item_click = (): void => {
      state.value = !state.value;
      const value = props.value ?? state.value;
      context.emit("update:modelValue", value);
      context.emit("change", value);
    };

    watch(
      () => props.checked,
      () => {
        state.value = props.checked;
      }
    );

    return {
      state,
      item_click,
      option,
    };
  },
});
</script>
<style lang="scss" scoped>
.checkbox {
  min-width: 1.25em;
  max-width: 1.25em;
  height: 1.25em;
  border: 0.125em solid;
  border-radius: 0.25em;
}

.clicked::after {
  content: "";
  width: 0.75em;
  height: 0.75em;
  position: absolute;
  background-color: #ff9d29;
  border-radius: 0.125em;
}

.disabled::after {
  background-color: #b5bac7;
}
</style>
