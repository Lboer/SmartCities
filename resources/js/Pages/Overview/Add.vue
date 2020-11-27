<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <inertia-link class="text-blue-500" :href="route('overview')">
                    Overview Bins
                </inertia-link>
                <span class="text-gray-500">/</span>
                Add
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
                                   v-model="addGarbageBinForm.name"
                                   autofocus
                        />

                        <jet-input-error :message="addGarbageBinForm.error('name')"
                                         class="mx-8 mt-2"
                        />
                        <jet-label for="address"
                                   value="Address"
                        />

                        <jet-input id="address" type="text"
                                   v-model="addGarbageBinForm.address"
                        />

                        <jet-input-error :message="addGarbageBinForm.error('address')"
                                         class="mx-8 mt-2"
                        />

                        <jet-label for="city"
                                   value="City"
                        />

                        <jet-input id="city" type="text"
                                   v-model="addGarbageBinForm.city"
                        />

                        <jet-input-error :message="addGarbageBinForm.error('city')"
                                         class="mx-8 mt-2"
                        />
                    </div>
                </template>

                <template #actions>
                    <jet-action-message :on="addGarbageBinForm.recentlySuccessful" class="mr-3">
                        Created.
                    </jet-action-message>

                    <jet-button :class="{ 'opacity-25': addGarbageBinForm.processing }"
                                :disabled="addGarbageBinForm.processing">
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
                addGarbageBinForm: this.$inertia.form({
                    name: null,
                    address: null,
                    city: null
                }, {
                    bag: 'addGarbageBin',
                    resetOnSuccess: true,
                })
            }
        },
        methods: {
            submit() {
                this.addGarbageBinForm.post(
                    route('overview.add')
                );
            }
        }
    }
</script>
