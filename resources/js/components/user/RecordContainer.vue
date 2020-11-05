<template>
    <div>
        <record-container
            v-if="trigger == false"
            @add-trigger="addFormTrigger"
        ></record-container>
        <medic-container
            v-if="trigger == true"
            @add-close="closeFormTrigger"
            :contribution-records="records"
            :medical-record-id="records.id"
            :total-fee="records.total_fee"
        ></medic-container>
    </div>
</template>

<script>
import RecordContainer from "../user/RecordComponent";
import MedicContainer from "../user/MedicalRecordComponent";
export default {
    data() {
        return {
            records: [],
            trigger: false
        };
    },
    methods: {
        addFormTrigger(data) {
            this.trigger = true;
            this.records = data;
        },
        closeFormTrigger() {
            this.trigger = false;
        }
    },
    mounted() {
        console.log(this.records);
    },
    components: {
        RecordContainer,
        MedicContainer
    }
};
</script>
