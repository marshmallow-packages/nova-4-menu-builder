<template>
  <div id="menu-builder-header">
    <div class="w-full px-4 py-2 mr-4 overflow-x-auto bg-white rounded-lg locale-selection dark:bg-gray-800"
      v-if="Object.keys(locales).length > 1">
      <div v-for="locale of Object.keys(locales)" :key="locale" @click.prevent="$emit('changeLocale', locale)"
        class="box-border flex items-center h-full px-2 font-bold cursor-pointer whitespace-nowrap" :class="{
          'text-primary-500 border-primary-500': activeLocale === locale,
          'text-80 border-transparent': activeLocale !== locale,
        }" style="border-bottom-width: 2px">
        <span v-html="getLocaleDisplay(locale)" />
      </div>
    </div>

    <Button class="mt-2 mb-2 mr-4 cursor-pointer" @click.prevent="showCopyModal = true"
      v-if="showCopyButton && showDuplicate" icon="duplicate" dusk="copy-menu-items-button">
      <!-- {{ __('novaMenuBuilder.copyMenuItemsButtonTitle') }} -->
    </Button>

    <Button class="mt-2 mb-2 cursor-pointer" :title="__('novaMenuBuilder.addMenuItem')"
      @click.prevent="$emit('addMenuItem')">
      <Icon name="ViewGridAdd" type="outline" />
      {{ __('novaMenuBuilder.addMenuItem') }}
    </Button>

    <copy-menu-items-modal :resourceId="resourceId" :activeLocale="activeLocale" :locales="locales"
      :showModal="showCopyModal" :menuCount="menuCount" @closeModal="showCopyModal = false"
      @refreshItems="$emit('refreshItems')" />
  </div>
</template>

<script>
import CopyMenuItemsModal from '../modals/CopyMenuItemsModal';
import { Button, Icon } from 'laravel-nova-ui';

export default {
  components: { Button, CopyMenuItemsModal, Icon },

  props: ['locales', 'activeLocale', 'resourceId', 'menuCount', 'showDuplicate'],

  data: () => ({
    showCopyModal: false,
  }),

  computed: {
    showCopyButton() {
      // If only 1 locale and 1 menu, don't show the button
      const localeCount = Object.keys(this.locales).length;
      return localeCount > 1 || this.menuCount > 1;
    },
  },

  methods: {
    getLocaleDisplay(locale) {
      const customDisplay = Nova.config('customLocaleDisplay');

      if (customDisplay && customDisplay[locale]) {
        return customDisplay[locale];
      }

      return `${this.locales[locale]} (${locale})`;
    },
  },
};
</script>

<style lang="scss">
#menu-builder-header {
  position: absolute;
  right: 0;
  top: -52px;
  display: flex;
  max-width: 80%;
  place-content: end;

  .locale-selection {
    display: flex;
    justify-content: center;
    align-items: center;

    &::-webkit-scrollbar {
      height: 5px;
    }

    &::-webkit-scrollbar-thumb {
      background: #c1c1c1;
      border-radius: 5px;
    }
  }
}

div.relative.overflow-hidden:has(> #menu-builder-field) {
  // fix for laravel-nova applying overflow-hidden to card elements.
  overflow: visible;
}
</style>
