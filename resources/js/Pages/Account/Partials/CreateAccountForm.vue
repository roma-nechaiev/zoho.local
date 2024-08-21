<script setup lang="ts">
import { ref } from "vue";
import { useForm, usePage } from "@inertiajs/vue3";
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from "@/Components/ui/dialog";
import { Button } from "@/Components/ui/button";
import { Label } from "@/Components/ui/label";
import { Input } from "@/Components/ui/input";
import { toast } from "vue-sonner";
import InputError from "@/Components/InputError.vue";

const form = useForm({
    Account_Name: "",
    Phone: "",
    Website: "",
});

const open = ref(false);

const submitHandler = () => {
    form.post(route("accounts.create"), {
        preserveScroll: true,

        replace: true,
        preserveState: true,
        onSuccess: () => {
            const { message } = usePage().props.flash;
            form.reset();
            open.value = false;
            toast.success(message);
        },
    });
};
</script>

<template>
    <Dialog v-model:open="open">
        <DialogTrigger as-child>
            <Button>New Account</Button>
        </DialogTrigger>
        <DialogContent>
            <DialogHeader>
                <DialogTitle>Create Account</DialogTitle>
                <DialogDescription
                    >Create your account here. Click save when you're done.
                </DialogDescription>
            </DialogHeader>
            <form @submit.prevent="submitHandler" class="grid gap-4">
                <div class="grid grid-cols-4 items-center gap-x-4">
                    <Label for="name" class="text-right">Account name</Label>
                    <Input
                        id="name"
                        class="col-span-3"
                        v-model="form.Account_Name"
                        required
                    />
                    <InputError
                        class="col-span-3 col-start-2 mt-2"
                        :message="form.errors.Account_Name"
                    />
                </div>
                <div class="grid grid-cols-4 items-center gap-x-4">
                    <Label for="Phone" class="text-right">Phone</Label>
                    <Input
                        id="Phone"
                        type="tel"
                        class="col-span-3"
                        v-model="form.Phone"
                        required
                    />
                    <InputError
                        class="col-span-3 col-start-2 mt-2"
                        :message="form.errors.Phone"
                    />
                </div>
                <div class="grid grid-cols-4 items-center gap-x-4">
                    <Label for="Website" class="text-right">Website</Label>
                    <Input
                        id="Website"
                        class="col-span-3"
                        v-model="form.Website"
                        required
                    />
                    <InputError
                        class="col-span-3 col-start-2 mt-2"
                        :message="form.errors.Website"
                    />
                </div>

                <DialogFooter>
                    <Button :disabled="form.processing">Submit</Button>
                </DialogFooter>
            </form>
        </DialogContent>
    </Dialog>
</template>
