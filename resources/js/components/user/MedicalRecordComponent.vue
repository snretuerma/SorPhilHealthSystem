<template>
  <el-card class="box-card">
    <h3>Medical Record Staff</h3>
    <hr />

    <el-form label-width="120px" size="small">
      <el-row>
        <div v-if="personnels.length >= 1">
          <div v-for="(personnel, index) in personnels" :key="index">
            <el-col :span="12">
              <el-card class="box-card">
                <div slot="header" class="clearfix">
                  <span>Card name</span>
                  <el-form-item>
                    <el-button
                      @click="removeStaff"
                      v-if="index >= 0"
                      style="float: right; padding: 3px 0"
                      >Close</el-button
                    >
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
                    @change="getStaffCode"
                    v-model="personnel.contribution"
                    :disabled="disableSelect[index]"
                  >
                    <el-option
                      label="Attending Physician"
                      value="0"
                    ></el-option>
                    <el-option
                      label="Requesting Physician"
                      value="1"
                    ></el-option>
                    <el-option label="Surgeon Physician" value="2"></el-option>
                    <el-option
                      label="Health Care Physician"
                      value="3"
                    ></el-option>
                    <el-option label="ER Physician" value="4"></el-option>
                    <el-option label="Anesthesiologist" value="5"></el-option>
                    <el-option label="Co-management" value="6"></el-option>
                    <el-option label="Admitting" value="7"></el-option>
                  </el-select>
                </el-form-item>

                <el-form-item label="Computed PF" :label-width="formLabelWidth">
                  <el-input
                    readonly
                    v-model="personnel.computePF"
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
        <el-button @click="getStaffCode">Compute</el-button>
        <el-button @click="addStaff">Add Staff</el-button>
      </el-form-item>
    </el-form>
  </el-card>
