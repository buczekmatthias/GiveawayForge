<script setup lang="ts">
import type { DateValue } from '@internationalized/date';
import {
    getLocalTimeZone,
    now,
    Time,
    today,
    ZonedDateTime,
} from '@internationalized/date';
import { ChevronDownIcon } from 'lucide-vue-next';
import type { Ref } from 'vue';
import { onMounted, ref, watch } from 'vue';
import FormGroup from '@/components/FormGroup.vue';
import { Button } from '@/components/ui/button';
import { Calendar } from '@/components/ui/calendar';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from '@/components/ui/popover';

type Props = {
    label: string;
    minDateTime?: ZonedDateTime;
};

const props = defineProps<Props>();

const getTime = (dt: ZonedDateTime | undefined = undefined) => {
    const n = dt ? dt : now(getLocalTimeZone());

    return new Time(n.hour, n.minute);
};

const date = ref(today(getLocalTimeZone())) as Ref<DateValue>;
const time = ref(getTime(props.minDateTime).toString());
const open = ref<boolean>(false);

const model = defineModel();

onMounted(() => {
    model.value = props.minDateTime;
});

watch(
    [date, time],
    () => {
        const [h, m, s] = time.value.split(':');
        const t = new Time(parseInt(h), parseInt(m), parseInt(s));

        model.value = new ZonedDateTime(
            date.value.year,
            date.value.month,
            date.value.day,
            getLocalTimeZone(),
            now(getLocalTimeZone()).offset,
            t.hour,
            t.minute,
            t.second,
        );
    },
    { deep: true },
);
</script>

<template>
    <FormGroup class="grid grid-cols-[1.5fr_1fr] gap-4" :group-label="label">
        <div class="flex flex-col gap-3">
            <Label for="date-picker" class="px-1"> Date </Label>
            <Popover v-model:open="open">
                <PopoverTrigger as-child>
                    <Button
                        id="date-picker"
                        variant="outline"
                        class="justify-between font-normal"
                    >
                        {{
                            date
                                ? date
                                      .toDate(getLocalTimeZone())
                                      .toLocaleDateString()
                                : 'Select date'
                        }}
                        <ChevronDownIcon />
                    </Button>
                </PopoverTrigger>
                <PopoverContent
                    class="w-auto overflow-hidden p-0"
                    align="start"
                >
                    <Calendar
                        :model-value="date"
                        :min-value="minDateTime"
                        @update:model-value="
                            (value) => {
                                if (value) {
                                    date = value;
                                    open = false;
                                }
                            }
                        "
                    />
                </PopoverContent>
            </Popover>
        </div>
        <div class="flex flex-col gap-3">
            <Label for="time-picker" class="px-1">Time</Label>
            <Input
                id="time-picker"
                type="time"
                step="1"
                v-model="time"
                class="appearance-none bg-background [&::-webkit-calendar-picker-indicator]:hidden [&::-webkit-calendar-picker-indicator]:appearance-none"
            />
        </div>
    </FormGroup>
</template>
