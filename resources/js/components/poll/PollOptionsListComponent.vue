<template>
    <div class="w-full mx-auto">
        <RadioGroup v-model="selected">
            <RadioGroupLabel class="sr-only">Server size</RadioGroupLabel>
            <div class="space-y-2">
                <RadioGroupOption
                    as="template"
                    v-for="option in options"
                    :key="option.name"
                    :value="option.id"
                    :v-slot="option.voted"
                >
                    <div
                        :class="[
                option.voted
                  ? 'ring-2 ring-offset-2 ring-offset-rose-500 ring-white ring-opacity-60'
                  : '',

              ]"
                        class="relative flex px-5 py-4 rounded-lg shadow-md cursor-pointer focus:outline-none"
                    >
                        <div class="flex items-center justify-between w-full">
                            <div class="flex items-center">
                                <div class="text-sm">
                                    <RadioGroupLabel
                                        as="p"
                                        :class="option.voted ? 'text-gray-900' : 'text-gray-900'"
                                        class="font-medium"
                                    >
                                        {{ option.name }}
                                    </RadioGroupLabel>

                                </div>
                            </div>
                            <div class="flex-shrink-0">
                                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" @click="vote(option.id)">
                                    <circle
                                        cx="12"
                                        cy="12"
                                        r="12"
                                        fill="#000"
                                        fill-opacity="0.2"
                                    />
                                    <path
                                        d="M7 13l3 3 7-7"
                                        stroke="#000"
                                        stroke-width="1.5"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    />
                                </svg>
                            </div>
                        </div>
                    </div>
                </RadioGroupOption>
            </div>
        </RadioGroup>
    </div>
</template>

<script>
import {
    RadioGroup,
    RadioGroupLabel,
    RadioGroupDescription,
    RadioGroupOption,
} from '@headlessui/vue'
import {mapActions} from "vuex";

export default {
    components: {
        RadioGroup,
        RadioGroupLabel,
        RadioGroupDescription,
        RadioGroupOption,
    },

    props: {
        options: Array,
    },

    methods: {
        ...mapActions({
            getPoll: 'poll/getPoll',
            attachPollOption: 'poll/attachPollOption',
        }),

        vote(option) {
            this.attachPollOption(option);
            this.getPoll(this.$route.params.id);
        }
    }
}
</script>
