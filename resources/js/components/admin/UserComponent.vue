<template>
  <div>
    <div class="row">
      <div class="col-sm-12">
        <h2>Hospital List</h2>
      </div>
    </div>
    <hr />
    <div class="row">
      <div class="col-sm-10" align="left">
        <div style="margin-bottom: 10px">
          <el-row>
            <el-col :span="10">
              <el-input
                v-model="filters[0].value"
                placeholder="Search"
              ></el-input>
            </el-col>
          </el-row>
        </div>
      </div>
      <div class="col-sm-2" align="right">
        <el-button type="primary" @click="clearfield();dialogFormVisible = true;form.formmode='add_hospital'">Add</el-button>
      </div>
    </div>
    <div class="card">
      <div class="card-body">
        <data-tables
          :data="data"
          :page-size="10"
          :filters="filters"
          :pagination-props="{ pageSizes: [10, 20, 50] }"
          :action-col="actionCol"
        >
          <div slot="empty">Table Empty</div>
          <el-table-column
            v-for="title in titles"
            :prop="title.prop"
            :label="title.label"
            :key="title.label"
            :width="title.width"
            sortable="custom"
          >
          </el-table-column>
          <p slot="append"></p>
        </data-tables>

        <!--Add Hospital-->
        <el-dialog
          title="Hospital Details"
          :visible.sync="dialogFormVisible"
          top="2vh"
        >
            <el-form :model="form" :rules="rules" ref="form">
                <el-form-item label="Hospital Code" :label-width="formLabelWidth" prop="hospital_code">
                  <el-input v-model="form.hospital_code" autocomplete="off" ></el-input>
                </el-form-item>
                <el-form-item label="Hospital Name" :label-width="formLabelWidth" prop="name">
                  <el-input v-model="form.name" autocomplete="off" ></el-input>
                </el-form-item>
                <el-form-item label="Location" :label-width="formLabelWidth" prop="address">
                  <el-input v-model="form.address" autocomplete="off" ></el-input>
                </el-form-item>
                <el-form-item label="Email Address" :label-width="formLabelWidth" prop="email_address">
                  <el-input v-model="form.email_address" autocomplete="off" ></el-input>
                </el-form-item>
            </el-form>
            <span slot="footer" class="dialog-footer">
              <el-button @click="dialogFormVisible = false">Cancel</el-button>
              <el-button v-if="form.formmode=='add_hospital'" type="primary" @click="formDialog('add_hospital')">Save</el-button>
              <el-button v-if="form.formmode=='edit_hospital'"  type="primary" @click="formDialog('edit_hospital')">Save changes</el-button>
            </span>
        </el-dialog>
        <!--End Hospital-->

        <!--User List-->
        <el-dialog
          :title="userdialog_title"
          :visible.sync="dialogFormVisible_user"
          top="2vh"
          width = "80%"
          :close-on-click-modal = false
          :close-on-press-escape = false
        >
         <el-tooltip content="Notify user for your changes via email" placement="top">
          <el-switch v-model="notify" style="float: right"></el-switch>
         </el-tooltip>
          <el-form :inline="true" :model="formUser" :rules="rulesUser" ref="formUser" class="demo-form-inline">
            <el-alert v-if="alert_match === 1" title="Password match" type="success" show-icon> </el-alert>
            <el-alert v-if="alert_match === 2" title="Password didn't match" type="warning" show-icon></el-alert>
              <el-form-item label="" prop="username">
                <el-input v-model="formUser.username" size="small" placeholder="* Username"></el-input>
              </el-form-item>
              <el-form-item label="" prop="password">
                <el-input placeholder="* Password" v-model="formUser.password" size="small" @input.native="checkmatch" show-password></el-input>
              </el-form-item>
              <el-form-item label="" prop="repassword">
                <el-input placeholder="* Confirm password" v-model="formUser.repassword" size="small" @input.native="checkmatch" show-password></el-input>
              </el-form-item>
              <el-form-item>
                <el-button type="primary" size="small" @click="addUser" :disabled="formUser.username == '' || formUser.password == '' || formUser.repassword == '' ">Add new user</el-button>
              </el-form-item>
          </el-form>

              <el-table
                :data="userdata.filter(userdata => !search || userdata.username.toLowerCase().includes(search.toLowerCase()))"
                style="width: 100%">
                <el-table-column
                  label="Username"
                  prop="username">
                </el-table-column>
                <el-table-column
                  label="Created at"
                  prop="created_at">
                </el-table-column>
                <el-table-column
                  label="Updated at"
                  prop="updated_at">
                </el-table-column>
                <el-table-column
                  align="right">
                  <template slot="header" slot-scope="scope">
                    <el-input
                      v-model="search"
                      size="small"
                      placeholder="Type to search username"/>
                  </template>
                  <template slot-scope="scope">
                    <el-button
                      size="mini"
                      type="danger"
                      @click="handleEditUser_reset(scope.$index, scope.row)">Reset Password</el-button>
                    <el-button
                      size="mini"
                      type="primary"
                      @click="handleEditUser(scope.$index, scope.row)">Edit</el-button>
                  </template>
                </el-table-column>
              </el-table>
            <span slot="footer" class="dialog-footer">
              <el-button @click="dialogFormVisible_user = false">Close</el-button>
            </span>
        </el-dialog>
        <!--End User List-->

      </div>
    </div>
  </div>