</template>
<script>
import constants from "../../constants.js";
const generateNewPersonnel = () => ({
  contribution: "",
  contributionType:"",
  state: "",
  staff:"",
});
export default {
  props: ["medicalRecordId", "totalFee"],
  data() {
    return {
      contributionType: [
        "Attending Physician",
        "Requesting Physician",
        "Surgeon Physician",
        "Health Care Physician",
        "ER Physician",
        "Anesthesiologist",
        "Co-management",
        "Admitting",
      ],
      data: {
        attending: 0,
        requesting: 0,
        surgeon: 0,
        health: 0,
        er: 0,
        anesthe: 0,
        comanage: 0,
        admitting: 0,
      },
      disableSelect:[],
      medical_record_id: "",
      medical_record_pooled: "",
      medical_record_shared: "",
      personellType: [],
      formLabelWidth: "120px",
      staff: [],
      query: "",
      is_public: "",
      is_private: "",
      state: "",
      personnels: [],
      search_data: [],
      rules: {
        personnel: [
          {
            required: true,
            message: "Please input Personnel name",
            trigger: "blur",
          },
        ],
        personnel_type: [
          {
            required: true,
            message: "Please input Personnel type",
            trigger: "blur",
          },
        ],
        contribution_type: [
          {
            required: true,
            message: "Please input Contribution type",
            trigger: "blur",
          },
        ],
      },
    };
  },
  methods: {
    save() {
      let validCount = 0;
      const formAmount = this.personnels.length + 1;

      this.$refs.personnels.validate((valid) => {
        if (valid) {
          validCount++;
        }
      });
      this.personnels.forEach((personnel, index) => {
        this.$refs[`dynamicFieldsForm${index}`][0].validate((valid) => {
          if (valid) {
            validCount++;
          }
        });
      });

      if (validCount === formAmount) {
        this.$message.success("通过所有验证");
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
    getStaffCode() {
      this.data.attending = 0;
      this.data.requesting = 0;
      this.data.surgeon = 0;
      this.data.er = 0;
      this.data.admitting = 0;
      this.data.comanage = 0;
      this.data.anesthe = 0;
      this.data.health = 0;
      this.personnels.forEach((element) => {
        if (element.contribution == 0) {
          this.data.attending += 1;
        } else if (element.contribution == 1) {
          this.data.requesting += 1;
        } else if (element.contribution == 2) {
          this.data.surgeon += 1;
        } else if (element.contribution == 3) {
          this.data.health += 1;
        } else if (element.contribution == 4) {
          this.data.er += 1;
        } else if (element.contribution == 5) {
          this.data.anesthe += 1;
        } else if (element.contribution == 6) {
          this.data.comanage += 1;
        } else {
          this.data.admitting += 1;
        }
      });
      var holder = 0;
      var total = 0;
      holder = this.medical_record_shared;
      this.personnels.forEach((element) => {
        if (element.contribution == "1") {
          holder = holder - Number(this.medical_record_shared * 0.1).toFixed(2);
          total = holder;
          console.log("holder 1_"+ holder);
          element.computePF = Number(
            Number(this.medical_record_shared * 0.1)
          ).toFixed(2);
          this.personnels.forEach((element1)=>{
            if(element1.contribution=="0")
            {
              
              element1.computePF=(holder/ Number(this.data.attending)).toFixed(2);
            }
            // else if(element1.contribution=="1") {
            //   console.log("wewe");
            //   element.computePF=Number(holder)-(holder-Number(this.medical_record_shared*.10)).toFixed(2);
            // }
            // else
            // {
            //     element1.computePF=((this.medical_record_shared * this.data.requesting)-holder).toFixed(2);
            
            // }
          })
        } else if (element.contribution == "2") {
          holder = holder - Number(this.medical_record_shared * 0.1).toFixed(2);
          total = holder;
          element.computePF = Number(
            Number(this.medical_record_shared * 0.1)
          ).toFixed(2);
          this.personnels.forEach((element1)=>{
            if(element1.contribution=="0")
            {
               element1.computePF=(holder/ Number(this.data.attending)).toFixed(2);
              // element1.computePF=(holder).toFixed(2);;
            }
          })
        } else if (element.contribution == "3") {
          holder = holder - Number(this.medical_record_shared * 0.1).toFixed(2);
          total = holder;
          element.computePF = Number(
            Number(this.medical_record_shared * 0.1)
          ).toFixed(2);
         this.personnels.forEach((element1)=>{
            if(element1.contribution=="0")
            {
              element1.computePF=(holder/ Number(this.data.attending)).toFixed(2);
              // element1.computePF=(holder).toFixed(2);;
            }
          })
        } else if (element.contribution == "4") {
          holder = holder - Number(this.medical_record_shared * 0.1).toFixed(2);
          total = holder;
          element.computePF = Number(
            Number(this.medical_record_shared * 0.1)
          ).toFixed(2);
          this.personnels.forEach((element1)=>{
            if(element1.contribution=="0")
            {
              element1.computePF=(holder/ Number(this.data.attending)).toFixed(2);
              // element1.computePF=(holder).toFixed(2);;
            }
          })
        } else if (element.contribution == "5") {
          holder = holder - Number(this.medical_record_shared * 0.3).toFixed(2);
          total = holder;
          element.computePF = Number(
            Number(this.medical_record_shared * 0.3)
          ).toFixed(2);
          this.personnels.forEach((element1)=>{
            if(element1.contribution=="0")
            {
              element1.computePF=(holder/ Number(this.data.attending)).toFixed(2);
              // element1.computePF=(holder).toFixed(2);;
            }
          })
        } else if (element.contribution == "6") {
          holder = holder - Number(this.medical_record_shared * 0.2).toFixed(2);
          total = holder;
          element.computePF = Number(
            Number(this.medical_record_shared * 0.2)
          ).toFixed(2);
          this.personnels.forEach((element1)=>{
            if(element1.contribution=="0")
            {
              element1.computePF=(holder/ Number(this.data.attending)).toFixed(2);
              // element1.computePF=(holder).toFixed(2);;
            }
          })
        } else if (element.contribution == "7") {
          holder = holder - Number(this.medical_record_shared * 0.1).toFixed(2);
          total = holder;
          element.computePF = Number(
            Number(this.medical_record_shared * 0.1)
          ).toFixed(2);
          this.personnels.forEach((element1)=>{
            if(element1.contribution=="0")
            {
              element1.computePF=(holder/ Number(this.data.attending)).toFixed(2);
              // element1.computePF=(holder).toFixed(2);
            }
          })
        } 
        else {
           holder = Number(holder).toFixed(2);
           total = Number(holder);
          element.computePF = Number(this.medical_record_shared).toFixed(2);
          this.personnels.forEach((element1)=>{
            if(element.contribution=="0")
            {
              element1.computePF=(holder/this.data.attending).toFixed(2);
            }
            // else 
            // {
            //   console.log("1-7")
            //   element1.computePF=Number(holder)-(Number(this.data.requesting) * Number(this.medical_record_shared)).toFixed(2);
            // }
          })
        
         
        
          
        //   this.personnels.forEach((element1)=>{
        //     holder=(Number(element.computePF)/Number(this.data.attending)).toFixed(2);
        //     if(element1.contribution=="0")
        //     {
        //       element1.computePF=holder;
        //     }
        //     else
        //     {
        //       // element1.computePF=holder-(this.medical_record_shared*.10);
        //     }
        //   })
        
        }
      });
    },
    addStaff() {
      this.personnels.push(new generateNewPersonnel());
      this.disableSelect.push(false);
      // this.getStaffCode();
    },
    removeStaff() {
      if (this.personnels.length > 1) {
        this.personnels.pop(this.personnels[this.personnels.length - 1]);
      }
      this.getStaffCode();
    },
    handleSelect(item) {
      var index = this.personnels.length - 1;
      this.personnels[index]["is_parttime"] = null;
      this.personnels[index]["is_private"] = null;
      this.personnels[index]["designation"] = null;
      this.personnels[index]["is_parttime"] = item.is_parttime;
      this.personnels[index]["is_private"] = item.is_private;
      this.personnels[index]["designation"] = item.designation;
      console.log(index);
    },
    querySearch(queryString, cb) {
      var links = this.staff;
      var results = queryString
        ? links.filter(this.createFilter(queryString))
        : links;
      // call callback function to return suggestions
      this.personnels.forEach((element)=>{
        element.staff=results[0].id;
      });
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
      this.personnels.forEach((element)=>{
        element.contributionType=constants.contributionType[Number(element.contribution)];
      });
      var data = {
        medical_record_id: this.medicalRecordId,
        personnel: this.personnels,
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
    computePF() {},
    getStaff() {
      axios
        .get("/user/personnel_get")
        .then((response) => {
          response.data.forEach((element) => {
            if (element.designation == 1) {
              if (element.is_private == 1) {
                this.is_private++;
              } else {
                this.is_public++;
              }
              element.value =
                element.first_name +
                " " +
                element.middle_name +
                " " +
                element.last_name +
                (element.name_suffix === null ? "" : " " + element.name_suffix);
            } else {
              element.value = "";
            }
          });
          this.staff = response.data;
          console.log(this.staff);
        })
        .catch(function (error) {});
    },
  },
  computed: {},
  mounted() {
    this.getStaff();
    this.personnels.push(new generateNewPersonnel());
    this.medical_record_shared = ((this.totalFee / 2) * 0.7).toFixed(2);
    this.medical_record_pooled = ((this.totalFee / 2) * 0.3).toFixed(2);
    this.personnels[0].contribution="0";
    this.getStaffCode();
    this.disableSelect.push(true);
  },
};
</script>
