<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <inertia-link class="text-blue-500" :href="route('overview')">
                    Overview Bins
                </inertia-link>
                <span class="text-gray-500">/</span>
                Edit
            </h2>
        </template>

        <div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">
            <jet-form-section @submitted="submit" :vertical="true">
                <template #form>
                    <div>
                        <jet-label for="name"
                                   value="Name"
                        />

                        <jet-input id="name" type="text"
                                   v-model="editGarbageBinForm.name"
                                   autofocus
                        />

                        <jet-input-error :message="editGarbageBinForm.error('name')"
                                         class="mx-8 mt-2"
                        />
                        <jet-label for="address"
                                   value="Address"
                        />

                        <jet-input id="address" type="text"
                                   v-model="editGarbageBinForm.address"
                        />

                        <jet-input-error :message="editGarbageBinForm.error('address')"
                                         class="mx-8 mt-2"
                        />

                        <jet-label for="city"
                                   value="City"
                        />

                        <jet-input id="city" type="text"
                                   v-model="editGarbageBinForm.city"
                        />

                        <jet-input-error :message="editGarbageBinForm.error('city')"
                                         class="mx-8 mt-2"
                        />
                    </div>
                </template>

                <template #actions>
                    <jet-action-message :on="editGarbageBinForm.recentlySuccessful" class="mr-3">
                        Created.
                    </jet-action-message>

                    <jet-button :class="{ 'opacity-25': editGarbageBinForm.processing }"
                                :disabled="editGarbageBinForm.processing">
                        Opslaan
                    </jet-button>
                </template>
            </jet-form-section>
        </div>
    </app-layout>
</template>

<script>
    import JetActionMessage from "../../Jetstream/ActionMessage";
    import JetButton from "../../Jetstream/Button";
    import JetDangerButton from "../../Jetstream/DangerButton";
    import JetFormSection from "../../Jetstream/FormSection";
    import JetInput from "../../Jetstream/Input";
    import JetInputError from "../../Jetstream/InputError";
    import JetLabel from "../../Jetstream/Label";
    import AppLayout from "../../Layouts/AppLayout";

export default {
    props: {
        garbageBin: Object
    },
    components: {
        AppLayout,
        JetActionMessage,
        JetButton,
        JetDangerButton,
        JetFormSection,
        JetInput,
        JetInputError,
        JetLabel
    },
    data() {
        return {
            editGarbageBinForm: this.$inertia.form({
                name: this.garbageBin.name,
            }, {
                bag: 'editGarbageBin',
                resetOnSuccess: true,
            })
        }
    },
    methods: {
        submit() {
            this.editGarbageBinForm.patch(
                route('overview.edit', {bin: this.garbageBin.id})
            );
        }
    }
}
</script>
