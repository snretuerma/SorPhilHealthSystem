<template>
  <el-card class="box-card">
    <div class="row">
      <div class="col-sm-12">
        <h3>Personnel Contribution Record</h3>
        <div class="card">
          <div class="card-body">
            <div id="test">
                 <div class="row">
                     <div class="col-sm-6">

                         <el-input
                                prefix-icon="el-icon-search"
                                v-model="search"
                                size="medium"
                                placeholder="Type to search"
                            />
                     </div>
                     <div class="col-sm-6" align="right">

                        <el-button @click="backToStaff()">Back to Staff</el-button>
                     </div>
                 </div>
              <el-table :data="listData">
                <el-table-column
                  width="200"
                  label="Contribution"
                  prop="contribution"
                >
                </el-table-column>
                <el-table-column
                  width="200"
                  label="Patient"
                  prop="name"
                  column-key="ase"
                >
                </el-table-column>
                <el-table-column
                  width="200"
                  label="Admission"
                  prop="admission_date"
                  column-key="ase"
                >
                </el-table-column>
                <el-table-column
                  width="200"
                  label="Discharge"
                  prop="discharge_date"
                  column-key="ase"
                >
                </el-table-column>
                <el-table-column width="200" label="Total" prop="total_fee">
                </el-table-column>
              </el-table>
              <div style="text-align: center">
                <el-pagination
                  background
                  layout="prev, pager, next"
                  @current-change="handleCurrentChange"
                  :page-size="pageSize"
                  :total="total"
                >
                </el-pagination>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </el-card>
</template>
<script>
export default {
  props: ["personnelContribution"],
  data() {
    return {
      filtered: [],
      page: 1,
      total: 0,
      pageSize: 10,
      search: "",
      data: [],
      trigger: false,
    };
  },
  methods: {
    handleCurrentChange(val) {
      this.page = val;
    },
    addFormTrigger(data) {
      this.trigger = true;
    },
    closeFormTrigger() {
      this.trigger = false;
    },
    getPerconnelContribution() {
      axios
        .post("personnelcontribution_get", this.personnelContribution)
        .then((response) => {
          response.data.forEach((el) => {
            this.buildPatientData(el);
          });
          this.data = response.data;
        })
        .catch(function (error) {});
    },
    backToStaff(){
        this.$emit("add-close");
    },
    buildName: function (first_name, middle_name, last_name, suffix) {
      return (
        last_name +
        " " +
        suffix +
        ", " +
        first_name +
        " " +
        middle_name.slice(0, 1) +
        "."
      ).trim();
    },
    buildPatientData: function (element) {
      if (element.name_suffix == undefined) {
        element.name_suffix = "";
      }
      element.name = this.buildName(
        element.first_name,
        element.middle_name,
        element.last_name,
        element.name_suffix
      );
    },
  },
  computed: {
    searching() {
      if (!this.search) {
        this.total = this.data.length;
        return this.data;
      }
      this.page = 1;
      return this.data.filter(
        (data) =>
          data.name.toLowerCase().includes(this.search.toLowerCase())
      );
    },
    listData() {
      this.total = this.searching.length;

      return this.searching.slice(
        this.pageSize * this.page - this.pageSize,
        this.pageSize * this.page
      );
    },
  },
  mounted() {
    this.getPerconnelContribution();
    console.log(this.data);
  },
};
</script>