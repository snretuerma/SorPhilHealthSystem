<template>
    <div>
        <record-child  v-if="trigger==false" @add-open="addFormTrigger"></record-child>
        <add-record-child v-if="trigger==true" @add-close="closeFormTrigger" :process-type="processType" :data="data"></add-record-child>
    </div>
</template>

<script>
import RecordChild from "../user/RecordChild";
import AddRecordChild from "../user/AddRecordChild";
export default {
    data() {
        return {
            trigger: false,
            processType:'',
            data:[]
        };
    },
    methods: {
        //parent trigger and data is parameter to pass
        addFormTrigger(data, type) {
            this.data=data;
            this.trigger = true;
            console.log(type);
            if (type == "edit") {
                this.processType="edit";
            } else {
                this.processType="add";
            }

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
