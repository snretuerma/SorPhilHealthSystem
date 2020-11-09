<template>
  <div>
    <personnel-container
      v-if="trigger == false"
      @add-trigger="addFormTrigger"
    ></personnel-container>
    <personnel-contribution
      v-if="trigger == true"
       @add-close="closeFormTrigger"
      :personnel-contribution="this.contribution"
    ></personnel-contribution>
    <!-- <record-container
            v-if="trigger == false"
            @add-trigger="addFormTrigger"
        ></record-container>
        <medic-container
            v-if="trigger == true"
            @add-close="closeFormTrigger"
            :contribution-records="records"
            :medical-record-id="records.id"
            :total-fee="records.total_fee"
        ></medic-container> -->
  </div>
</template>

<script>
import PersonnelContainer from "../user/PersonnelComponent";
import PersonnelContribution from "../user/PersonnelContributionComponent";
export default {
  data() {
    return {
      contribution: [],
      trigger: false,
    };
  },
  methods: {
    addFormTrigger(data) {
      this.trigger = true;
      this.contribution = data;
    },
    closeFormTrigger() {
      this.trigger = false;
    },
  },
  components: {
    PersonnelContainer,
    PersonnelContribution,
  },
};
</script>
