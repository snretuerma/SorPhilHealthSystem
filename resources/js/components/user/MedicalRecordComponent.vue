<template>
  <el-card class="box-card">
    <div class="row">
      <div class="col-sm-6">
        <h3>Medical Record Staff</h3>
      </div>

      <div class="col-sm-6" align="right">
        <el-row>
          <el-button type="success" size="medium" @click="onSubmit()"
            >Save</el-button
          >
          <el-button type="primary" size="medium" @click="addAttending()"
            >Add Attending Physician</el-button
          >
          <el-button type="primary" size="medium" @click="addStaff()"
            >Add Other Physician</el-button
          >
        </el-row>
      </div>
    </div>
    <hr />
    <el-form label-width="120px" size="small">
      <el-row>
        <div v-if="contributionRecords.type == undefined">
          <div v-for="(doctor, index) in attending" :key="index">
            <el-col :span="12">
              <el-card class="box-card">
                <div slot="header" class="clearfix">
                  <span>Attending Physician</span>
                  <el-form-item>
                    <el-button
                      @click="removeAttending"
                      v-if="index >= 0"
                      style="float: right; padding: 3px 0"
                      >Close</el-button
                    >
                  </el-form-item>
                </div>

                <el-form-item label="Personnel" :label-width="formLabelWidth">
                  <el-autocomplete
                    width="200px"
                    class="inline-input"
                    v-model="doctor.state"
                    :fetch-suggestions="querySearchAttending"
                    placeholder="Please Input"
                    :trigger-on-focus="false"
                    @select="handleSelectAttending"
                  ></el-autocomplete>
                  <br />
                  <span class="font-italic text-danger" v-if="errors.state"
                    ><small>{{ errors.state[0] }}</small></span
                  >
                </el-form-item>

                <el-form-item label="Contribution">
                  <el-select
                    @change="getStaffCode"
                    v-model="doctor.contribution"
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
                    v-model="doctor.computePF"
                    autocomplete="off"
                  ></el-input>
                </el-form-item>
              </el-card>
            </el-col>
          </div>
        </div>
        <div v-else>
          <div v-for="(doctor, index) in attending" :key="index">
            <el-col :span="12">
              <el-card class="box-card">
                <div slot="header" class="clearfix">
                  <span>Attending Physician</span>
                  <el-form-item>
                    <el-button
                      @click="removeAttending"
                      v-if="index >= 0"
                      style="float: right; padding: 3px 0"
                      >Close</el-button
                    >
                  </el-form-item>
                </div>

                <el-form-item label="Personnel" :label-width="formLabelWidth">
                  <el-autocomplete
                    width="200px"
                    class="inline-input"
                    v-model="doctor.state"
                    :fetch-suggestions="querySearchAttending"
                    placeholder="Please Input"
                    :trigger-on-focus="false"
                    @select="handleSelectAttending"
                  ></el-autocomplete>
                  <br />
                  <span class="font-italic text-danger" v-if="errors.state"
                    ><small>{{ errors.state[0] }}</small></span
                  >
                </el-form-item>

                <el-form-item label="Contribution">
                  <el-select
                    @change="getStaffCode"
                    v-model="doctor.contribution"
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
                    v-model="doctor.computePF"
                    autocomplete="off"
                  ></el-input>
                </el-form-item>
              </el-card>
            </el-col>
          </div>
        </div>

        <div v-if="personnels.length >= 1">
          <div v-for="(personnel, index) in personnels" :key="index">
            <el-col :span="12">
              <el-card class="box-card">
                <div slot="header" class="clearfix">
                  <span>Physician</span>
                  <el-form-item>
                    <el-button
                      @click="removeStaff"
                      v-if="index >= 0"
                      style="float: right; padding: 3px 0"
                      >Close</el-button
                    >
                  </el-form-item>
                </div>

                <el-form-item label="Personnel" :label-width="formLabelWidth">
                  <el-autocomplete
                    width="200px"
                    class="inline-input"
                    v-model="personnel.state"
                    :fetch-suggestions="querySearch"
                    placeholder="Please Input"
                    :trigger-on-focus="false"
                    @select="handleSelect"
                  ></el-autocomplete>
                  <br />
                  <span class="font-italic text-danger" v-if="errors.state"
                    ><small>{{ errors.state[0] }}</small></span
                  >
                </el-form-item>

                <el-form-item label="Contribution" prop="contribution_type">
                  <el-select
                    @change="getStaffCode"
                    v-model="personnel.contribution"
                  >
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
                  <br />
                  <span
                    class="font-italic text-danger"
                    v-if="errors.contributionType"
                    ><small>{{ errors.contributionType[0] }}</small></span
                  >
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
    </el-form>
  </el-card>
