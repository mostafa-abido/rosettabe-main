<!-- This example requires Tailwind CSS v2.0+ -->
<template>
    <article :aria-labelledby="'poll-title-' + poll.id">
        <div>
          <router-link :to="{ name: 'poll', params: { id: poll.id }}" :id="'poll-title-' + poll.id"
                       class="mt-4 text-base font-medium text-gray-900">
            {{ poll.title }}
          </router-link>
        </div>
      <div class="mt-2 text-sm text-gray-700 space-y-4" v-html="poll.description" />
        <div class="mt-2 text-sm text-gray-700 space-y-4">
            <poll-options-list-component :options="poll.options"></poll-options-list-component>
        </div>
        <!--        <div class="mt-6 flex justify-between space-x-8">-->
        <!--            <div class="flex space-x-6">-->
        <!--                      <span class="inline-flex items-center text-sm">-->
        <!--                        <button class="inline-flex space-x-2 text-gray-400 hover:text-gray-500">-->
        <!--                          <ThumbUpIcon class="h-5 w-5" aria-hidden="true" />-->
        <!--                          <span class="font-medium text-gray-900">{{ question.likes }}</span>-->
        <!--                          <span class="sr-only">likes</span>-->
        <!--                        </button>-->
        <!--                      </span>-->
        <!--                <span class="inline-flex items-center text-sm">-->
        <!--                        <button class="inline-flex space-x-2 text-gray-400 hover:text-gray-500">-->
        <!--                          <ChatAltIcon class="h-5 w-5" aria-hidden="true" />-->
        <!--                          <span class="font-medium text-gray-900">{{ question.replies }}</span>-->
        <!--                          <span class="sr-only">replies</span>-->
        <!--                        </button>-->
        <!--                      </span>-->
        <!--                <span class="inline-flex items-center text-sm">-->
        <!--                        <button class="inline-flex space-x-2 text-gray-400 hover:text-gray-500">-->
        <!--                          <EyeIcon class="h-5 w-5" aria-hidden="true" />-->
        <!--                          <span class="font-medium text-gray-900">{{ question.views }}</span>-->
        <!--                          <span class="sr-only">views</span>-->
        <!--                        </button>-->
        <!--                      </span>-->
        <!--            </div>-->
        <!--            <div class="flex text-sm">-->
        <!--                      <span class="inline-flex items-center text-sm">-->
        <!--                        <button class="inline-flex space-x-2 text-gray-400 hover:text-gray-500">-->
        <!--                          <ShareIcon class="h-5 w-5" aria-hidden="true" />-->
        <!--                          <span class="font-medium text-gray-900">Share</span>-->
        <!--                        </button>-->
        <!--                      </span>-->
        <!--            </div>-->
        <!--        </div>-->
    </article>
</template>

<script>
import { RadioGroup, RadioGroupDescription, RadioGroupLabel, RadioGroupOption } from '@headlessui/vue'
import PollOptionsListComponent from "../poll/PollOptionsListComponent";

export default {
    name: "PollCardComponent",
    props: {
        poll: [],
    },
    components: {
        PollOptionsListComponent,
        RadioGroup,
        RadioGroupDescription,
        RadioGroupLabel,
        RadioGroupOption,
    },
}
</script>
