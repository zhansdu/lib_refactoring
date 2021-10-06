<template>
  <div>
    <loading-indicator />
    <navbar-vue></navbar-vue>
    <router-view v-slot="{ Component }">
      <transition name="scale">
        <component :is="Component" />
      </transition>
    </router-view>
    <footer-vue></footer-vue>
  </div>
</template>

<script lang="ts">
import { defineComponent, ref } from "@vue/runtime-core";
import NavbarVue from "./main_view/Navbar.vue";
import FooterVue from "./main_view/Footer.vue";
import LoadingIndicator from "./main_view/LoadingIndicator.vue";
export default defineComponent({
  components: { NavbarVue, FooterVue, LoadingIndicator },
});
</script>
<style lang="scss" scoped>
.scale-enter-active,
.scale-leave-active {
  transition: all 0.5s ease;
}

.scale-enter-from,
.scale-leave-to {
  opacity: 0;
  transform: scale(0.9);
}
</style>