</template>
<script>
import constants from "../../constants.js";
const generateNewPersonnel = () => ({
  contribution: "",
  contributionType: "",
  state: "",
  staff: "",
});
export default {
  props: ["medicalRecordId", "totalFee", "contributionRecords"],
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
      file: {
        id: "",
        medical_record: "",
        item: "",
        total: 0,
      },
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
      otherButton: true,
      errors: [],
      disableSelect: [],
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
      attending: [],
      others: [],
      search_data: [],
      rules: {
        personnel: [
          {
            required: true,
            message: "Please input Personnel name",
            trigger: "blur",
          },
        ],
        attending: [
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
    triggerClose() {
      this.$emit("add-close");
    },
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
    },
    submitForm(formName) {
      this.$refs[formName].validate((valid) => {
        if (valid) {
          alert("submit!");
        } else {
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
      this.data.health = 0;
      this.data.er = 0;
      this.data.anesthe = 0;
      this.data.comanage = 0;
      this.data.admitting = 0;
      this.attending.forEach((element) => {
        if (element.contribution == 0) {
          this.data.attending += 1;
        }
      });
      this.personnels.forEach((element) => {
        if (element.contribution == 1) {
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
        } else if (element.contribution == 7) {
          this.data.admitting += 1;
        }
      });
      var holder = 0;
      var total = 0;

      this.attending.forEach((element) => {
        holder = this.medical_record_shared;
        total = Number(holder);
        this.personnels.forEach((element) => {
          if (element.contribution == "1") {
            total = total - this.medical_record_shared * 0.1;
            element.computePF = this.masknumber(
              this.medical_record_shared * 0.1,
              false
            );
          } else if (element.contribution == "2") {
            total = total - this.medical_record_shared * 0.1;
            element.computePF = this.masknumber(
              this.medical_record_shared * 0.1,
              false
            );
          } else if (element.contribution == "3") {
            total = total - this.medical_record_shared * 0.1;
            element.computePF = this.masknumber(
              this.medical_record_shared * 0.1,
              false
            );
          } else if (element.contribution == "4") {
            total = total - this.medical_record_shared * 0.1;
            element.computePF = this.masknumber(
              this.medical_record_shared * 0.1,
              false
            );
          } else if (element.contribution == "5") {
            total = total - this.medical_record_shared * 0.3;
            element.computePF = this.masknumber(
              this.medical_record_shared * 0.3,
              false
            );
          } else if (element.contribution == "6") {
            total = total - this.medical_record_shared * 0.2;
            element.computePF = this.masknumber(
              this.medical_record_shared * 0.2,
              false
            );
          } else if (element.contribution == "7") {
            total = total - this.medical_record_shared * 0.1;
            element.computePF = this.masknumber(
              this.medical_record_shared * 0.1,
              false
            );
          }
        });

        if (this.contributionRecords.type == undefined) {
          this.attending.forEach((element1) => {
            if (element.contribution == "0") {
              element1.computePF = this.masknumber(
                total / this.data.attending,
                false
              );
            }
          });
        } else {
          let temp =
            this.data.requesting * (this.medical_record_shared * 0.1) +
            this.data.surgeon * (this.medical_record_shared * 0.1) +
            this.data.health * (this.medical_record_shared * 0.1) +
            this.data.er * (this.medical_record_shared * 0.1) +
            this.data.anesthe * (this.medical_record_shared * 0.3) +
            this.data.comanage * (this.medical_record_shared * 0.2) +
            this.data.admitting * (this.medical_record_shared * 0.1);
          total =
            Number(this.contributionRecords.totalContributions - temp) /
            (Number(this.contributionRecords.totalAttending) +
              Number(this.data.attending));
          this.file.total = total;
          this.attending.forEach((element1) => {
            if (element.contribution == "0") {
              element1.computePF = this.masknumber(total, false);
            }
          });
        }
      });
      this.personnels.forEach((element) => {
        if (element.contribution == "1") {
          total = (Number(this.totalFee) / 2) * 0.7 * 0.1;
          element.computePF = this.masknumber(total, false);
        } else if (element.contribution == "2") {
          total = (Number(this.totalFee) / 2) * 0.7 * 0.1;
          element.computePF = this.masknumber(total, false);
        } else if (element.contribution == "3") {
          total = (Number(this.totalFee) / 2) * 0.7 * 0.1;
          element.computePF = this.masknumber(total, false);
        } else if (element.contribution == "4") {
          total = (Number(this.totalFee) / 2) * 0.7 * 0.1;
          element.computePF = this.masknumber(total, false);
        } else if (element.contribution == "5") {
          total = (Number(this.totalFee) / 2) * 0.7 * 0.3;
          element.computePF = this.masknumber(total, false);
        } else if (element.contribution == "6") {
          total = (Number(this.totalFee) / 2) * 0.7 * 0.2;
          element.computePF = this.masknumber(total, false);
        } else if (element.contribution == "7") {
          total = (Number(this.totalFee) / 2) * 0.7 * 0.1;
          element.computePF = this.masknumber(total, false);
        }
      });
    },
    addStaff() {
      this.personnels.push(new generateNewPersonnel());
    },
    addAttending() {
      if (this.contributionRecords.type == undefined) {
        this.attending.push(new generateNewPersonnel());
        this.attending[Number(this.attending.length) - 1].contribution = "0";
        this.disableSelect.push(true);
        this.getStaffCode();
      } else {
        this.attending.push(new generateNewPersonnel());
        this.attending[Number(this.attending.length) - 1].contribution = "0";
        this.disableSelect.push(true);
        this.getStaffCode();
      }
    },
    removeAttending() {
      if (this.contributionRecords.type == undefined) {
        if (this.attending.length > 1) {
          this.attending.pop(this.attending[this.attending.length - 1]);
          this.disableSelect.pop(
            this.disableSelect[this.disableSelect.length - 1]
          );
        }
      } else {
        if (this.attending.length >= 0) {
          this.attending.pop(this.attending[this.attending.length - 1]);
          this.disableSelect.pop(
            this.disableSelect[this.disableSelect.length - 1]
          );
        }
      }

      this.getStaffCode();
    },
    removeStaff() {
      if (this.personnels.length >= 0) {
        this.personnels.pop(this.personnels[this.personnels.length - 1]);
      }
      this.getStaffCode();
    },
    handleSelect(item) {
      var index = this.personnels.length - 1;
      this.personnels[index]["is_parttime"] = null;
      this.personnels[index]["is_private"] = null;
      this.personnels[index]["designation"] = null;
      this.personnels[index]["staff"] = null;
      this.personnels[index]["is_parttime"] = item.is_parttime;
      this.personnels[index]["is_private"] = item.is_private;
      this.personnels[index]["designation"] = item.designation;
      this.personnels[index]["staff"] = item.id;
    },
    handleSelectAttending(item) {
      var index = this.attending.length - 1;
      this.attending[index]["is_parttime"] = null;
      this.attending[index]["is_private"] = null;
      this.attending[index]["designation"] = null;
      this.attending[index]["staff"] = null;
      this.attending[index]["is_parttime"] = item.is_parttime;
      this.attending[index]["is_private"] = item.is_private;
      this.attending[index]["designation"] = item.designation;
      this.attending[index]["staff"] = item.id;
    },
    querySearch(queryString, cb) {
      var links = this.staff;
      var results = queryString
        ? links.filter(this.createFilter(queryString))
        : links;
      cb(results);
    },
    querySearchAttending(queryString, cb) {
      var links = this.staff;
      var results = queryString
        ? links.filter(this.createFilter(queryString))
        : links;
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
      this.personnels.forEach((element) => {
        element.contributionType =
          constants.contributionType[Number(element.contribution)];
        this.others.push(element);
      });
      this.attending.forEach((element) => {
        element.contributionType =
          constants.contributionType[Number(element.contribution)];
        this.others.push(element);
      });

      var data = {
        medical_record_id: this.medicalRecordId,
        personnel: this.others,
      };
      var errorCounter = 0;
      this.others.forEach((element) => {
        if (
          element.state == "" ||
          element.computePF == "" ||
          element.contribution == null ||
          element.contribution == ""
        ) {
          errorCounter++;
        }
      });
      if (errorCounter == 0) {
        if (data.personnel.length > 0) {
          axios
            .post("/user/contrirecord_add", data)
            .then((response) => {
              if (response.data.status == 200) {
                this.open_notif(
                  "success",
                  "Success",
                  "Contribution added successfully"
                );
                this.triggerClose();
              }
            })
            .catch((error) => {});
        }
        if (
          this.contributionRecords.type == undefined &&
          data.personnel.length > 0 || 
          this.contributionRecords.type == true &&
          data.personnel.length > 0 
        ) {
          this.file.item = this.contributionRecords;
          this.file.total =
            Number(this.contributionRecords.totalAttending) *
            Number(this.contributionRecords.contributions[0].credit);
          this.personnels.forEach((element) => {
            this.file.total = Number(
              Number(this.file.total) - Number(element.computePF)
            );
          });
          this.file.total /=
            this.attending.length + this.contributionRecords.totalAttending;
          var _this = this;
          axios
            .post(
              "contribution_edit/" +
                this.contributionRecords.contributions[0].id,
              _this.file
            )
            .then((response) => {
              if (response.status > 199 && response.status < 203) {
                _this.open_notif(
                  "success",
                  "Success",
                  "Contribution update successfully"
                );
              }
            })
            .catch((error) => {});
        } else {
          this.open_notif("info", "Message", "Please add Physician");
        }
      } else {
        this.open_notif(
          "info",
          "Message",
          "Required fields were missing values"
        );
      }
      this.others = [];
      errorCounter = 0;
    },
    open_notif: function (status, title, message) {
      if (status == "success") {
        this.$notify.success({
          title: title,
          message: message,
          offset: 0,
        });
      } else if (status == "error") {
        this.$notify.error({
          title: title,
          message: message,
          offset: 0,
        });
      } else if (status == "info") {
        this.$notify.info({
          title: title,
          message: message,
          offset: 0,
        });
      } else if (status == "warning") {
        this.$notify.warning({
          title: title,
          message: message,
          offset: 0,
        });
      }
    },
    masknumber: function (theform, mode) {
      var num = theform,
        rounded;
      if (mode == true) {
        var with2Decimals = num
          .toString()
          .match(/^-?\d+(?:\.\d{0,4})?/)[0]
          .replace(/(\d)(?=(\d{3})+\.)/g, "$1,");
      } else {
        var with2Decimals = num.toString().match(/^-?\d+(?:\.\d{0,4})?/)[0];
      }
      return with2Decimals;
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
        })
        .catch(function (error) {});
    },
  },
  computed: {},
  mounted() {
    if (this.contributionRecords.type == undefined) {
      this.getStaff();
      this.attending.push(new generateNewPersonnel());
      this.medical_record_shared = (this.totalFee / 2) * 0.7;
      this.medical_record_pooled = (this.totalFee / 2) * 0.3;
      this.attending[0].contribution = "0";
      this.getStaffCode();
      this.disableSelect.push(true);
    } else {
      this.getStaff();
      this.getStaffCode();
      this.medical_record_shared = Number((this.totalFee / 2) * 0.7);
      this.medical_record_pooled = (this.totalFee / 2) * 0.3;
    }
  },
};
</script>