</template>

<script>
"use strict";
export default {
  
  data() {
    return {
      data: [],
      userdata: [],
      formUser: {
          username: '',
          password: '',
          repassword: '',
          hospital_id: '',
        },
      userdialog_title: "",
      rules: {
        hospital_code: [
          { required: true, message: "Hospital Code is required.", trigger: "blur" },
        ],
        name: [
          { required: true, message: "Hospital Name is required.", trigger: "blur" },
        ],
        address: [
          { required: true, message: "Hospital Location is required.", trigger: "blur" },
        ],
        email_address: [
          { type: 'email', required: true, message: "Please input correct email address", trigger: ['blur', 'change'] },
        ],
      },
      rulesUser: {
        username: [
          { required: true, message: "Username is required.", trigger: "blur" },
        ],
        password: [
          {
            required: true,
            message: "Password is required.",
            trigger: "blur",
          },
        ],
        repassword: [
          {
            required: true,
            message: "Confirm Password is required.",
            trigger: "blur",
          },
        ],
      },
      filters: [
        {
          prop: ["hospital_name", "address", "email_address"],
          value: "",
        },
      ],
      titles: [
        {
          prop: "hospital_name",
          label: "Hospital",
          width: "400px"
        },
        {
          prop: "address",
          label: "Address / Location",
        },
        {
          prop: "email_address",
          label: "Email address",
        },
      ],
      actionCol: {
        label: "Actions",
        props: {
          align: "center",
        },
        buttons: [
          {
            props: {
              type: "success",
              icon: "el-icon-user-solid",
              circle: true,
              size: "mini",
            },
            handler: (row) => {
              this.hospital_id = row.id;
              this.formUser.hospital_id = row.id;
              this.userdialog_title = row.hospital_code + " - User Account";
              this.hospital_email = row.email_address;
              this.hospital_code = row.hospital_code;

              var data = this.data[parseInt(this.data.indexOf(row))].users;
              this.data_hospital_index = parseInt(this.data.indexOf(row));

              data.forEach((entry)=>{
                  var date = new Date(entry.created_at);
                  entry.created_at = ((date.getMonth() > 8) ? (date.getMonth() + 1) : ('0' + (date.getMonth() + 1))) + '/' + ((date.getDate() > 9) ? date.getDate() : ('0' + date.getDate())) + '/' + date.getFullYear();
                  var date = new Date(entry.updated_at);
                  entry.updated_at = ((date.getMonth() > 8) ? (date.getMonth() + 1) : ('0' + (date.getMonth() + 1))) + '/' + ((date.getDate() > 9) ? date.getDate() : ('0' + date.getDate())) + '/' + date.getFullYear();
              });
              this.userdata = data;
              this.dialogFormVisible_user = true;
            },
          },
          {
            props: {
              type: "primary",
              icon: "el-icon-edit",
              circle: true,
              size: "mini",
            },
            handler: (row) => {
              this.form.id = row.id;
              this.form.hospital_code=row.hospital_code;
              this.form.name=row.name;
              this.form.address=row.address;
              this.form.email_address=row.email_address;
              
              this.form_check.hospital_code=row.hospital_code;
              this.form_check.name=row.name;
              this.form_check.address=row.address;
              this.form_check.email_address=row.email_address;

              this.form.edit_object_index = this.data.indexOf(row);

              this.dialogFormVisible = true;
              this.form.formmode='edit_hospital';
            },
          },
        ],
      },
      layout: "pagination, table",
      dialogTableVisible: false,
      dialogFormVisible: false,
      dialogFormVisible_user: false,
      form: {
        id: "",
        hospital_code: "",
        name: "",
        address: "",
        email_address: "",
        edit_object_index:"",
        formmode:"",
      },
      form_check: {
        hospital_code: "",
        name: "",
        address: "",
        email_address: "",
      },
      formLabelWidth: "120px",
      formLabelWidth_user: "120px",
      alert_match: '',
      hospital_id:"",
      hospital_row:"",
      search: '',
      data_hospital_index: '',
      hospital_email: '',
      hospital_code: '',
      notify: false,
    };
  },
  methods: {
    checkmatch(event){
      if(this.formUser.password == this.formUser.repassword && this.formUser.password != '' && this.formUser.repassword != ''){
        this.alert_match = 1;
      }else if(this.formUser.password != this.formUser.repassword && this.formUser.password != '' && this.formUser.repassword != ''){
        this.alert_match = 2;
      }else{
        this.alert_match = '';
      }
    },
    getHospital: function () {
      axios
        .get("hospitals_get")
        .then((response) => {
          response.data.forEach((entry)=>{
            entry.hospital_name = entry.name + " (" + entry.hospital_code + ")";
          });
          this.data = response.data;
        })
        .catch(function (error) {});
    },
    clearfield:function(){
      this.form.hospital_code="";
      this.form.name="";
      this.form.address="";
      this.form.email_address="";
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
    formDialog:  function (mode) {
      function validateEmail(email) { var re = /\S+@\S+\.\S+/;  return re.test(email); }
      switch (mode) {
        case 'add_hospital':      
            if(validateEmail(this.form.email_address) === true && 
              this.form.hospital_code != "" &&
              this.form.name != "" && 
              this.form.address != "" &&
              this.form.email_address != "")
            {
              axios
                .post("add_hospital", this.form)
                .then((response) => {
                  if (response.status > 199 && response.status < 203) {
                    this.getHospital();
                    this.open_notif(
                      "success",
                      "Success",
                      "Hospital added successfully"
                    );
                    this.dialogFormVisible = false;
                    this.clearfield();
                  } else {
                    this.open_notif("error", "System", "Failed to add hospital");
                  }
                })
                .catch((error) => {
                  this.errors = error.response.data.errors;
                });
            }else{ this.open_notif("warning", "System", "Make sure you input valid"); }
          break;
        case 'edit_hospital':
              var _this = this;
              if (
                this.form.hospital_code == this.form_check.hospital_code &&
                this.form.name == this.form_check.name &&
                this.form.address == this.form_check.address &&
                this.form.email_address == this.form_check.email_address
              ) {
                _this.open_notif("info", "Message", "No Changes");
              } else {
                  if(validateEmail(this.form.email_address) === true){
                    axios
                      .post("hospital_edit/" + this.form.id, this.form)
                      .then((response) => {
                        if (response.status > 199 && response.status < 203) {
                          _this.open_notif("success", "Success", "Changes has been saved");
                          this.dialogFormVisible = false;
                          this.clearfield();
                          _this.getHospital();
                        }
                      })
                      .catch(function (error) {});
                  }else{ this.open_notif("warning", "System", "Please input correct email address"); }
              }
          break;
      }
    },
    addUser() {
        var _this = this;
        this.formUser.hospital_email = this.hospital_email;
        this.formUser.hospital_code = this.hospital_code;
        this.formUser.changes = "System administrator added a new user, with the username '" + this.formUser.username + "'";
        this.formUser.notify = this.notify;
        if(this.formUser.password == this.formUser.repassword){
          axios
          .post("add_user", this.formUser)
          .then((response) => {
            if (response.status > 199 && response.status < 203) {
                axios
                .get("hospitals_get")
                .then((response) => {
                    if (response.status > 199 && response.status < 203) {
                        response.data.forEach((entry)=>{  entry.hospital_name = entry.name + " (" + entry.hospital_code + ")";  });
                        _this.data = response.data;

                        var data = _this.data[parseInt(_this.data_hospital_index)].users;
                        data.forEach((entry)=>{
                            var date = new Date(entry.created_at);
                            entry.created_at = ((date.getMonth() > 8) ? (date.getMonth() + 1) : ('0' + (date.getMonth() + 1))) + '/' + ((date.getDate() > 9) ? date.getDate() : ('0' + date.getDate())) + '/' + date.getFullYear();
                            var date = new Date(entry.updated_at);
                            entry.updated_at = ((date.getMonth() > 8) ? (date.getMonth() + 1) : ('0' + (date.getMonth() + 1))) + '/' + ((date.getDate() > 9) ? date.getDate() : ('0' + date.getDate())) + '/' + date.getFullYear();
                        });
                        _this.userdata = data;

                        _this.formUser.username = '';
                        _this.formUser.password = '';
                        _this.formUser.repassword = '';
                        _this.alert_match = '';
                    }
                })
                .catch(function (error) {});
                _this.open_notif(
                  "success",
                  "Success",
                  "User added successfully"
                );
            } else {
              this.open_notif("error", "System", "Failed to add user");
            }
          })
          .catch((error) => {
            //this.errors = error.response.data.errors;
          });
        } else {
              this.open_notif("warning", "Invalid", "Password confirm didn't match");
        }
    },
    handleEditUser(index, row) {
        row.hospital_email = this.hospital_email;
        row.hospital_code = this.hospital_code;
        row.notify = this.notify;
        var old_username = row.username;
        var rowusername = row.username;
        this.$prompt('Please input username', 'Edit user', {
          confirmButtonText: 'SAVE CHANGES',
          cancelButtonText: 'Cancel',
          closeOnPressEscape: false,
          closeOnClickModal: false,
          inputValue: row.username,
          beforeClose: 
                      (action, instance, done) => {
                        if (action === 'confirm') {
                            if(instance._data.inputValue != null && instance._data.inputValue != ''){
                                if(old_username === instance._data.inputValue){
                                  this.open_notif("info", "System", "No changes has been made");
                                }else{
                                  instance.confirmButtonLoading = true;
                                  instance.confirmButtonText = 'Saving...';
                                  row.username = instance._data.inputValue;

                                  row.changes = "System administrator edit the existing user '" + old_username + "' to '" + instance._data.inputValue + "'";

                                  axios
                                    .post("user_edit/" + row.id, row)
                                    .then((response) => {
                                      if (response.status > 199 && response.status < 203) {
                                        setTimeout(() => {
                                          done();
                                          setTimeout(() => {
                                            instance.confirmButtonLoading = false;
                                          }, 200);
                                        }, 2000);
                                      }
                                    })
                                    .catch(function (error) { row.username = rowusername; });
                                }
                            }else{ row.username = rowusername; this.open_notif('warning','Invalid!','Field required'); }
                        } else {done();}
                      }
        }).then(({ value }) => {
            this.open_notif("success", "Success", "Changes has been saved");
        }).catch(() => {
          this.open_notif('info','Cancelled','No changes');    
        });
    },
    handleEditUser_reset(index, row) {
        row.hospital_email = this.hospital_email;
        row.hospital_code = this.hospital_code;
        row.changes = "System administrator reset user password to default for '" + row.username + "'";
        row.notify = this.notify;
        this.$confirm('This will reset password to default. Continue?', 'Warning', {
          confirmButtonText: 'RESET PASSWORD',
          cancelButtonText: 'Cancel',
          type: 'warning'
        }).then(() => {
            axios
              .post("user_edit_resetpass/" + row.id, row)
              .then((response) => {
                if (response.status > 199 && response.status < 203) {
                  this.open_notif("success", "Success", "Reset password to default, changes has been saved");
                }
              })
              .catch(function (error) {});
        }).catch(() => {
          this.open_notif('info','Cancelled','No changes');           
        });
    },
  },
  mounted() {
    this.getHospital();
  },
};
</script>
