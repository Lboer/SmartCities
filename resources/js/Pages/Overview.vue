<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Overview Bins
            </h2>
        </template>

        <container>

            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                            Name
                                        </th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                            Temperature
                                        </th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                            Distance
                                        </th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                            Updated at
                                        </th>
                                        <th class="px-6 py-3 bg-gray-50"></th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="bin in bins" :key="bin.id">
                                        <td class="px-6 py-4 whitespace-no-wrap">
                                            {{ bin.name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap">
                                            <div class="text-sm leading-5 text-gray-900">
                                                {{ bin.temperature }} °C
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap">
                                            <div class="text-sm leading-5 text-gray-900">
                                                {{ bin.distance }} cm
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap">
                                            <div class="text-sm leading-5 text-gray-900">
                                                {{ bin.last_active_at }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap text-right text-sm leading-5 font-medium">
                                            <inertia-link :href="route('overview.edit_form', {bin: bin.id})" class="text-indigo-600 hover:text-indigo-900">
                                                Bewerken
                                            </inertia-link>

                                            <span @click="garbageBinBeingDeleted = bin.id" class="text-red-600 hover:text-red-900 ml-3 cursor-pointer">
                                                Verwijderen
                                                </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <jet-confirmation-modal :show="garbageBinBeingDeleted" @close="garbageBinBeingDeleted = null">
                <template #title>
                    Garbage Bin Deletion
                </template>

                <template #content>
                    Are you sure you want to delete this garbage bin?
                </template>

                <template #footer>
                    <jet-secondary-button @click.native="garbageBinBeingDeleted = null">
                        Never Mind
                    </jet-secondary-button>

                    <jet-danger-button class="ml-2" @click.native="deleteGarbageBin" :class="{ 'opacity-25': deleteApiTokenForm.processing }" :disabled="deleteApiTokenForm.processing">
                        Delete
                    </jet-danger-button>
                </template>
            </jet-confirmation-modal>
        </container>
    </app-layout>
</template>

<script>
import AppLayout from './../Layouts/AppLayout';
import Container from './../Components/Container';
import JetConfirmationModal from "../Jetstream/ConfirmationModal";
import JetSecondaryButton from "../Jetstream/SecondaryButton";
import JetDangerButton from "../Jetstream/DangerButton";

export default {
    components: {
        AppLayout,
        Container,
        JetConfirmationModal,
        JetSecondaryButton,
        JetDangerButton
    },
    props: {
        bins: Array
    },
    data() {
        return {
            deleteApiTokenForm: this.$inertia.form(),
            garbageBinBeingDeleted: null,
        }
    },
    methods: {
        deleteGarbageBin(arg)
        {
            this.deleteApiTokenForm.delete(route('overview.delete', this.garbageBinBeingDeleted), {
                preserveScroll: true,
                preserveState: true,
            }).then(() => {
                this.garbageBinBeingDeleted = null
            });
        }
    }
}
</script>
