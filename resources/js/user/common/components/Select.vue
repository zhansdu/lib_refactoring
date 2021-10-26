<template>
  <div
    tabindex="0"
    class="position-relative d-flex align-items-center rounded border"
    @focusout="
      () => {
        show(false);
      }
    "
  >
    <div v-if="required">
      <input type="text" v-model="modelValue" required />
    </div>
    <div
      class="
        d-flex
        justify-content-between
        align-items-center
        cursor-pointer
        w-100
      "
    >
      <label>
        <div class="d-flex align-items-center" v-if="multiple">
          <div
            class="m-1 p-1 px-2 bg-lightblue rounded-pill"
            :class="option(options, 'multiple_title', 'class')"
            :style="option(options, 'multiple_title', 'style')"
            v-for="(label, index) in multiple_title()"
            :key="index"
            @click="multiple_title_click(+index)"
          >
            {{ $t(item_label(label)) }}
          </div>
        </div>
        <div
          v-else
          :class="option(options, 'title', 'class')"
          :style="option(options, 'title', 'style')"
          @click="
            () => {
              show();
            }
          "
        >
          {{ $t(label) }}
        </div>
      </label>
      &nbsp;
      <div
        class="rotate"
        @click="
          () => {
            show();
          }
        "
      >
        <CaretUp />
      </div>
    </div>
    <div ref="select" class="results bg-white shadow-sm pl-3 transition">
      <div
        class="py-2 cursor-pointer"
        :class="option(options, 'item', 'class')"
        :style="option(options, 'item', 'style')"
        v-for="(item, index) in items"
        :key="index"
        @click="
          () => {
            item_click(item);
          }
        "
      >
        {{ $t(item_label(item)) }}
      </div>
    </div>
  </div>
</template>
<script type="text/javascript" lang="ts">
// icons
import CaretUp from "@user/common/assets/icons/CaretUp.vue";
import { defineComponent, ref,PropType, watchEffect } from "@vue/runtime-core";
import {option} from './mixins/Option'
type Title = {
  placeholder?: string;
  label?: string;
};

type Item = {
  name: string;
};

export default defineComponent({
  emits: ["change", "update:modelValue"],
  props: {
    modelValue: {
      type: [Object, String, Boolean, Number, Array],
      required:true,
      default: null,
    },
    title: {
      type: Object as PropType<Title>,
      required: true,
    },
    items: {
      type: Array as PropType<Array<Item | any>>,
      required: true,
    },
    options:{
      type: Object,
      required: false
    },
    disabled: Boolean,
    required: Boolean,
    multiple: {
      type: Boolean,
      default: false
    },
  },
  components: { CaretUp },
  setup(props, context) {
    const width = ref(0);
    const select= ref<HTMLDivElement>();
    const shown=ref(false);

    const show = (show?:boolean):void => {
      if(show==undefined){
        shown.value=!shown.value;
        show=shown.value;
      }
      if(show){
        let docHeight = window.innerHeight - (select.value as HTMLDivElement).getBoundingClientRect().bottom;
        width.value = docHeight > 250 ? 250 : docHeight;
      }
      else{
        width.value = 0;
      }
      (select.value as HTMLDivElement).style.maxHeight = width.value + "px";
    };

    const label = ref(props.title.placeholder ?? (props.items as Array<Item>)[0].name ?? (props.items as Array<any>)[0]);

    watchEffect(
      () => {
        if(props.modelValue!=null){
          if(!props.multiple){
            if(props.title.label!=null){
              label.value =  (props.modelValue as Object)[props.title.label];
            }
            else{
              label.value = props.modelValue.toString();
            }
          }
        }
    });

    const item_click = (item : Item | any):void => {
      context.emit("change", item);
      if(!props.multiple){
        show(false);
        context.emit("update:modelValue", item);
      }
      else{
        (props.modelValue as Array<any>).push(item);
      }
    };

    const item_label = (item : Item | any): string =>{
      let title = props.title;

        if ( title.label ){
          return (item as Object)[title.label]
        }
        else if ( item.name ) {
          return (item as Item).name
        }
        else {
          return item.toString();
        }
    };

    const multiple_title = ():Array<any> => (props.modelValue as Array<any>);

    const multiple_title_click=(index:number)=>{
      let items=props.modelValue as Array<any>;
      items.splice(index, 1);
    };

    return {
      select,
      width,
      label,
      shown,
      show,
      item_click,
      item_label,
      multiple_title,
      multiple_title_click,
      option
    };
  }
});
</script>
<style scoped lang="scss">
.results,
input {
  position: absolute;
  top: 102%;
  width: 100%;
  left: 0;
}
.results {
  z-index: 1000;
  overflow: hidden;
  overflow-y: auto;
  max-height: 0;
}
input {
  visibility: hidden;
  :invalid > label {
    color: red;
  }
}
</style>
