<template>
  <Modal :show="showModal" class="overflow-hidden bg-white rounded-lg shadow-lg dark:bg-gray-800">
    <ModalHeader v-text="__('novaMenuBuilder.delConfirmModalTitle')" />

    <div class="grid gap-6 p-6">
      <p v-if="itemToDelete && itemToDelete.children.length > 0">
        {{ __('novaMenuBuilder.delConfirmModalDescChildren') }}
      </p>
      <p>
        {{ __('novaMenuBuilder.delConfirmModalDesc') }}
      </p>
    </div>

    <ModalFooter class="flex justify-end">
      <div class="ml-auto">
        <CancelButton component="button" type="button" dusk="cancel-action-button" @click.prevent="$emit('closeModal')"
          class="mr-3" />

        <Button type="button" dusk="confirm-action-button" state="danger"
          :label="__('novaMenuBuilder.delConfirmModalConfirm')" @click.prevent="$emit('confirmItemDelete')" />
      </div>
    </ModalFooter>
  </Modal>
</template>

<script>
import { Button } from 'laravel-nova-ui';

export default {
  props: ['showModal', 'itemToDelete'],
  components: { Button },
};
</script>
