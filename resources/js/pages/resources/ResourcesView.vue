<!-- This example requires Tailwind CSS v2.0+ -->
<template>
  <div class="w-full">
    <flex-title :title="title" justify="center" :backTo="{ name: 'root' }" />
    <media />
  </div>
</template>

<script>
import Media from '../../components/media/Media';
import { RESOURCES } from '../../constants/pages';
import FlexTitle from '../../components/layout/menus/FlexTitleComponent';
import { MailIcon, PhoneIcon } from '@heroicons/vue/solid';
import ResourceAttachmentsListComponent from '../../components/resources/ResourceAttachmentsListComponent';

export default {
  components: {
    Media,
    FlexTitle,
    ResourceAttachmentsListComponent,
    MailIcon,
    PhoneIcon,
  },
  data: () => ({ title: RESOURCES }),
};
</script>
