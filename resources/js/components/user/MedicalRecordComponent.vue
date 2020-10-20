<template>
  <el-card class="box-card">
    <h3>Medical Record Staff</h3>
    <hr />

    <el-form label-width="120px" size="small">
      <el-row>
        <div v-if="personnels.length >= 1">
          <div  v-for="(personnel, index) in personnels" :key="index"  >
            <el-col :span="12">
              <el-card class="box-card">
                <div slot="header" class="clearfix">
                  <span>Card name</span>
                  <el-form-item>
                    <el-button
                      @click="removeStaff"
                      v-if="index >=0"
                      style="float: right; padding: 3px 0"
                    >Close</el-button>
                  </el-form-item>
                </div>

                <el-form-item
                  label="Personnel"
                  :label-width="formLabelWidth"
                  prop="personnel"
                >
                  <el-autocomplete
                    width="200px"
                    class="inline-input"
                    v-model="personnel.state"
                    :fetch-suggestions="querySearch"
                    placeholder="Please Input"
                    :trigger-on-focus="false"
                    @select="handleSelect"
                  ></el-autocomplete>
                </el-form-item>
                <!-- <el-form-item label="Personnel" prop="personnel_type">
                  <el-select v-model="personnel.stafftype" placeholder="choose">
                    <el-option label="Medical" value="medical"></el-option>
                    <el-option
                      label="Non-medical"
                      value="non-medical"
                    ></el-option>
                  </el-select>
                </el-form-item> -->

                <el-form-item label="Contribution" prop="contribution_type">
                  <el-select
                    v-model="personnel.contribution"
                    placeholder="choose"
                  >
                    <el-option
                      label="Attending Physician"
                      value="Attending Physician"
                    ></el-option>
                    <el-option
                      label="Requesting Physician"
                      value="Requesting Physician"
                    ></el-option>
                    <el-option
                      label="Surgeon Physician"
                      value="Surgeon Physician"
                    ></el-option>
                    <el-option
                      label="Health Care Physician"
                      value="Health Care Physician"
                    ></el-option>
                    <el-option
                      label="ER Physician"
                      value="ER Physician"
                    ></el-option>
                    <el-option
                      label="Anesthesiologist"
                      value="Anesthesiologist"
                    ></el-option>
                    <el-option
                      label="Co-management"
                      value="Co-management"
                    ></el-option>
                    <el-option label="Admitting" value="Admitting"></el-option>
                  </el-select>
                </el-form-item>

                <el-form-item
                  label="Computed PF"
                  :label-width="formLabelWidth"
                >
                  <el-input
                    v-model="personnel.computed_pf"
                    autocomplete="off"
                  ></el-input>
                </el-form-item>
              </el-card>
            </el-col>
          </div>
        </div>
      </el-row>
      <el-form-item>
        <el-button type="primary" @click="onSubmit">Create</el-button>
        <el-button @click="addStaff">Add Staff</el-button>
      </el-form-item>
    </el-form>
  </el-card>
</template>
<script>
const generateNewPersonnel = () => ({
  contribution: "",
  patient_id: "",
  state: "",
  stafftype: "",
  staff: "",
  query: "",
});
export default {
  props: ["medicalRecordId"],
  data() {
    return {
       medical_record_id:"",
      formLabelWidth: "120px",
      staff: [],
      query: "",
      state: "",
      personnels: [],
      search_data: [],
      rules: {
          personnel: [
            { required: true, message: 'Please input Personnel name', trigger: 'blur' },
          ],
          personnel_type: [
           { required: true, message: 'Please input Personnel type', trigger: 'blur' },
          ],
          contribution_type: [
            { required: true, message: 'Please input Contribution type', trigger: 'blur' },
          ],
        }
    };
  },
  methods: {
    save() {
      let validCount = 0;
      const formAmount = this.personnels.length + 1;
      
      this.$refs.personnels.validate(valid => {
        if ( valid ) {
          validCount++;
        } 
      });
      this.personnels.forEach((personnel, index) => {        
        this.$refs[`dynamicFieldsForm${index}`][0].validate(valid => {
          if ( valid ) {
            validCount++;
          }
        });
      });

      if ( validCount === formAmount ) {
        this.$message.success('通过所有验证');
        // 通过所有验证，进行后续处理
      }
    },
    submitForm(formName) {
      this.$refs[formName].validate((valid) => {
        if (valid) {
          alert("submit!");
        } else {
          console.log("error submit!!");
          return false;
        }
      });
    },
    resetForm(formName) {
      this.$refs[formName].resetFields();
    },
    addStaff() {
      this.personnels.push(new generateNewPersonnel());
    },
    removeStaff() {
      if (this.personnels.length > 1) {
        this.personnels.pop(this.personnels[this.personnels.length - 1]);
      }
    },
    handleSelect(item) {
      var index = this.personnels.length-1;
      this.personnels[index]['is_parttime']=null;
      this.personnels[index]['is_private']=null;
      this.personnels[index]['designation']=null;
      this.personnels[index]['is_parttime']=item.is_parttime;
      this.personnels[index]['is_private']=item.is_private;
      this.personnels[index]['designation']=item.designation;
      console.log(index);
      
    },
    querySearch(queryString, cb) {
      var links = this.staff;
      var results = queryString
        ? links.filter(this.createFilter(queryString))
        : links;
      // call callback function to return suggestions
      console.log(results);
      cb(results);
    },
    createFilter(queryString) {
      return (link) => {
        return (
          link.value.toLowerCase().indexOf(queryString.toLowerCase()) === 0
        );
      };
    },
    onSubmit() {
      var data={
        medical_record_id:this.medicalRecordId,
        personnel:this.personnels
      };
      axios
        .post("/user/contrirecord_add", data)
        .then((response) => {
          console.log(response.data);
        })
        .catch(function (error) {});
    },
    getName: function (name) {
      this.personnels.query = name;
      this.search_data = [];
    },
    getStaff() {
      axios
        .get("/user/personnel_get")
        .then((response) => {
          response.data.forEach((element) => {
            element.value =
              element.first_name +
              " " +
              element.middle_name +
              " " +
              element.last_name +
              (element.name_suffix === null ? "" : " " + element.name_suffix);
          });
          this.staff = response.data;
        })
        .catch(function (error) {});
    },
  },
  mounted() {
    this.getStaff();
    // this.search();
    this.personnels.push(new generateNewPersonnel());
  },
};
</script>