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
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/Components/ui/select";
import { Button } from "@/Components/ui/button";
import { Label } from "@/Components/ui/label";
import { Input } from "@/Components/ui/input";
import { toast } from "vue-sonner";
import InputError from "@/Components/InputError.vue";

const props = defineProps(["account"]);

const form = useForm({
    Deal_Name: "",
    Stage: "",
    id: props.account,
});

const open = ref(false);

const submitHandler = () => {
    form.post(route("dials.create"), {
        preserveScroll: true,
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
            <Button variant="secondary" size="sm">New Dial</Button>
        </DialogTrigger>
        <DialogContent>
            <DialogHeader>
                <DialogTitle>Create Dial</DialogTitle>
                <DialogDescription
                    >Create your dial here. Click save when you're done.
                </DialogDescription>
            </DialogHeader>
            <form @submit.prevent="submitHandler" class="grid gap-4">
                <div class="grid grid-cols-4 items-center gap-x-4">
                    <Label for="name" class="text-right">Deal name</Label>
                    <Input
                        id="name"
                        class="col-span-3"
                        v-model="form.Deal_Name"
                        required
                    />
                    <InputError
                        class="col-span-3 col-start-2 mt-2"
                        :message="form.errors.Deal_Name"
                    />
                </div>
                <div class="grid grid-cols-4 items-center gap-x-4">
                    <Label for="Stage" class="text-right">Stage</Label>
                    <Select v-model="form.Stage">
                        <SelectTrigger id="Stage" class="col-span-3">
                            <SelectValue placeholder="Select a stage" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem value="Qualification"
                                >Qualification</SelectItem
                            >
                            <SelectItem value="Needs Analysis"
                                >Needs Analysis</SelectItem
                            >
                            <SelectItem value="Value Proposition"
                                >Value Proposition</SelectItem
                            >
                            <SelectItem value="Identify Decision Makers"
                                >Identify Decision Makers</SelectItem
                            >
                            <SelectItem value="Proposal/Price Quote"
                                >Proposal/Price Quote</SelectItem
                            >
                            <SelectItem value="Negotiation/Review"
                                >Negotiation/Review</SelectItem
                            >
                            <SelectItem value="Closed Won"
                                >Closed Won</SelectItem
                            >
                            <SelectItem value="Closed Lost"
                                >Closed Lost</SelectItem
                            >
                            <SelectItem value="Closed Lost to Competition"
                                >Closed Lost to Competition</SelectItem
                            >
                        </SelectContent>
                    </Select>

                    <InputError
                        class="col-span-3 col-start-2 mt-2"
                        :message="form.errors.Stage"
                    />
                </div>

                <DialogFooter>
                    <Button :disabled="form.processing">Submit</Button>
                </DialogFooter>
            </form>
        </DialogContent>
    </Dialog>
</template>
