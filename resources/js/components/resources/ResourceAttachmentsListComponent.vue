<template>
    <div class="sm:col-span-2">
        <dt class="text-sm font-medium text-gray-500">
            Attachments
        </dt>
        <dd class="mt-1 text-sm text-gray-900">
            <ul class="border border-gray-200 rounded-md divide-y divide-gray-200">
                <li class="pl-3 pr-4 py-3 flex items-center justify-between text-sm">
                    <div class="w-0 flex-1 flex items-center">
                        <PaperClipIcon class="flex-shrink-0 h-5 w-5 text-gray-400" aria-hidden="true" />
                        <span class="ml-2 flex-1 w-0 truncate">
                             тмаодт
                            </span>
                    </div>
                    <div class="ml-4 flex-shrink-0">
                        <a  class="font-medium text-blue-600 hover:text-blue-500">
                            Download
                        </a>
                    </div>
                </li>
            </ul>
        </dd>
    </div>
</template>

<script>
import {
    AcademicCapIcon,
    BadgeCheckIcon,
    CashIcon,
    ClockIcon,
    ReceiptRefundIcon,
    UsersIcon,
} from '@heroicons/vue/outline'

const actions = [
    {
        title: 'Request time off',
        href: '#',
        icon: ClockIcon,
        iconForeground: 'text-teal-700',
        iconBackground: 'bg-teal-50',
    },
    {
        title: 'Benefits',
        href: '#',
        icon: BadgeCheckIcon,
        iconForeground: 'text-purple-700',
        iconBackground: 'bg-purple-50',
    },

]

export default {
    setup() {
        return {
            actions,
        }
    },
}
</script>
