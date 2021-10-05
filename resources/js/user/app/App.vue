<template>
  <div>
    <loading-indicator />
    <navbar-vue></navbar-vue>
    <router-view v-slot="{ Component }">
      <transition name="scale-slide">
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
.scale-slide-enter-active,
.scale-slide-leave-active {
  position: absolute;
  transition: all 0.85s ease;
}

.scale-slide-enter-from {
  left: -100%;
}

.scale-slide-enter-to {
  left: 0%;
}

.scale-slide-leave-from {
  transform: scale(1);
}

.scale-slide-leave-to {
  transform: scale(0.8);
}
</style>
