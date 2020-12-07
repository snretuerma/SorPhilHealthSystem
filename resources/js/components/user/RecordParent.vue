<template>
    <div>
        <record-child  v-if="trigger==false" @add-open="addFormTrigger"></record-child>
        <add-record-child v-if="trigger==true" @add-close="closeFormTrigger" :data="data"></add-record-child>
    </div>
</template>

<script>
import RecordChild from "../user/RecordChild";
import AddRecordChild from "../user/AddRecordChild";
export default {
    data() {
        return {
            trigger: false,
            data:[]
        };
    },
    methods: {
        //parent trigger and data is parameter to pass
        addFormTrigger(data) {
            this.data=data;
            this.trigger = true;
        },
        closeFormTrigger() {
            this.trigger = false;
        }
    },
    components: {
        RecordChild,
        AddRecordChild
    }
};
</script>
